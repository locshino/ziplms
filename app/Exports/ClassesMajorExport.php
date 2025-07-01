<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\ClassesMajor;
class ClassesMajorExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return ClassesMajor::select('id', 'organization_id','name', 'code', 'description', 'parent_id')->get();
    }

}
