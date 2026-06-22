<?php

namespace App\Http\Requests\Issue;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StoreIssueRequest extends FormRequest
{
    use ValidatesIssueAttributes;

    public function authorize(): bool
    {
        return $this->user()->can('createIssue', $this->route('project'));
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return $this->issueRules();
    }

    protected function failedValidation(Validator $validator): void
    {
        session()->flash('open_modal', 'create-issue');

        parent::failedValidation($validator);
    }
}
