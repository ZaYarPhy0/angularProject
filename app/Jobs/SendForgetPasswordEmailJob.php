<?php

namespace App\Jobs;

use App\Mail\ForgetPassword;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SendForgetPasswordEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;
    public $token;
    public $frontend_url;
    /**
     * Create a new job instance.
     */
    public function __construct($user, $token, $frontend_url)
    {
        $this->user=$user;
        $this->token=$token;
        $this->frontend_url=$frontend_url;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->user['email'])->send(new ForgetPassword($this->user, $this->token, $this->frontend_url));

    }
}
