<?php

namespace App\Jobs;
// namespace App\Mail;

use App\Mail\CompanyCreatedMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
class SendCompanyCreatedEmail implements ShouldQueue
{
    use Dispatchable, Queueable,SerializesModels;

    /**
     * Create a new job instance.
     */
    public $company;
    public function __construct($company)
    {
        $this->company=$company;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to(env('MAIL_FROM_ADDRESS'))->send(new CompanyCreatedMail(
            $this->company
        ));
    }
}
