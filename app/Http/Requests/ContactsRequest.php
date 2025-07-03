<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactsRequest extends FormRequest
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
        $contactId = $this->route('contact') ? $this->route('contact')->id : null;
        
        return [
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|email|max:255|unique:contacts,email,' . $contactId,
            'phone' => 'required|string|min:10|max:20',
        ];
    }
}
