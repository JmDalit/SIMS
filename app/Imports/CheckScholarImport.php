<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Row;

class CheckScholarImport implements OnEachRow, WithHeadingRow, WithMultipleSheets
{
    /**
     * @param Collection $row
     */
    public function headingRow(): int
    {
        return 1;
    }
    public function sheets(): array
    {
        return [
            'information' => $this,
        ];
    }

    public function onRow(Row $row)
    {
        $data = $row->toArray();

        Validator::make(
            $data,
            [
                'spas_no' => ['required', Rule::unique('scholars', 'spas_no')],
                'scholarship_type' => ['required', Rule::exists('list_references', 'name')->where(fn($query) => $query->where('is_active', true)->where('is_delete', false))],
                'scholarship_subprogram' => ['required', Rule::exists('list_programs', 'name')->where(fn($query) => $query->where('is_active', true)->where('is_delete', false))],
                'scholarship_program' => ['required', Rule::exists('list_references', 'name')->where(fn($query) => $query->where('is_active', true)->where('is_delete', false))],
                'status' => ['required'],
                'firstname' => ['required'],
                'lastname' => ['required'],
                'middlename' => ['nullable'],
                'suffix' => ['nullable'],
                'sex' => ['required', 'in:M,F'],
                'email' => ['required', 'email'],
                'contact' => ['required'],
                'birth_place' => ['required'],
                'religion' => ['required'],
                'civil_status' => ['required'],
                'address' => ['required'],
                'barangay_municipality_province_region' => ['required'],
                'parent_fullname' => ['required'],
                'id_no' => ['required'],
                'id_place' => ['required'],
                'id_date' => ['required'],
                'companion' => ['required'],
                'year_award' => ['required'],
                'course' => ['required', Rule::exists('school_campus_course',)],
                'school' => ['required', Rule::exists('school_campuses', 'generated_name')->where(fn($query) => $query->where('is_active', true)->where('is_delete', false))]
            ],
            [
                'spas_no.required' =>
                "Row {$row->getRowIndex()}: The SPAS No field is required.",
                'spas_no.unique' =>
                "Row {$row->getRowIndex()}: The SPAS No has already been taken.",
                'scholarship_type.required' =>
                "Row {$row->getRowIndex()}: The Scholarship Type field is required.",
                'scholarship_subprogram.required' =>
                "Row {$row->getRowIndex()}: The Scholarship Subprogram field is required.",
                'scholarship_program.required' =>
                "Row {$row->getRowIndex()}: The Scholarship Program field is required.",
                'status.required' =>
                "Row {$row->getRowIndex()}: The Status field is required.",
                'scholarship_type.exists' => "Row {$row->getRowIndex()}: Please contact admin support to update scholarship option.",
                'scholarship_subprogram.exists' => "Row {$row->getRowIndex()}: Please contact admin support to update subprogram option.",
                'scholarship_program.exists' => "Row {$row->getRowIndex()}: Please contact admin support to update program option.",
                'barangay_municipality_province_region.required' => "Row {$row->getRowIndex()}: Please contact admin support to update geolocation option.",
                'school.exists' => "Row {$row->getRowIndex()}: Please contact admin support to update school option.",
            ]
        )->validate();
    }
}
