<?php

namespace App\Models;

use Illuminate\Database\Eloquent\MassPrunable;
use Illuminate\Database\Eloquent\Model as EloquentModel;

class OTPLogin extends EloquentModel
{
    use MassPrunable;

    protected $table;

    public function __construct(array $attributes = [])
    {
        $this->table = config('filament-otp-login.table_name') ?? 'filament_otp_login_codes';
        parent::__construct($attributes);
    }

    public function prunable()
    {
        return static::where('expires_at', '<', now());
    }
}
