<?php

/**
 * InfinityFree Deployment Extractor & Database Provisioner (TFA8)
 * Unpacks tfa8-infinityfree.zip into /htdocs, sets permissions, creates storage link if supported,
 * imports schema_export.sql using credentials from .env, cleans up, and self-destructs.
 */
header('Content-Type: text/plain; charset=utf-8');

$zipFile = __DIR__.'/tfa8-infinityfree.zip';

if (! file_exists($zipFile)) {
    http_response_code(404);
    echo 'ERROR: Deployment package not found at '.$zipFile."\n";
    exit(1);
}

if (! class_exists('ZipArchive')) {
    http_response_code(500);
    echo "ERROR: ZipArchive extension is not enabled in this PHP environment.\n";
    exit(1);
}

/**
 * Minimal .env reader for extracting MySQL credentials post-unpack.
 *
 * @return array<string, string>
 */
function loadEnvFile(string $path): array
{
    $vars = [];
    if (! file_exists($path)) {
        return $vars;
    }

    foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#') || ! str_contains($line, '=')) {
            continue;
        }
        [$key, $value] = explode('=', $line, 2);
        $vars[trim($key)] = trim($value, " \t\n\r\0\x0B\"'");
    }

    return $vars;
}

$zip = new ZipArchive;
$res = $zip->open($zipFile);

if ($res !== true) {
    http_response_code(500);
    echo 'ERROR: Could not open zip file. Error code: '.$res."\n";
    exit(1);
}

$extracted = $zip->extractTo(__DIR__);
$zip->close();

if (! $extracted) {
    http_response_code(500);
    echo 'ERROR: Failed during extraction to '.__DIR__."\n";
    exit(1);
}

@unlink($zipFile);
echo "SUCCESS: Extracted tfa8-infinityfree.zip successfully!\n";

if (file_exists(__DIR__.'/index2.html')) {
    @unlink(__DIR__.'/index2.html');
    echo "Removed default InfinityFree index2.html placeholder.\n";
}

// Ensure writable storage & cache directories exist (including public/avatars for uploads)
$storageDirs = [
    __DIR__.'/storage/app/public/avatars',
    __DIR__.'/storage/framework/cache/data',
    __DIR__.'/storage/framework/sessions',
    __DIR__.'/storage/framework/views',
    __DIR__.'/storage/logs',
    __DIR__.'/bootstrap/cache',
];
foreach ($storageDirs as $sDir) {
    if (! is_dir($sDir)) {
        @mkdir($sDir, 0775, true);
    }
    @chmod($sDir, 0775);
}

// Create symlink when supported by host; Laravel fallback route still serves /storage/* if blocked
$linkTarget = __DIR__.'/storage/app/public';
$linkPath = __DIR__.'/public/storage';
if (! file_exists($linkPath) && function_exists('symlink')) {
    @symlink($linkTarget, $linkPath);
}

$envFile = __DIR__.'/.env';
$env = loadEnvFile($envFile);
$sqlFile = __DIR__.'/database/schema_export.sql';

$dbHost = $env['DB_HOST'] ?? '';
$dbName = $env['DB_DATABASE'] ?? '';
$dbUser = $env['DB_USERNAME'] ?? '';
$dbPass = $env['DB_PASSWORD'] ?? '';
$appUrl = $env['APP_URL'] ?? 'https://tfa8.infinityfreeapp.com';

$mysqlSuccess = false;
if ($dbHost !== '' && $dbName !== '' && $dbUser !== '' && file_exists($sqlFile)) {
    try {
        $dsn = "mysql:host={$dbHost};dbname={$dbName};charset=utf8mb4";
        $pdo = new PDO($dsn, $dbUser, $dbPass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_TIMEOUT => 10,
        ]);

        // Only initialize tables if users/customers do not already exist (preserve live uploads)
        $existingUsers = $pdo->query("SHOW TABLES LIKE 'users'")->fetchColumn();
        if ($existingUsers === false) {
            $sqlContent = file_get_contents($sqlFile);
            $statements = array_filter(array_map('trim', explode(';', (string) $sqlContent)));
            foreach ($statements as $stmt) {
                if ($stmt !== '' && ! preg_match('/^(--|\/\*)/', $stmt)) {
                    $pdo->exec($stmt);
                }
            }
            echo "SUCCESS: Imported MySQL schema and initial seed data into {$dbName}.\n";
        } else {
            // Ensure avatar column exists if upgrading from TFA6 schema
            $avatarCol = $pdo->query("SHOW COLUMNS FROM `users` LIKE 'avatar'")->fetchColumn();
            if ($avatarCol === false) {
                $pdo->exec('ALTER TABLE `users` ADD COLUMN `avatar` varchar(255) DEFAULT NULL AFTER `role`');
                echo "SUCCESS: Added avatar column to existing users table.\n";
            } else {
                echo "NOTICE: Existing MySQL tables and avatar column already present; preserved data.\n";
            }
        }
        $mysqlSuccess = true;
    } catch (Throwable $e) {
        echo 'NOTICE: MySQL initialization failed ('.$e->getMessage()."). Falling back to SQLite.\n";
    }
}

if (! $mysqlSuccess && file_exists($envFile)) {
    $envContent = (string) file_get_contents($envFile);
    $envContent = preg_replace('/^DB_CONNECTION=.*/m', 'DB_CONNECTION=sqlite', $envContent);
    file_put_contents($envFile, $envContent);
}

@unlink(__FILE__);
echo "Removed extract.php for security.\n";
echo "Deployment complete! Visit {$appUrl}/ to verify.\n";
