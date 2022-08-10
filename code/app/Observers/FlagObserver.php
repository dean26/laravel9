<?php

namespace App\Observers;

use App\Models\Flag;

class FlagObserver
{
    /**
     * Handle the Flag "created" event.
     *
     * @param  \App\Models\Flag  $flag
     * @return void
     */
    public function created(Flag $flag)
    {
        //add log
        $user = auth()->user();

        $flag->owner->logs()->create(
            [
                'user_id' => $user->id,
                'content' => __('messages.flag.created', [
                    'name' => (string) $user,
                    'content' => $flag->content,
                ]
                ),
            ]
        );
    }

    /**
     * Handle the Flag "deleted" event.
     *
     * @param  \App\Models\Flag  $flag
     * @return void
     */
    public function deleted(Flag $flag)
    {
        //add log
        $user = auth()->user();
        $flag->owner->logs()->create(
            [
                'user_id' => $user->id,
                'content' => __('messages.flag.deleted', [
                    'name' => (string) $user,
                    'content' => $flag->content,
                ]
                ),
            ]
        );
    }
}
