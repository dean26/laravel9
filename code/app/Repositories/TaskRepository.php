<?php

namespace App\Repositories;

use App\Models\Task;

class TaskRepository implements \App\Interfaces\RepositoryInterface
{
    public function getAll()
    {
        return Task::all();
    }

    public function getById(int $id)
    {
        return Task::findOrFail($id);
    }

    public function delete(int $id)
    {
        Task::destroy($id);
    }

    public function deleteMultiple(array $ids)
    {
        Task::destroy($ids);
    }

    public function create(array $data)
    {
        return Task::create($data);
    }

    public function update($id, array $data)
    {
        $task = $this->getById($id);
        $task->update($data);

        return $task;
    }
}
