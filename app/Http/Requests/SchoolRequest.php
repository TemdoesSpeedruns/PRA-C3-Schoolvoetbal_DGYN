<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SchoolRequest extends FormRequest
{
    public function authorize()
    {
        // Pas aan als je permissies wil controleren
        return $this->user() !== null;
    }

    public function rules()
    {
        $schoolId = $this->route('school') ? $this->route('school')->id : null;

        return [
            'name' => ['required', 'string', 'max:255'],
            'contact_person' => ['nullable', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('schools', 'email')->ignore($schoolId),
            ],
            'status' => ['nullable', Rule::in(['pending', 'approved', 'rejected'])],
        ];
    }
}