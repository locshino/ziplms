<?php

namespace App\Models;

use Illuminate\Database\Eloquent\MassPrunable;
use Illuminate\Database\Eloquent\Model as EloquentModel;

/**
 * @property int $id
 * @property string $code
 * @property string $email
 * @property string $expires_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OTPLogin newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OTPLogin newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OTPLogin query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OTPLogin whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OTPLogin whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OTPLogin whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OTPLogin whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OTPLogin whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OTPLogin whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
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
