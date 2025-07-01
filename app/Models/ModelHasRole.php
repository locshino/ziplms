<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModelHasRole extends Model
{
     protected $table = 'model_has_roles';


    protected $fillable = [
        'role_id',
        'model_type',
        'model_id',
    ];

    // Liên kết tới role
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    // Liên kết tới user (chỉ khi model_type là App\Models\User)
    public function user()
    {
        return $this->belongsTo(User::class, 'model_id');
    }
}
