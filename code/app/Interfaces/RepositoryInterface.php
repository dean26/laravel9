<?php

namespace App\Interfaces;

interface RepositoryInterface
{
    public function getAll();

    public function getById(int $id);

    public function delete(int $id);

    public function deleteMultiple(array $id);

    public function create(array $data);

    public function update($id, array $data);
}
