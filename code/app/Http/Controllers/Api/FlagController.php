<?php

namespace App\Http\Controllers\Api;

use App\Dictionaries\UserRolesDictionary;
use App\Http\Controllers\Controller;
use App\Repositories\FlagRepository;
use Illuminate\Http\Request;

/**
 * @group Flags
 */
class FlagController extends Controller
{
    private FlagRepository $repository;

    /**
     * @param  FlagRepository  $repository
     */
    public function __construct(FlagRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Remove the specified note from storage.
     *
     * @authenticated
     *
     * @param  int  $id
     * @return bool
     */
    public function destroy(int $id): bool
    {
        $flag = $this->repository->getById($id);
        abort_unless(auth()->user()->hasRole(UserRolesDictionary::ROLE_DIRECTOR) || $flag->user_id == $id, 403);

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
}
