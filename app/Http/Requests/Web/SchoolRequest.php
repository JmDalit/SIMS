<?php

namespace App\Http\Requests\Web;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SchoolRequest extends FormRequest
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
        switch ($this->type) {
            case 'delete':
                return [
                    'isDelete' => ['boolean']
                ];
                break;
            case 'status':
                return [
                    'isActive' => ['boolean']
                ];
                break;
            default:
                return [
                    'name' => ['required', 'string', Rule::unique('schools')->ignore($this->id)],
                    'abbreviation' => ['required', 'string'],
                    'class' => ['required', 'array'],
                    'campuses.*.id' => ['nullable', 'integer'],
                    'campuses.*.name' => ['nullable'],
                    'campuses.*.semester' => ['required'],
                    'campuses.*.grading' => ['required'],
                    'campuses.*.startDate' => ['nullable', 'date'],
                    'campuses.*.endDate' => ['nullable', 'date'],
                    'campuses.*.agency' => ['required'],
                    'campuses.*.street' => ['nullable', 'string'],
                    'campuses.*.address' => ['required'],
                    'campuses.*.main' => ['boolean'],
                ];
                break;
        }
    }

    public function messages()
    {
        return [
            'campuses.*.semester.required' => 'The academic term field is required.',
            'campuses.*.grading.required'  => 'The grading field is required.',
            'campuses.*.agency.required'   => 'The agency field is required.',
            'campuses.*.street.required'   => 'The street field is required.',
            'campuses.*.address.required'  => 'The address field is required.',
            'campuses.*.main.boolean'      => 'The main field must be true or false.',
        ];
    }
}
