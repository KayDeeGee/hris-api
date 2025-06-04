<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreJobApplicationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<text, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'cover_letter' => ['nullable', 'string'],
            'resume_path' => ['nullable', 'string'],
            'job_id' => ['required', 'exists:job_posts,id'],

            // Guest-only fields
            'first_name' => ['required_without:user_id', 'string', 'max:255'],
            'last_name'  => ['required_without:user_id', 'string', 'max:255'],
            'email'      => ['required_without:user_id', 'email', 'max:255'],
            'phone'      => ['nullable', 'string', 'max:20'],
        ];
    }
}
