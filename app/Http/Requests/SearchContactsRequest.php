<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Request validation for contact search operations.
 *
 * Validates search parameters to ensure proper formatting and prevent
 * potential security issues with user input.
 */
class SearchContactsRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'search' => 'nullable|string|max:255',
            'per_page' => 'nullable|integer|min:1|max:100',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'search' => 'termo de busca',
            'per_page' => 'itens por página',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'search.string' => 'O :attribute deve ser um texto válido.',
            'search.max' => 'O :attribute não pode ter mais de 255 caracteres.',
            'per_page.integer' => 'O número de :attribute deve ser um número inteiro.',
            'per_page.min' => 'O número de :attribute deve ser pelo menos 1.',
            'per_page.max' => 'O número de :attribute não pode ser maior que 100.',
        ];
    }

    /**
     * Get the sanitized search term.
     */
    public function getSearch(): ?string
    {
        $search = $this->input('search');
        
        if (empty($search)) {
            return null;
        }
        
        return trim($search);
    }

    /**
     * Get the validated per page value.
     */
    public function getPerPage(): int
    {
        return (int) $this->input('per_page', 10);
    }
}
