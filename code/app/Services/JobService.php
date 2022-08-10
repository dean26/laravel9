<?php

namespace App\Services;

use App\Dictionaries\UserRolesDictionary;
use App\Models\Job;
use App\Traits\ServiceTrait;
use Illuminate\Pagination\LengthAwarePaginator;
use ReflectionClass;

class JobService
{
    use ServiceTrait;

    public function __construct()
    {
        return $this;
    }

    public function paginateByArrayData(array $data): LengthAwarePaginator
    {
        $query = Job::query()->select('*')->with(['item', 'customer', 'user']);

        //no director can watch only jobs added for them
        if (! auth()->user()->hasRole(UserRolesDictionary::ROLE_DIRECTOR)) {
            $query->where('user_id', auth()->user()->id);
        } else {
            if (isset($data['user_id'])) {
                $query->where('user_id', $data['user_id']);
            }
        }

        if (isset($data['customer_id'])) {
            $query->where('customer_id', $data['customer_id']);
        }
        if (isset($data['status'])) {
            $query->where('status', $data['status']);
        }
        if (isset($data['expected_installation_date_start'])) {
            $query->where('expected_installation_date', '>=', $data['expected_installation_date_start']);
        }
        if (isset($data['expected_installation_date_end'])) {
            $query->where('expected_installation_date', '<=', $data['expected_installation_date_end']);
        }

        (isset($data['sort_by'])) ? $query->orderBy($data['sort_by'], (array_key_exists('order_by', $data) ? $data['order_by'] : 'desc')) : $query->orderBy('expected_installation_date', 'desc');

        return $query->paginate($this->getPerPage($data));
    }

    public static function getPossibleStatuses(): array
    {
        $rules = [];

        $reflector = new ReflectionClass('App\Dictionaries\JobTaskStatusDictionary');
        foreach ($reflector->getConstants() as $k => $option) {
            if (! in_array($option, $rules)) {
                $rules[] = $option;
            }
        }

        return $rules;
    }

    public static function getPossibleFlags(): array
    {
        //logged user
        $user = auth()->user();

        $rules = [];

        if (! $user || $user->hasRole(UserRolesDictionary::ROLE_DIRECTOR)) {
            $reflector = new ReflectionClass('App\Dictionaries\DirectorFlagDictionary');
            foreach ($reflector->getConstants() as $k => $option) {
                if (! in_array($option, $rules)) {
                    $rules[] = $option;
                }
            }
        }

        if (! $user || $user->hasRole(UserRolesDictionary::ROLE_DIRECTOR) || $user->hasRole(UserRolesDictionary::ROLE_INSTALLER)) {
            $reflector = new ReflectionClass('App\Dictionaries\InstallerFlagDictionary');
            foreach ($reflector->getConstants() as $option) {
                if (! in_array($option, $rules)) {
                    $rules[] = $option;
                }
            }
        }

        if (! $user || $user->hasRole(UserRolesDictionary::ROLE_DIRECTOR) || $user->hasRole(UserRolesDictionary::ROLE_WAREHOUSE)) {
            $reflector = new ReflectionClass('App\Dictionaries\WarehouseFlagDictionary');
            foreach ($reflector->getConstants() as $option) {
                if (! in_array($option, $rules)) {
                    $rules[] = $option;
                }
            }
        }

        return $rules;
    }
}
