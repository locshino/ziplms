<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use ZPMLabs\FilamentApiDocsBuilder\Models\ApiDocs as BaseApiDocs;

/**
 * @property string $id
 * @property string $title
 * @property string $slug
 * @property string|null $description
 * @property string $version
 * @property array<array-key, mixed>|null $data
 * @property string|null $user_id
 * @property string|null $tenant_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiDocs newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiDocs newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiDocs query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiDocs whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiDocs whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiDocs whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiDocs whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiDocs whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiDocs whereTenantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiDocs whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiDocs whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiDocs whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiDocs whereVersion($value)
 * @mixin \Eloquent
 */
class ApiDocs extends BaseApiDocs
{
    use HasUuids;
}
