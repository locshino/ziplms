<?php

namespace App\Models;

use App\States\Progress\ProgressStatus;
use Spatie\ModelStates\HasStates;

/**
 * @property string $id
 * @property string|null $organization_id
 * @property string $uploaded_by_user_id
 * @property string $original_file_name
 * @property string $storage_path
 * @property int $total_rows
 * @property int $processed_rows
 * @property int $successful_imports
 * @property int $failed_imports
 * @property array<array-key, mixed>|null $error_log
 * @property string|null $error_report_path
 * @property ProgressStatus $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Organization|null $organization
 * @property-read \App\Models\User $uploader
 *
 * @method static \Database\Factories\BatchFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Batch newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Batch newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Batch onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Batch orWhereNotState(string $column, $states)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Batch orWhereState(string $column, $states)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Batch query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Batch whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Batch whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Batch whereErrorLog($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Batch whereErrorReportPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Batch whereFailedImports($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Batch whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Batch whereNotState(string $column, $states)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Batch whereOrganizationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Batch whereOriginalFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Batch whereProcessedRows($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Batch whereState(string $column, $states)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Batch whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Batch whereStoragePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Batch whereSuccessfulImports($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Batch whereTotalRows($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Batch whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Batch whereUploadedByUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Batch withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Batch withoutTrashed()
 *
 * @mixin \Eloquent
 */
class Batch extends Base\Model
{
    use HasStates;

    protected $casts = [
        'error_log' => 'json',
        'status' => ProgressStatus::class,
    ];

    protected $fillable = [
        'organization_id',
        'uploaded_by_user_id',
        'original_file_name',
        'storage_path',
        'total_rows',
        'processed_rows',
        'successful_imports',
        'failed_imports',
        'error_log',
        'error_report_path',
        'status',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by_user_id');
    }
}
