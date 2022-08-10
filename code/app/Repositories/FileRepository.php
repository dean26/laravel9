<?php

namespace App\Repositories;

use App\Models\File;

class FileRepository implements \App\Interfaces\RepositoryInterface
{
    public function getAll()
    {
        return File::all();
    }

    public function getById(int $id)
    {
        return File::findOrFail($id);
    }

    public function delete(int $id)
    {
        File::destroy($id);
    }

    public function deleteMultiple(array $ids)
    {
        File::destroy($ids);
    }

    public function create(array $data)
    {
        return File::create($data);
    }

    public function update($id, array $data)
    {
        $file = $this->getById($id);
        $file->update($data);

        return $file;
    }
}
