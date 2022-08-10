<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ItemSearchRequest;
use App\Http\Requests\ItemStoreUpdateRequest;
use App\Http\Resources\ItemResource;
use App\Repositories\ItemRepository;
use App\Services\ItemService;
use Illuminate\Http\Request;

/**
 * @group Items
 */
class ItemController extends Controller
{
    private ItemRepository $repository;

    /**
     * @param  ItemRepository  $repository
     */
    public function __construct(ItemRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource. Paginated.
     *
     * @authenticated
     *
     * @param  ItemSearchRequest  $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(ItemSearchRequest $request): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return ItemResource::collection((new ItemService())->paginateByArrayData($request->validated()));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @authenticated
     *
     * @param  ItemStoreUpdateRequest  $request
     * @return ItemResource
     */
    public function store(ItemStoreUpdateRequest $request): ItemResource
    {
        return new ItemResource($this->repository->create($request->validated()));
    }

    /**
     * Display the specified resource.
     *
     * @authenticated
     *
     * @param  int  $id
     * @return ItemResource
     */
    public function show(int $id): ItemResource
    {
        return new ItemResource($this->repository->getById($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @authenticated
     *
     * @param  ItemStoreUpdateRequest  $request
     * @param  int  $id
     * @return ItemResource
     */
    public function update(ItemStoreUpdateRequest $request, int $id): ItemResource
    {
        return new ItemResource($this->repository->update($id, $request->validated()));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @authenticated
     *
     * @param  int  $id
     * @return bool
     */
    public function destroy(int $id): bool
    {
        $this->repository->delete($id);

        return true;
    }

    /**
     * Remove the specified resources from storage.
     *
     * @authenticated
     * @bodyParam ids integer[] required
     *
     * @return bool
     */
    public function destroy_many(Request $request): bool
    {
        $ids = $request->input('ids');

        if (! is_array($ids)) {
            abort(404, 'Ids are not a array');
        }
        $this->repository->deleteMultiple($ids);

        return true;
    }
}
