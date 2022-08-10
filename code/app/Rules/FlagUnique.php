<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Database\Eloquent\Model;

class FlagUnique implements Rule
{
    private Model $model;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(string $model_name, int $model_id)
    {
        switch ($model_name) {
            case 'Job': $this->model = \App\Models\Job::find($model_id); break;
            case 'Task': $this->model = \App\Models\Task::find($model_id); break;
        }
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (! $this->model || $this->model->flags()->where('content', $value)->count() > 0) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('messages.flag.validation');
    }
}
