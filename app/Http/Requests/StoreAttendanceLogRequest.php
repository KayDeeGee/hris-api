<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAttendanceLogRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */

    public function rules(): array
    {
        return [
            'employee_number' => ['required', 'string', 'exists:employees,employee_number'],
            'device_name'     => ['required', 'string', 'max:255'],
            'log_time'        => ['required', 'date'],
            'log_type'        => ['required', 'integer', 'in:1,2'],     // 1 = IN, 2 = OUT
            'log_method'      => ['required', 'integer', 'in:1,2'],     // 1 = QR, 2 = Button
        ];
    }

    public function messages(): array
    {
        return [
            'employee_number.exists' => 'Invalid employee number scanned.',
            'log_type.in' => 'Log type must be 1 (IN) or 2 (OUT).',
            'log_method.in' => 'Log method must be 1 (QR) or 2 (Button).',
        ];
    }
}
