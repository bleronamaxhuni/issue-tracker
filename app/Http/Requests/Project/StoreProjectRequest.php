<?php

namespace App\Http\Requests\Project;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
{
    use ValidatesProjectAttributes;

    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return $this->projectRules();
    }

    protected function failedValidation(Validator $validator): void
    {
        session()->flash('open_modal', 'create-project');

        parent::failedValidation($validator);
    }
}
