<?php

namespace App\Http\Requests\Tag;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StoreTagRequest extends FormRequest
{
    use ValidatesTagAttributes;

    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return $this->tagRules();
    }

    protected function failedValidation(Validator $validator): void
    {
        session()->flash('open_modal', 'create-tag');

        parent::failedValidation($validator);
    }
}
