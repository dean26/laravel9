<?php

namespace App\Http\Controllers\Api;

use App\Dictionaries\UserRolesDictionary;
use App\Http\Controllers\Controller;
use App\Http\Requests\GuideStoreRequest;
use App\Http\Resources\GuideResource;
use App\Repositories\GuideRepository;
use Illuminate\Http\Request;

/**
 * @group Guides
 */
class GuideController extends Controller
{
    private GuideRepository $repository;

    /**
     * @param  GuideRepository  $repository
     */
    public function __construct(GuideRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Remove the specified guide from storage.
     *
     * @authenticated
     *
     * @param  int  $id
     * @return bool
     */
    public function destroy(int $id): bool
    {
        $guide = $this->repository->getById($id);
        abort_unless(auth()->user()->hasRole(UserRolesDictionary::ROLE_DIRECTOR) || $guide->author_id == $id, 403);

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
        abort_unless(auth()->user()->hasRole(UserRolesDictionary::ROLE_DIRECTOR), 403);

        $ids = $request->input('ids');

        if (! is_array($ids)) {
            abort(404, 'Ids are not a array');
        }
        $this->repository->deleteMultiple($ids);

        return true;
    }

    /**
     * Update guide
     *
     * @authenticated
     *
     * @param  int  $id
     * @return GuideResource
     */
    public function update(GuideStoreRequest $request, int $id): GuideResource
    {
        $guide = $this->repository->getById($id);
        abort_unless(auth()->user()->hasRole(UserRolesDictionary::ROLE_DIRECTOR) || $guide->user_id == $id, 403);

        return new GuideResource($this->repository->update($id, $request->validated()));
    }

}
