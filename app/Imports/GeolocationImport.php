<?php

namespace App\Imports;

use App\Models\geolocations;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Row;

class GeolocationImport implements ToCollection, WithHeadingRow, WithMultipleSheets, WithStartRow, WithChunkReading, WithBatchInserts
{
    /**
     * @param Collection $row
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
            'PSGC' => $this,
        ];
    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function chunkSize(): int
    {
        return 1000;
    }


    public function collection(Collection $rows)
    {
        $insert = [];

        foreach ($rows as $row) {
            $data = [
                'psgc_code' => trim($row['10_digit_psgc'] ?? ''),
                'name' => trim($row['name'] ?? ''),
                'type' => trim($row['geographic_level'] ?? ''),
                'old_name' => trim($row['old_names'] ?? ''),
            ];

            // ✅ validate row
            $validator = Validator::make($data, [
                'psgc_code' => ['required'],
                'name' => ['required'],
                'type' => ['nullable', 'in:Bgy,City,Mun,Prov,Reg,SubMun'],
                'old_name' => ['nullable'],
            ]);

            if ($validator->fails()) {
                // skip invalid row (or collect errors for reporting)
                continue;
            }

            $insert[] = $data;
        }

        if (!empty($insert)) {
            // ✅ batch insert/update
            DB::table('geolocations')->upsert(
                $insert,
                ['psgc_code'],        // unique key
                ['name', 'old_name', 'type'] // columns to update
            );
        }
    }
}
