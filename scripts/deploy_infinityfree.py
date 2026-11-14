#!/usr/bin/env python3
"""
Automated FTP Deployment for IT0049 POS TFA8 to InfinityFree.
Reads credentials from environment variables or gitignored .deploy.env.
"""

import ftplib
import os
import subprocess
from pathlib import Path

BASE_DIR = Path(__file__).resolve().parent.parent
ZIP_PATH = BASE_DIR / "tfa8-infinityfree.zip"
EXTRACT_PHP = BASE_DIR / "extract.php"


def load_dotenv(path: Path) -> None:
    if not path.exists():
        return
    for line in path.read_text().splitlines():
        line = line.strip()
        if not line or line.startswith("#") or "=" not in line:
            continue
        key, val = line.split("=", 1)
        os.environ.setdefault(key.strip(), val.strip().strip('"').strip("'"))


def load_credentials() -> dict:
    load_dotenv(BASE_DIR / ".deploy.env")
    user = os.getenv("FTP_USER", "")
    password = os.getenv("FTP_PASS", "")
    if not user or not password:
        raise RuntimeError("Missing FTP_USER or FTP_PASS in environment or .deploy.env")
    return {
        "FTP_HOST": os.getenv("FTP_HOST", "ftpupload.net"),
        "FTP_PORT": int(os.getenv("FTP_PORT", "21")),
        "FTP_USER": user,
        "FTP_PASS": password,
        "APP_URL": os.getenv("APP_URL", "https://tfa8.infinityfreeapp.com"),
    }


def build_package() -> None:
    print("==> Packaging lean production build via build-infinityfree-zip.sh ...")
    res = subprocess.run([str(BASE_DIR / "build-infinityfree-zip.sh")], cwd=str(BASE_DIR))
    if res.returncode != 0:
        raise RuntimeError("Build script failed!")


def deploy() -> None:
    creds = load_credentials()
    if not ZIP_PATH.exists():
        build_package()

    print(f"Connecting to FTP {creds['FTP_HOST']}:{creds['FTP_PORT']} as {creds['FTP_USER']} ...")
    ftp = ftplib.FTP(timeout=45)
    ftp.connect(creds["FTP_HOST"], creds["FTP_PORT"])
    ftp.login(creds["FTP_USER"], creds["FTP_PASS"])
    print("FTP connected and authenticated.")

    try:
        ftp.cwd("htdocs")
        try:
            if "index2.html" in ftp.nlst():
                ftp.delete("index2.html")
                print("Removed default hosting placeholder 'index2.html'.")
        except Exception as exc:
            print(f"Note on index2.html removal: {exc}")

        zip_size_mb = ZIP_PATH.stat().st_size / (1024 * 1024)
        print(f"==> Uploading {ZIP_PATH.name} ({zip_size_mb:.2f} MB) ...")
        with open(ZIP_PATH, "rb") as f:
            ftp.storbinary(f"STOR {ZIP_PATH.name}", f, blocksize=65536)

        print("==> Uploading extract.php ...")
        with open(EXTRACT_PHP, "rb") as f:
            ftp.storbinary("STOR extract.php", f)

        print("\nUpload complete! Trigger extraction at:")
        print(f"  {creds['APP_URL']}/extract.php")
    finally:
        try:
            ftp.quit()
        except Exception:
            pass


if __name__ == "__main__":
    deploy()
