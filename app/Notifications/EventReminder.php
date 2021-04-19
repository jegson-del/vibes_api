<?php

namespace App\Notifications;

use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EventReminder extends Notification// implements ShouldQueue
{
//    use Queueable;

    private User $user;
    private string $subject;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user, string $subject)
    {
        $this->user = $user;
        $this->subject = $subject;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage())
                    ->subject($this->subject)
                    ->from(config('mail.from.address'), config('app.name'))
                    ->greeting('Hey ' . $this->user['username'])
                    ->line('Dont forget your event (' . $this->user['event_name'] . ')')
                    ->line('Starting ' . Carbon::parse($this->user['starts'])->diffForHumans())
                    ->line('When you get to the venue, show your tickets at the door.
                            Your ticket can be found in the profile section of the app');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
