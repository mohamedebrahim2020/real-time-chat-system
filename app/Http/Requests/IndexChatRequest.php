<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IndexChatRequest extends FormRequest
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
            'user_id' => 'required|integer|exists:users,id',
        ];
    }


	// Merge route parameter into request data
	protected function prepareForValidation()
	{
		$this->merge([
			'user_id' => $this->route('user_id'),
		]);
	}
}
