<?php

namespace App\Observers;

use App\Models\User;
use App\Models\Testing;
use Illuminate\Support\Facades\Notification;
use App\Notifications\TestingNotification;
use App\Events\TestingUpdateEvent;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;

class TestingObserver implements ShouldHandleEventsAfterCommit
{
    /**
     * Handle the Testing "created" event.
     */
    public function created(Testing $testing): void
    {
        //
        // dd(auth()->user()->currentTeam->id);
        
        // Send Notifications
        $team_id = auth()->user()->currentTeam->id ?? false;
        if($team_id) {

            // Receiver
            $users = User::where('current_team_id', $team_id)->get();
            $user_count = $users->count();

            // Laravel Broadcast Notification
            foreach($users as $user) {
                // Notification -> sendNow | send
                if($user_count > 100) { // queue
                    Notification::send($user, new TestingNotification($testing));
                }
                else
                Notification::sendNow($user, new TestingNotification($testing));
            }

            // Notifications Megaphone
            $url = url('/api/testing/'.$testing->id);
            $message = [
                'title' => 'New Data',
                'body' => sprintf('Data was created: %s Created at: %s', $testing->name, $testing->created_at),
                'url' => $url,
                'link' => 'Read More...',
            ];
            $notification = new \MBarlow\Megaphone\Types\Important(
                $message['title'], $message['body'], $message['url'], $message['link']
            );
            
            foreach($users as $user) {
                $user->notify($notification);
            }
        }

    }

    /**
     * Handle the Testing "updated" event.
     */
    public function updated(Testing $testing): void
    {
        //

        // Broadcast Event and Send Notifications
        $team_id = auth()->user()->currentTeam->id ?? false;
        if($team_id) {

            // Receiver
            $users = User::where('current_team_id', $team_id)->get();
            $users_count = $users->count();

            // Broadcast Event
            $testing->team_id = $team_id;
            $testing->users_count = $users_count;
            TestingUpdateEvent::dispatch($testing);

            // Notifications Megaphone
            $url = url('/api/testing/'.$testing->id);
            $message = [
                'title' => 'Updated Data',
                'body' => sprintf('Data was changed: %s Updated at: %s', $testing->name, $testing->updated_at),
                'url' => $url,
                'link' => 'Read More...',
            ];
            $notification = new \MBarlow\Megaphone\Types\Important(
                $message['title'], $message['body'], $message['url'], $message['link']
            );
            
            foreach($users as $user) {
                $user->notify($notification);
            }
        }
    }

    /**
     * Handle the Testing "deleted" event.
     */
    public function deleted(Testing $testing): void
    {
        //
    }

    /**
     * Handle the Testing "restored" event.
     */
    public function restored(Testing $testing): void
    {
        //
    }

    /**
     * Handle the Testing "force deleted" event.
     */
    public function forceDeleted(Testing $testing): void
    {
        //
    }
}
