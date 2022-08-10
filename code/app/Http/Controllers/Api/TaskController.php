<?php

namespace App\Http\Controllers\Api;

use App\Dictionaries\UserRolesDictionary;
use App\Http\Controllers\Controller;
use App\Http\Requests\FlagTaskRequest;
use App\Http\Requests\TaskStatusUpdateRequest;
use App\Http\Resources\FlagResource;
use App\Http\Resources\TaskPreviewResource;
use App\Http\Resources\TaskResource;
use App\Repositories\TaskRepository;
use App\Services\FlagService;
use Illuminate\Http\Request;

/**
 * @group Tasks
 */
class TaskController extends Controller
{
    private TaskRepository $repository;

    /**
     * @param  TaskRepository  $repository
     */
    public function __construct(TaskRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Remove the specified tasks from storage.
     *
     * @authenticated
     *
     * @param  int  $id
     * @return bool
     */
    public function destroy(int $id): bool
    {
        $task = $this->repository->getById($id);
        abort_unless(auth()->user()->hasRole(UserRolesDictionary::ROLE_DIRECTOR) || $task->author_id == $id, 403);

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
     * Update task status
     *
     * @authenticated
     *
     * @param  JobStatusUpdateRequest  $request
     * @param  int  $id
     * @return TaskResource
     */
    public function status(TaskStatusUpdateRequest $request, int $id): TaskResource
    {
        $task = $this->repository->getById($id);
        abort_unless(auth()->user()->hasRole(UserRolesDictionary::ROLE_DIRECTOR) || $task->author_id == $id, 403);

        return new TaskResource($this->repository->update($id, $request->validated()));
    }

    /**
     * Show task details
     *
     * @authenticated
     *
     * @param  Request  $request
     * @param  int  $id
     * @return TaskPreviewResource
     */
    public function show(Request $request, int $id): TaskPreviewResource
    {
        $task = $this->repository->getById($id);
        abort_unless(auth()->user()->hasRole(UserRolesDictionary::ROLE_DIRECTOR) || $task->author_id == $id, 403);

        return new TaskPreviewResource($task);
    }

    /**
     * Add new flag to task
     *
     * @authenticated
     *
     * @param  mixed  $request
     * @param  mixed  $id
     * @return FlagResource
     */
    public function flags_store(FlagTaskRequest $request, int $id)
    {
        $task = $this->repository->getById($id);

        return new FlagResource((new FlagService())->store($request->validated(), $task));
    }
}
