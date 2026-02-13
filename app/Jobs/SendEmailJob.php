<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\ForgotPassword;
use Illuminate\Support\Facades\Mail;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected $details,)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $details = $this->details;

        if ($details['mailType'] == "forgotPassword") {
            Mail::to($details['users'])->send(new ForgotPassword($details['mailData']));
        }
    }
}
