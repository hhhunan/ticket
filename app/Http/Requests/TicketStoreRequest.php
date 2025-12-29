<?php

namespace App\Http\Requests;

use App\Rules\PhoneNumber;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TicketStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', new PhoneNumber, 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'min:10'],
            'attachments' => ['nullable', 'array', 'max:5'],
            'attachments.*' => ['file', 'max:10240', 'mimes:jpg,jpeg,png,gif,pdf,doc,docx'],
        ];
    }

    public function messages(): array
    {
        return [
            'phone.regex' => 'Телефон должен быть в международном формате E.164',
            'attachments.*.max' => 'Размер файла не должен превышать 10MB',
        ];
    }
}
