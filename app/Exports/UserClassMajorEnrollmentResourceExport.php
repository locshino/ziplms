<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\UserClassMajorEnrollment;

class UserClassMajorEnrollmentResourceExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return \App\Models\UserClassMajorEnrollment::select(
            'id',
            'user_id',
            'class_major_id',
            'start_date',
            'end_date',
            'created_at',
            'updated_at'
        )->get();
    }
}
