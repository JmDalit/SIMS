<?php

namespace App\Http\Requests\Web;

use Illuminate\Foundation\Http\FormRequest;

class SchoolCampusSemesterRequest extends FormRequest
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
            'campusId' => ['required', 'exists:school_campuses,id'],
            'semester' => ['array'],
            'semester.*.id' => ['nullable'],
            'semester.*.semesterId' => ['required'],
            'semester.*.startDate' => ['required', 'date'],
            'semester.*.endDate' => ['required', 'date'],
            'semester.*.submissionDate' => ['required', 'date'],
            'semester.*.startDateFormatted' => ['nullable', 'string'],
            'semester.*.endDateFormatted' => ['nullable', 'string'],
            'semester.*.submissionDateFormatted' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'semesters.*.startDate.required' => 'Each semester must have a start date.',
            'semesters.*.endDate.required' => 'Each semester must have an end date.',
            'semesters.*.submissionDate.required' => 'Each semester must have an suubmission date.',
        ];
    }

    public function attributes(): array
    {
        return [
            'campusId' => 'campus',
            'semester.*.semesterId' => 'semester',
            'semester.*.startDate' =>  'start date',
            'semester.*.endDate' => 'end date',
            'semester.*.submissionDate' => 'submission date',
        ];
    }
}
