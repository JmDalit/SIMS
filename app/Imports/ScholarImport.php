<?php

namespace App\Imports;

use App\Models\ListCourse;
use App\Models\ListPrograms;
use App\Models\ListReferences;
use App\Models\ListStatuses;
use App\Models\LocationBarangays;
use App\Models\LocationCity;
use App\Models\LocationProvinces;
use App\Models\LocationRegions;
use App\Models\ScholarProfiles;
use App\Models\Scholars;
use App\Models\SchoolCampusCourses;
use App\Models\SchoolCampuses;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ScholarImport implements OnEachRow, WithHeadingRow, WithStartRow, SkipsEmptyRows, WithMultipleSheets
{
    use Importable;
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function headingRow(): int
    {
        return 1;
    }
    public function startRow(): int
    {
        return 2;
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

        DB::transaction(function () use ($data, $row) {

            $scholars = Scholars::create([
                'spas_no'     => trim($data['spas_no']),
                'type_id'     =>  ListReferences::where('name', trim($data['scholarship_type']))->value('id'),
                'program_id'  => ListPrograms::where('name', trim($data['scholarship_subprogram']))->value('id'),
                'category_id' =>  ListReferences::where('name', trim($data['scholarship_program']))->value('id'),
                'status_id'   => ListStatuses::where('name', trim($data['status']))->value('id'),
                'created_by'  => Auth::user()->profile->fullname,
            ]);

            $scholars->profile()->create([
                'fname' => $data['firstname'],
                'lname' => $data['lastname'],
                'mname' => $data['middlename'],
                'suffix' => $data['suffix'],
                'contact_no' => $data['contact'],
                'birthdate' => Date::excelToDateTimeObject($data['birthdate'])->format('Y-m-d'),
                'birthplace' => $data['birth_place'],
                'email' => $data['email'],
                'sex' => $data['sex'],
                'religion' => $data['religion'],
                'civil_status' => $data['civil_status']
            ]);

            $scholars->parent()->create([
                'fname' => $data['parent_fullname'],
                'id_no' => $data['id_no'],
                'id_date' => Date::excelToDateTimeObject($data['id_date'])->format('Y-m-d'),
                'id_place' => $data['id_place'],
                'companion' => $data['companion']
            ]);

            // $sliceName = explode(',', $data['barangay_municipality_province_region']);

            $scholars->address()->create([
                'address' => $data['address'],
                'barangay_code'   => LocationBarangays::where('name', trim($data['barangay']))->value('code'),
                'municipality_code' => LocationCity::where('name', trim($data['municipality']))->value('code'),
                'province_code'   => LocationProvinces::where('name', trim($data['province']))->value('code'),
                'region_code'     => LocationRegions::where('name', trim($data['region']))->value('code'),
            ]);


            $scholars->schoolInfo()->create([
                'campus_id' => SchoolCampuses::where('generated_name', $data['school'])->value('id'),
                'campus_course_id' => SchoolCampusCourses::whereHas('course', function ($q) use ($data) {
                    $q->where('name', $data['course']);
                })->value('id'),
                'award_year' => $data['year_award']
            ]);



            DB::commit();
        });
    }

    public function rules() {}
}
 // '*.fname' => ['required'],
            // '*.mname' => ['required'],
            // '*.lname' => ['required'],
            // '*.suffix' => ['required'],
            // '*.sex' => ['required', 'in:F,M'],
            // '*.email' => ['required', 'email'],
            // '*.contact' => ['required'],
            // '*.birthdate' => ['required'],
            // '*.birth_place' => ['required'],
            // '*.religion' => ['required'],
            // '*.civil_status' => ['required'],
            // '*.address' => ['required'],
            // '*.barangay' => ['required'],
            // '*.municipality' => ['required'],
            // '*.province' => ['required'],
            // '*.region' => ['required'],
            // '*.parent_fullname' => ['required'],
            // '*.id_no' => ['required'],
            // '*.id_place' => ['required'],
            // '*.id_date' => ['required'],
            // '*.companion' => ['required'],
            // '*.year_award' => ['required'],
            // '*.course' => ['required'],
            // '*.school' => ['required'],
            // '*.campus' => ['required'],
