<?php

namespace App\Imports;

use App\Models\ListPrograms;
use App\Models\ListReferences;
use App\Models\ListStatuses;
use App\Models\Scholars;
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
        Log::info('Import row', ['row' => $row->getRowIndex(), 'data' => $data]);

        DB::transaction(function () use ($data, $row) {

            // $scholarshipTypeId = ListReferences::where('name', trim($data['scholarship_type']))
            //     ->where('is_active', true)
            //     ->where('is_delete', false)
            //     ->value('id');

            // $programId = ListPrograms::where('name', trim($data['scholarship_subprogram']))
            //     ->where('is_active', true)
            //     ->where('is_delete', false)
            //     ->value('id');

            // $categoryId = ListReferences::where('name', trim($data['scholarship_program']))
            //     ->where('is_active', true)
            //     ->where('is_delete', false)
            //     ->value('id');

            // $statusId = ListStatuses::where('name', trim($data['status']))
            //     ->where('is_active', true)
            //     ->where('is_delete', false)
            //     ->value('id');


            // Scholars::create([
            //     'spas_no'     => trim($data['spas_no']),
            //     'type_id'     => $scholarshipTypeId,
            //     'program_id'  => $programId,
            //     'category_id' => $categoryId,
            //     'status_id'   => $statusId,
            //     'created_by'  => Auth::user()->profile->fullname,
            // ]);
        });
    }

    // public function model(array $row)
    // {

    // }



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
