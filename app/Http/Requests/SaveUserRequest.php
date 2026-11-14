<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to submit this form.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Normalize incoming user fields and default staff role before validation runs.
     */
    protected function prepareForValidation(): void
    {
        $role = trim((string) $this->input('role', ''));

        $this->merge([
            'username' => trim((string) $this->input('username', '')),
            'full_name' => trim((string) $this->input('full_name', '')),
            'role' => $role === '' ? 'Staff Member' : $role,
            'remove_avatar' => $this->boolean('remove_avatar'),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        /** @var User|null $user */
        $user = $this->route('user');

        return [
            'username' => [
                'required',
                'string',
                'max:50',
                'regex:/^[a-zA-Z0-9._-]+$/',
                Rule::unique('users', 'username')->ignore($user?->id),
            ],
            'full_name' => ['required', 'string', 'max:100'],
            'role' => ['nullable', 'string', 'max:50', Rule::in(User::ROLES)],
            'avatar' => ['nullable', 'file', 'image', 'max:2048'],
            'remove_avatar' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Get clear validation messages displayed in the user form summary and inline fields.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'username.required' => 'Username is required.',
            'username.max' => 'Username must not exceed 50 characters.',
            'username.regex' => 'Username may only contain letters, numbers, periods, hyphens, and underscores.',
            'username.unique' => 'That username is already assigned to another staff account.',
            'full_name.required' => 'Full name is required.',
            'full_name.max' => 'Full name must not exceed 100 characters.',
            'role.in' => 'Please choose one of the supported system roles.',
            'avatar.file' => 'Please select a valid profile picture file.',
            'avatar.image' => 'The profile picture must be a valid image file (JPG, PNG, GIF, BMP, or WebP).',
            'avatar.max' => 'The profile picture must not be larger than 2 MB.',
        ];
    }
}
