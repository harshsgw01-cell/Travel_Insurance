<?php

namespace App\Notifications;

use App\Models\Policy;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PolicyIssuedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public readonly Policy $policy) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $policy = $this->policy;

        return (new MailMessage)
            ->subject("Your Travel Insurance Policy #{$policy->policy_number} is Active")
            ->greeting("Hello {$notifiable->name},")
            ->line("Your travel insurance policy has been issued successfully.")
            ->line("**Policy Number:** {$policy->policy_number}")
            ->line("**Coverage Period:** {$policy->start_date->format('d M Y')} to {$policy->end_date->format('d M Y')}")
            ->line("**Destination:** {$policy->destination_country}")
            ->line("**Total Amount:** ₹" . number_format($policy->total_amount, 2))
            ->action('View Policy', url("/policies/{$policy->id}"))
            ->line('Thank you for choosing Travel Insurance. Have a safe trip!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'policy_id'     => $this->policy->id,
            'policy_number' => $this->policy->policy_number,
            'status'        => 'issued',
        ];
    }
}
