<?php

namespace App\Jobs;

use App\Mail\BulkNotificationMail;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendBulkNotificationEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public User $user,
        public string $title,
        public string $body
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            if ($this->user->email) {
                Mail::to($this->user->email)->send(new BulkNotificationMail($this->title, $this->body));
            }
        } catch (\Exception $e) {
            Log::error("Failed to send bulk notification email to {$this->user->email}: ".$e->getMessage());
            // Bạn có thể ném lại exception để job được thử lại nếu cần
            // $this->fail($e);
        }
    }
}
