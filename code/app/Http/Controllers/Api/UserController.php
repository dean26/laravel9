<?php

namespace App\Http\Controllers\Api;

use App\Dictionaries\UserRolesDictionary;
use App\Http\Controllers\Controller;
use App\Http\Requests\NoteStoreRequest;
use App\Http\Requests\UserSearchRequest;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdatePasswordRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\NoteResource;
use App\Http\Resources\UserPreviewResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\NoteService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * @group Users
 */
class UserController extends Controller
{
    private UserRepository $repository;

    /**
     * @param  UserRepository  $repository
     */
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Show all users, only for "directors". Paginated.
     *
     * @authenticated
     *
     * @param  mixed  $request
     * @param  mixed  $id
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(UserSearchRequest $request)
    {
        abort_if(! auth()->user()->hasRole(UserRolesDictionary::ROLE_DIRECTOR), 403);

        return UserResource::collection((new UserService)->paginateByArrayData($request->all()));
    }

    /**
     * Show specific user data, for "directors" and for authenticated user
     *
     * @authenticated
     *
     * @param  mixed  $request
     * @param  mixed  $id
     * @return UserPreviewResource
     */
    public function show(Request $request, int $id)
    {
        abort_unless(auth()->user()->hasRole(UserRolesDictionary::ROLE_DIRECTOR) || auth()->user()->id == $id, 403);

        return new UserPreviewResource($this->repository->getById($id));
    }

    /**
     * Store new user, only for "directors"
     * Possible values for roles [Director, Installer, Warehouse]
     *
     * @authenticated
     *
     * @param  mixed  $request
     * @return UserResource
     */
    public function store(UserStoreRequest $request)
    {
        abort_if(! auth()->user()->hasRole(UserRolesDictionary::ROLE_DIRECTOR), 403);

        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        $user = $this->repository->create($data);

        if (isset($data['role'])) {
            $user->assignRole($data['role']);
        }

        return new UserResource($user);
    }

    /**
     * Update user, for "directors" and for authenticated user
     *
     * Only director can update user roles
     *
     * @authenticated
     *
     * @param  mixed  $request
     * @return UserResource
     */
    public function update(UserUpdateRequest $request, int $id)
    {
        abort_unless(auth()->user()->hasRole(UserRolesDictionary::ROLE_DIRECTOR) || auth()->user()->id == $id, 403);

        $data = $request->validated();
        $user = $this->repository->update($id, $data);

        if (isset($data['role']) && auth()->user()->hasRole(UserRolesDictionary::ROLE_DIRECTOR)) {
            $user->syncRoles($data['role']);
        }

        return new UserResource($user);
    }

    /**
     * Password change action, for "directors" only
     *
     * @authenticated
     *
     * @param  mixed  $request
     * @param  mixed  $id
     * @return UserResource
     */
    public function update_password(UserUpdatePasswordRequest $request, int $id)
    {
        abort_if(! auth()->user()->hasRole(UserRolesDictionary::ROLE_DIRECTOR), 403);

        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        $user = $this->repository->update($id, $data);

        return new UserResource($user);
    }

    /**
     * Destroy user, only for "directors"
     *
     * @authenticated
     *
     * @param  mixed  $request
     * @param  mixed  $id
     * @return void
     */
    public function destroy(Request $request, int $id)
    {
        abort_if(! auth()->user()->hasRole(UserRolesDictionary::ROLE_DIRECTOR), 403);

        $this->repository->delete($id);

        return true;
    }

    /**
     * Remove the specified resources from storage, only for "directors"
     *
     * @authenticated
     * @bodyParam ids integer[] required
     *
     * @return bool
     */
    public function destroy_many(Request $request): bool
    {
        abort_if(! auth()->user()->hasRole(UserRolesDictionary::ROLE_DIRECTOR), 403);

        $ids = $request->input('ids');

        if (! is_array($ids)) {
            abort(404, 'Ids are not a array');
        }
        $this->repository->deleteMultiple($ids);

        return true;
    }

    /**
     * Add new notes to user
     *
     * @authenticated
     *
     * @param  mixed  $request
     * @param  mixed  $id
     * @return NoteResource
     */
    public function notes_store(NoteStoreRequest $request, int $id)
    {
        $customer = $this->repository->getById($id);

        return new NoteResource((new NoteService())->store($request->validated(), $customer));
    }

    /**
     * Show all users notes
     *
     * @authenticated
     *
     * @param  mixed  $request
     * @param  mixed  $id
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function notes_index(Request $request, int $id)
    {
        $customer = $this->repository->getById($id);

        return NoteResource::collection($customer->notes);
    }
}
