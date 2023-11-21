<?php

namespace App\Jobs;

use App\Mail\AuthMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendAuthEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(private string $email, private string $rememberToken)
    {

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if (config('app.env') === 'local') {
            Mail::to(config('app.master_email'))->send(new AuthMail($this->email, $this->rememberToken));
        } else {
            Mail::to($this->email)->send(new AuthMail($this->email, $this->rememberToken));
        }
    }
}
