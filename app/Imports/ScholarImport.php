<?php

namespace App\Imports;

use App\Models\Scholars;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ScholarImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Scholars([
            'fname' => $row['fname'],
            'mname' => $row['mname'],
            'lname' => $row['lname'],
            'suffix' => $row['suffix'],
            'birthdate' => $row['birthdate'],
            'contact_no' => $row['contact_no'],
            'email' => $row['email'],
            'sex' => $row['sex'],

        ]);
    }
}
