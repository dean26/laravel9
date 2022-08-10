<?php

namespace App\Http\Controllers\Api;

use App\Dictionaries\UserRolesDictionary;
use App\Http\Controllers\Controller;
use App\Http\Requests\FileStoreRequest;
use App\Http\Requests\FlagJobRequest;
use App\Http\Requests\GuideStoreRequest;
use App\Http\Requests\JobSearchRequest;
use App\Http\Requests\JobStatusUpdateRequest;
use App\Http\Requests\JobStoreRequest;
use App\Http\Requests\JobUpdateRequest;
use App\Http\Requests\NoteStoreRequest;
use App\Http\Requests\TaskStoreRequest;
use App\Http\Resources\FileResource;
use App\Http\Resources\FlagResource;
use App\Http\Resources\GuideResource;
use App\Http\Resources\JobPreviewResource;
use App\Http\Resources\JobResource;
use App\Http\Resources\NoteResource;
use App\Http\Resources\TaskResource;
use App\Models\Job;
use App\Repositories\JobRepository;
use App\Services\FileService;
use App\Services\FlagService;
use App\Services\GuideService;
use App\Services\JobService;
use App\Services\NoteService;
use App\Services\TaskService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * @group Jobs
 */
class JobController extends Controller
{
    private JobRepository $repository;

    /**
     * @param  JobRepository  $repository
     */
    public function __construct(JobRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource. Paginated.
     *
     * @authenticated
     *
     * @param  JobSearchRequest  $request
     * @return AnonymousResourceCollection
     */
    public function index(JobSearchRequest $request): AnonymousResourceCollection
    {
        return JobResource::collection((new JobService())->paginateByArrayData($request->validated()));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @authenticated
     *
     * @param  JobStoreRequest  $request
     * @return JobPreviewResource
     */
    public function store(JobStoreRequest $request): JobPreviewResource
    {
        abort_if(! auth()->user()->hasRole(UserRolesDictionary::ROLE_DIRECTOR), 403);

        return new JobPreviewResource($this->repository->create($request->validated()));
    }

    /**
     * Display the specified resource.
     *
     * @authenticated
     *
     * @param  int  $id
     * @return JobPreviewResource
     */
    public function show(int $id): JobPreviewResource
    {
        $job = $this->repository->getById($id);
        if (! auth()->user()->hasRole(UserRolesDictionary::ROLE_DIRECTOR)) {
            abort_if($job->user_id != auth()->user()->id, 403);
        }

        return new JobPreviewResource($job);
    }

    /**
     * Update the specified resource in storage.
     *
     * @authenticated
     *
     * @param  JobUpdateRequest  $request
     * @param  int  $id
     * @return JobPreviewResource
     */
    public function update(JobUpdateRequest $request, int $id): JobPreviewResource
    {
        abort_if(! auth()->user()->hasRole(UserRolesDictionary::ROLE_DIRECTOR), 403);

        return new JobPreviewResource($this->repository->update($id, $request->validated()));
    }

    /**
     * Update job status
     *
     * @authenticated
     *
     * @param  JobStatusUpdateRequest  $request
     * @param  int  $id
     * @return JobPreviewResource
     */
    public function status(JobStatusUpdateRequest $request, int $id): JobPreviewResource
    {
        return new JobPreviewResource($this->repository->update($id, $request->validated()));
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
        abort_if(! auth()->user()->hasRole(UserRolesDictionary::ROLE_DIRECTOR), 403);

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
        abort_if(! auth()->user()->hasRole(UserRolesDictionary::ROLE_DIRECTOR), 403);

        $ids = $request->input('ids');

        if (! is_array($ids)) {
            abort(404, 'Ids are not a array');
        }
        $this->repository->deleteMultiple($ids);

        return true;
    }

    /**
     * Add new note to job
     *
     * @authenticated
     *
     * @param  mixed  $request
     * @param  mixed  $id
     * @return NoteResource
     */
    public function notes_store(NoteStoreRequest $request, int $id)
    {
        $job = $this->repository->getById($id);

        return new NoteResource((new NoteService())->store($request->validated(), $job));
    }

    /**
     * Show all job notes
     *
     * @authenticated
     *
     * @param  mixed  $request
     * @param  mixed  $id
     * @return AnonymousResourceCollection
     */
    public function notes_index(Request $request, int $id)
    {
        $job = $this->repository->getById($id);

        return NoteResource::collection($job->notes);
    }

    /**
     * Add new file to job
     *
     * @authenticated
     *
     * @param  mixed  $request
     * @param  mixed  $id
     * @return FileResource
     */
    public function files_store(FileStoreRequest $request, int $id)
    {
        $job = $this->repository->getById($id);

        return new FileResource((new FileService())->store($request, $job));
    }

    /**
     * Show all job files
     *
     * @authenticated
     *
     * @param  mixed  $request
     * @param  mixed  $id
     * @return AnonymousResourceCollection
     */
    public function files_index(Request $request, int $id): AnonymousResourceCollection
    {
        $job = $this->repository->getById($id);

        return FileResource::collection($job->files);
    }

    /**
     * Add new flag to job
     *
     * @authenticated
     *
     * @param  mixed  $request
     * @param  mixed  $id
     * @return FlagResource
     */
    public function flags_store(FlagJobRequest $request, int $id): FlagResource
    {
        $job = $this->repository->getById($id);

        return new FlagResource((new FlagService())->store($request->validated(), $job));
    }

    /**
     * Add new task to job
     *
     * @authenticated
     *
     * @param  mixed  $request
     * @param  mixed  $id
     * @return TaskResource
     */
    public function tasks_store(TaskStoreRequest $request, int $id)
    {
        $job = $this->repository->getById($id);

        return new TaskResource((new TaskService())->store($request->validated(), $job));
    }

    /**
     * Show all job tasks
     *
     * @authenticated
     *
     * @param  mixed  $request
     * @param  mixed  $id
     * @return AnonymousResourceCollection
     */
    public function tasks_index(Request $request, int $id): AnonymousResourceCollection
    {
        $job = $this->repository->getById($id);

        return TaskResource::collection($job->tasks);
    }

    /**
     * Add new guide to job
     *
     * @authenticated
     *
     * @param  mixed  $request
     * @param  mixed  $id
     * @return GuideResource
     */
    public function guide_store(GuideStoreRequest $request, int $id)
    {
        $job = $this->repository->getById($id);

        return new GuideResource((new GuideService())->store($request->validated(), $job));
    }

    /**
     * Show all job guides
     *
     * @authenticated
     *
     * @param  mixed  $request
     * @param  mixed  $id
     * @return AnonymousResourceCollection
     */
    public function guide_index(Request $request, int $id): AnonymousResourceCollection
    {
        $job = $this->repository->getById($id);

        return GuideResource::collection($job->guides);
    }
}
