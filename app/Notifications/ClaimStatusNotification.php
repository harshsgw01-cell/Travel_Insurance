<?php

namespace App\Notifications;

use App\Models\Claim;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ClaimStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public readonly Claim $claim) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $claim  = $this->claim;
        $status = ucwords(str_replace('_', ' ', $claim->status->value));

        $mail = (new MailMessage)
            ->subject("Claim #{$claim->claim_number} Status Update: {$status}")
            ->greeting("Hello {$notifiable->name},")
            ->line("Your claim status has been updated.")
            ->line("**Claim Number:** {$claim->claim_number}")
            ->line("**Status:** {$status}")
            ->line("**Claim Type:** {$claim->claim_type}")
            ->line("**Amount Claimed:** ₹" . number_format($claim->amount_claimed, 2));

        if ($claim->amount_approved) {
            $mail->line("**Amount Approved:** ₹" . number_format($claim->amount_approved, 2));
        }

        if ($claim->remarks) {
            $mail->line("**Remarks:** {$claim->remarks}");
        }

        return $mail->action('View Claim', url("/claims/{$claim->id}"))
            ->line('Thank you for using Travel Insurance.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'claim_id'     => $this->claim->id,
            'claim_number' => $this->claim->claim_number,
            'status'       => $this->claim->status->value,
        ];
    }
}
