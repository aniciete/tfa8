<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Supported operational staff roles displayed in user forms.
     *
     * @var list<string>
     */
    public const ROLES = [
        'Store Administrator',
        'Shift Supervisor',
        'Cashier',
        'Inventory Clerk',
        'Staff Member',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'full_name',
        'role',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'remember_token',
    ];

    /**
     * Determine whether the user has an existing avatar stored on the public disk.
     */
    public function hasAvatar(): bool
    {
        return ! empty($this->avatar) && Storage::disk('public')->exists($this->avatar);
    }

    /**
     * Resolve the public image URL for this user's avatar, falling back to the placeholder asset.
     */
    public function avatarUrl(): string
    {
        if ($this->hasAvatar()) {
            $url = is_file(public_path('storage/'.$this->avatar))
                ? Storage::disk('public')->url($this->avatar)
                : route('avatars.show', ['filename' => basename($this->avatar)]);

            return (string) preg_replace('~^https?:~', '', $url);
        }

        return (string) preg_replace('~^https?:~', '', asset('assets/images/avatar-placeholder.svg'));
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
