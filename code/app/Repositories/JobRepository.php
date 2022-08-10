<?php

namespace App\Repositories;

use App\Interfaces\RepositoryInterface;
use App\Models\Job;

class JobRepository implements RepositoryInterface
{
    public function getAll()
    {
        return Job::all();
    }

    public function getById(int $id)
    {
        return Job::findOrFail($id);
    }

    public function delete(int $id)
    {
        Job::destroy($id);
    }

    public function deleteMultiple(array $ids)
    {
        Job::destroy($ids);
    }

    public function create(array $data)
    {
        return Job::create($data);
    }

    public function update($id, array $data)
    {
        $job = $this->getById($id);
        $job->update($data);

        return $job;
    }
}
