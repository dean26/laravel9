<?php

namespace App\Repositories;

use App\Models\Note;

class NoteRepository implements \App\Interfaces\RepositoryInterface
{
    public function getAll()
    {
        return Note::all();
    }

    public function getById(int $id)
    {
        return Note::findOrFail($id);
    }

    public function delete(int $id)
    {
        Note::destroy($id);
    }

    public function deleteMultiple(array $ids)
    {
        Note::destroy($ids);
    }

    public function create(array $data)
    {
        return Note::create($data);
    }

    public function update($id, array $data)
    {
        $note = $this->getById($id);
        $note->update($data);

        return $note;
    }
}
