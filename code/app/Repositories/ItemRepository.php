<?php

namespace App\Repositories;

use App\Models\Item;

class ItemRepository implements \App\Interfaces\RepositoryInterface
{
    public function getAll()
    {
        return Item::all();
    }

    public function getById(int $id)
    {
        return Item::findOrFail($id);
    }

    public function delete(int $id)
    {
        Item::destroy($id);
    }

    public function deleteMultiple(array $ids)
    {
        Item::destroy($ids);
    }

    public function create(array $data)
    {
        return Item::create($data);
    }

    public function update($id, array $data)
    {
        $item = $this->getById($id);
        $item->update($data);

        return $item;
    }
}
