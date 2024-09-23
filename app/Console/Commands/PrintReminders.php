<?php

namespace App\Console\Commands;

// use App\Events\NewNotification;
use App\Events\TaskOverdue;
use App\Models\Reminder;
use Carbon\Carbon;
use Illuminate\Console\Command;

class PrintReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:print-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Print reminders that are due';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();
        $reminders = Reminder::with('task')
            ->where('status', '!=', 'sent')
            ->whereHas('task', function ($query) {
                $query->whereNotIn('status', ['completed', 'archived']);
            })
            ->where('reminder_time', '<', $now)
            ->get();

        foreach ($reminders as $reminder) {
            event(new TaskOverdue($reminder, $reminder->user));
        }

        // Update the status of the reminders that were sent
        Reminder::whereIn('id', $reminders->pluck('id'))->update(['status' => 'sent']);

        return 0;
    }
}
