<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Rappasoft\LaravelAuthenticationLog\Models\AuthenticationLog as LaravelAuthenticationLogModel;

/**
 * @property int $id
 * @property string $authenticatable_type
 * @property string $authenticatable_id
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property \Illuminate\Support\Carbon|null $login_at
 * @property bool $login_successful
 * @property \Illuminate\Support\Carbon|null $logout_at
 * @property bool $cleared_by_user
 * @property array<array-key, mixed>|null $location
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $authenticatable
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthenticationLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthenticationLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthenticationLog query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthenticationLog whereAuthenticatableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthenticationLog whereAuthenticatableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthenticationLog whereClearedByUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthenticationLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthenticationLog whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthenticationLog whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthenticationLog whereLoginAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthenticationLog whereLoginSuccessful($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthenticationLog whereLogoutAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthenticationLog whereUserAgent($value)
 *
 * @mixin \Eloquent
 */
class AuthenticationLog extends LaravelAuthenticationLogModel
{
    // use HasUuids;
}
