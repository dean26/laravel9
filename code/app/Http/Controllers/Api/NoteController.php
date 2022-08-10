<?php

namespace App\Http\Controllers\Api;

use App\Dictionaries\UserRolesDictionary;
use App\Http\Controllers\Controller;
use App\Http\Requests\NoteStoreRequest;
use App\Http\Resources\NoteResource;
use App\Repositories\NoteRepository;
use Illuminate\Http\Request;

/**
 * @group Note
 */
class NoteController extends Controller
{
    private NoteRepository $repository;

    /**
     * @param  NoteRepository  $repository
     */
    public function __construct(NoteRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Update note
     *
     * @authenticated
     *
     * @param  int  $id
     * @return NoteResource
     */
    public function update(NoteStoreRequest $request, int $id): NoteResource
    {
        $note = $this->repository->getById($id);
        abort_unless(auth()->user()->hasRole(UserRolesDictionary::ROLE_DIRECTOR) || $note->user_id == $id, 403);

        return new NoteResource($this->repository->update($id, $request->validated()));
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
        $note = $this->repository->getById($id);
        abort_unless(auth()->user()->hasRole(UserRolesDictionary::ROLE_DIRECTOR) || $note->user_id == $id, 403);

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
