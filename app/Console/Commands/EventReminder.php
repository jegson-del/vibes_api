<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\EventReminder as EventReminderNotification;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;

class EventReminder extends Command
{
    protected $signature = 'event:reminder
    {--daily=0 : Checks if it is a daily reminder or other days}';

    protected $description = 'Sends reminder to  a user';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $daily = (int) $this->option('daily');

        $subject = 'You have an event coming up soon';

        if ($daily) {
            $subject = 'You have an event today';
        }

        User::join('payments', 'users.id', 'payments.user_id')
            ->join('events', 'payments.event_id', 'events.id')
            ->whereDate('starts', now()->format('Y-m-d'))
            ->select(
                'users.id as id',
                'users.name as username',
                'users.email',
                'events.name as event_name',
                'events.starts as starts',
            )
            ->chunk(25, function (Collection $data) use ($subject) {
                foreach ($data as $user) {
                    $user->notify(new EventReminderNotification($user, $subject));
                }
            });
    }
}
