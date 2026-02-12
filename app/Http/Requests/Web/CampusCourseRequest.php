<?php

namespace App\Http\Requests\Web;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CampusCourseRequest extends FormRequest
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
                    'campusId' => ['nullable'],
                    'course' => ['required', 'array'],
                    'course.id' => [
                        'integer',
                        Rule::unique('school_campus_courses', 'course_id')
                            ->where('campus_id', $this->campusId)
                            ->where('is_delete', 'false')
                            ->ignore($this->id),
                    ],
                    'years'            => ['required'],
                    // 'subjects.*.id'    => ['nullable'],
                    // 'subjects.*.name'  => ['required'],
                    // 'subjects.*.code'  => ['required'],
                    // 'subjects.*.class' => ['required'],
                    // 'subjects.*.unit'  => ['required']
                ];
                break;
        }
    }


    public function attributes()
    {
        return [
            'course.id' => 'course',
            'course' => 'program'
        ];
    }
}
