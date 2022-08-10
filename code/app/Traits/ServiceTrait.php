<?php

namespace App\Traits;

trait ServiceTrait
{
    public function getPerPage(array $data): int
    {
        return $data['per_page'] ?? config('app.per_page');
    }
}
