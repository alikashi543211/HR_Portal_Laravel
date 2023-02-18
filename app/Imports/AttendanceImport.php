<?php

namespace App\Imports;

use App\Attendance;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AttendanceImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    public function model(array $row)
    {
        dd($row);
        return new Attendance([
            'category_id' => 2,
            'name' => $row['Business_Name'],
            'phone_no' => $row['Telephone_Number'],
            'address' => $row['Address'] . ($row['City'] ? ', ' . $row['City'] : ''),
            'email' => explode(',', $row['Email'])[0],
            'postcode' => $row['Postal_Code'],
            'search_field' => $row['Business_Name'] . ' ' . $row['City'],
            'region' => $row['country'],
            'town' => $row['City'],
            'lat' => $row['Latitude'],
            'lng' => $row['Longitude'],
            'facebook' => $row['Facebook'],
            'twitter' => $row['Twitter'],
            'website' => $row['Website_URL'],
            'description' => '<p>' . $row['About_us'] . '</p>'
        ]);
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function batchSize(): int
    {
        return 5000;
    }
}
