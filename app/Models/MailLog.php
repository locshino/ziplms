<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Tapp\FilamentMailLog\Models\MailLog as FilamentMailLogModel;

/**
 * @property string $id
 * @property string|null $from
 * @property string|null $to
 * @property string|null $cc
 * @property string|null $bcc
 * @property string $subject
 * @property string $body
 * @property string|null $headers
 * @property string|null $attachments
 * @property string|null $message_id
 * @property string|null $status
 * @property array<array-key, mixed>|null $data
 * @property string|null $opened
 * @property string|null $delivered
 * @property string|null $complaint
 * @property string|null $bounced
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $data_json
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MailLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MailLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MailLog query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MailLog whereAttachments($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MailLog whereBcc($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MailLog whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MailLog whereBounced($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MailLog whereCc($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MailLog whereComplaint($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MailLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MailLog whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MailLog whereDelivered($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MailLog whereFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MailLog whereHeaders($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MailLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MailLog whereMessageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MailLog whereOpened($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MailLog whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MailLog whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MailLog whereTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MailLog whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class MailLog extends FilamentMailLogModel
{
    use HasUuids;
}
