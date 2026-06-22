<?php

namespace App\Http\Requests\Project;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProjectRequest extends FormRequest
{
    use ValidatesProjectAttributes;

    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('project'));
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
        session()->flash('open_modal', $this->route('project')->editModalName());

        parent::failedValidation($validator);
    }
}
