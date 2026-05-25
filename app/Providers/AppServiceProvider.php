<?php

namespace App\Providers;

use App\Events\ClaimStatusUpdated;
use App\Events\PolicyIssued;
use App\Listeners\SendPolicyIssuedNotification;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Event::listen(PolicyIssued::class, SendPolicyIssuedNotification::class);
    }
}
