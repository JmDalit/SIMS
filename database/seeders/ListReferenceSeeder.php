<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ListReferenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('list_references')->insert([
            [
                'name' => 'n/a',
                'classification' => 'n/a',
                'type' => 'n/a',
                'others' => 'n/a',
            ],
            [
                'name' => 'SUC',
                'classification' => 'Class',
                'type' => 'School',
                'others' => 'n/a',
            ],
            [
                'name' => 'LUC',
                'classification' => 'Class',
                'type' => 'School',
                'others' => 'n/a',
            ],
            [
                'name' => 'Private',
                'classification' => 'Class',
                'type' => 'School',
                'others' => 'n/a',
            ],
            [
                'name' => 'Semester',
                'classification' => 'Term Type',
                'type' => 'School',
                'others' => 'n/a',
            ],
            [
                'name' => 'Trimester',
                'classification' => 'Term Type',
                'type' => 'School',
                'others' => 'n/a',
            ],
            [
                'name' => 'Quarter Term',
                'classification' => 'Term Type',
                'type' => 'School',
                'others' => 'n/a',
            ],
            [
                'name' => 'Transmutation',
                'classification' => 'Grading System',
                'type' => 'School',
                'others' => 'n/a',
            ],
            [
                'name' => 'Percent Grading',
                'classification' => 'Grading System',
                'type' => 'School',
                'others' => 'n/a',
            ],
            [
                'name' => 'Per Grade',
                'classification' => 'Grading System',
                'type' => 'School',
                'others' => 'n/a',
            ],
            [
                'name' => '1st',
                'classification' => 'Level',
                'type' => 'School',
                'others' => 'First Year',
            ],
            [
                'name' => '2nd',
                'classification' => 'Level',
                'type' => 'School',
                'others' => 'Second Year',
            ],
            [
                'name' => '3rd',
                'classification' => 'Level',
                'type' => 'School',
                'others' => 'Third Year',
            ],
            [
                'name' => '4th',
                'classification' => 'Level',
                'type' => 'School',
                'others' => 'Fourth Year',
            ],
            [
                'name' => '5th',
                'classification' => 'Level',
                'type' => 'School',
                'others' => 'Fifth Year',
            ],
            [
                'name' => 'Summa Cud Laude',
                'classification' => 'Award',
                'type' => 'School',
                'others' => 'n/a',
            ],
            [
                'name' => 'Magna Cum Laude',
                'classification' => 'Award',
                'type' => 'School',
                'others' => 'n/a',
            ],
            [
                'name' => 'Cum Laude',
                'classification' => 'Award',
                'type' => 'School',
                'others' => 'n/a',
            ],
            [
                'name' => 'Honorable Mention',
                'classification' => 'Award',
                'type' => 'School',
                'others' => 'n/a',
            ],
            [
                'name' => 'Academic Distinction',
                'classification' => 'Award',
                'type' => 'School',
                'others' => 'n/a',
            ],
            [
                'name' => 'Center of Excellence',
                'classification' => 'Certification',
                'type' => 'Course',
                'others' => 'n/a',
            ],
            [
                'name' => 'Center of Development',
                'classification' => 'Certification',
                'type' => 'Course',
                'others' => 'n/a',
            ],
            [
                'name' => 'FAAP Level III',
                'classification' => 'Certification',
                'type' => 'Course',
                'others' => 'n/a',
            ],
            [
                'name' => 'FAAP Level IV',
                'classification' => 'Certification',
                'type' => 'Course',
                'others' => 'n/a',
            ],
            [
                'name' => 'Full',
                'classification' => 'Category',
                'type' => 'Category',
                'others' => 'n/a',
            ],
            [
                'name' => 'Special',
                'classification' => 'Category',
                'type' => 'Category',
                'others' => 'n/a',
            ],
            [
                'name' => 'Partial',
                'classification' => 'Category',
                'type' => 'Category',
                'others' => 'n/a',
            ],
            [
                'name' => 'Undergraduate',
                'classification' => 'Type',
                'type' => 'Category',
                'others' => 'n/a',
            ],
            [
                'name' => 'JLSS',
                'classification' => 'Type',
                'type' => 'Category',
                'others' => 'n/a',
            ],
            [
                'name' => 'Personal Services',
                'classification' => 'Expenditure',
                'type' => 'Category',
                'others' => 'n/a',
            ],
            [
                'name' => 'Maintenance And Other Operating Expenses',
                'classification' => 'Expenditure',
                'type' => 'Category',
                'others' => 'n/a',
            ],
            [
                'name' => 'Special Purposes',
                'classification' => 'Expenditure',
                'type' => 'Category',
                'others' => 'n/a',
            ],
            [
                'name' => 'Capital Outlay',
                'classification' => 'Expenditure',
                'type' => 'Category',
                'others' => 'n/a',
            ],
            [
                'name' => 'First Semester',
                'classification' => 'Semester',
                'type' => 'Term ',
                'others' => 'regular',
            ],
            [
                'name' => 'Second Semester',
                'classification' => 'Semester',
                'type' => 'Term ',
                'others' => 'regular',
            ],
            [
                'name' => 'Summer Class',
                'classification' => 'Semester',
                'type' => 'Term ',
                'others' => 'summer',
            ],
            [
                'name' => 'First Quarter',
                'classification' => 'Trimester',
                'type' => 'Term ',
                'others' => 'regular',
            ],
            [
                'name' => 'Second Quarter',
                'classification' => 'Trimester',
                'type' => 'Term ',
                'others' => 'regular',
            ],
            [
                'name' => 'Third Quarter',
                'classification' => 'Trimester',
                'type' => 'Term ',
                'others' => 'regular',
            ],
            [
                'name' => 'Summer',
                'classification' => 'Trimester',
                'type' => 'Term ',
                'others' => 'summer',
            ],
            [
                'name' => 'First Quarter',
                'classification' => 'Quarter Term',
                'type' => 'Term ',
                'others' => 'regular',
            ],
            [
                'name' => 'Second Quarter',
                'classification' => 'Quarter Term',
                'type' => 'Term ',
                'others' => 'regular',
            ],
            [
                'name' => 'Third Quarter',
                'classification' => 'Quarter Term',
                'type' => 'Term ',
                'others' => 'regular',
            ],
            [
                'name' => 'Fourth Quarter',
                'classification' => 'Quarter Term',
                'type' => 'Term ',
                'others' => 'regular',
            ],
            [
                'name' => 'RA 7687',
                'classification' => 'Scholarship',
                'type' => 'Category',
                'others' => 'n/a',
            ],
            [
                'name' => 'RA 10612',
                'classification' => 'Scholarship',
                'type' => 'Category',
                'others' => 'n/a',
            ],
            [
                'name' => 'MERIT',
                'classification' => 'Scholarship',
                'type' => 'Category',
                'others' => 'n/a',
            ],
            [
                'name' => 'Undergraduate',
                'classification' => 'Scholarship',
                'type' => 'Type',
                'others' => 'n/a',
            ],
            [
                'name' => 'JLSS',
                'classification' => 'Scholarship',
                'type' => 'Type',
                'others' => 'n/a',
            ],
            [
                'name' => 'Transferee',
                'classification' => 'Modify Type',
                'type' => 'n/a',
                'others' => 'n/a',
            ],
            [
                'name' => 'Shiftee',
                'classification' => 'Modify Type',
                'type' => 'n/a',
                'others' => 'n/a',
            ],
            [
                'name' => 'University-Based HS',
                'classification' => 'Class',
                'type' => 'School',
                'others' => 'n/a',
            ],
            [
                'name' => 'Academic',
                'classification' => 'Subject Class',
                'type' => 'Subject',
                'others' => 'n/a',
            ],
            [
                'name' => 'Non-Academic',
                'classification' => 'Subject Class',
                'type' => 'Subject',
                'others' => 'n/a',
            ],
        ]);
    }
}
