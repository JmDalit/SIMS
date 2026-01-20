<?php

namespace App\Http\Requests\Web;

use Illuminate\Foundation\Http\FormRequest;

class SchoolCampusCurriculumRequest extends FormRequest
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
                    'multi'                             => ['nullable', 'array'],
                    'multi.*.id'                        => ['nullable'],
                    'multi.*.campus_course_id'          => ['nullable'],
                    'multi.*.semesterTypeId'            => ['nullable'],
                    'multi.*.yearLevel'                 => ['required'],
                    'multi.*.yearNumber'                => ['nullable'],
                    'multi.*.subjects'                  => ['required', 'array'],
                    'multi.*.subjects.*.id'             => ['nullable'],
                    'multi.*.subjects.*.name'           => ['required'],
                    'multi.*.subjects.*.semester_array' => ['nullable', 'array'],
                    'multi.*.subjects.*.class_array'    => ['required', 'array'],
                    'multi.*.subjects.*.subjectCode'    => ['required'],
                    'multi.*.subjects.*.unit'           => ['required'],
                    'multi.*.subjects.*.year'           => ['nullable']

                ];
                break;
        }
    }

    public function messages()
    {
        return [
            'multi.*.subjects'                 => 'Please make sure to include subjects before proceeding.',
            'multi.*.subjects.*.name'          => 'Please provide the subject name.',
            'multi.*.subjects.*.class_array'   => 'Please select a subject class. ',
            'multi.*.subjects.*.subjectCode'   => 'Please enter the subject code.',
            'multi.*.subjects.*.unit'          => 'Please specify the number of units.',
        ];
    }
}
