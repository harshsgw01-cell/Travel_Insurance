<?php

namespace App\Listeners;

use App\Events\PolicyIssued;
use App\Notifications\PolicyIssuedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendPolicyIssuedNotification implements ShouldQueue
{
    public function handle(PolicyIssued $event): void
    {
        $policy   = $event->policy->load('customer.user');
        $user     = $policy->customer?->user;

        if ($user) {
            $user->notify(new PolicyIssuedNotification($policy));
        }
    }
}
