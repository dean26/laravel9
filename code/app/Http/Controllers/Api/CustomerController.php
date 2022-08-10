<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerSearchRequest;
use App\Http\Requests\CustomerStoreRequest;
use App\Http\Requests\CustomerUpdateRequest;
use App\Http\Requests\JobSearchRequest;
use App\Http\Requests\NoteStoreRequest;
use App\Http\Resources\CustomerPreviewResource;
use App\Http\Resources\CustomerResource;
use App\Http\Resources\JobResource;
use App\Http\Resources\NoteResource;
use App\Models\Customer;
use App\Repositories\CustomerRepository;
use App\Services\CustomerService;
use App\Services\JobService;
use App\Services\NoteService;
use Illuminate\Http\Request;

/**
 * @group Customers
 */
class CustomerController extends Controller
{
    private CustomerRepository $repository;

    /**
     * @param  CustomerRepository  $repository
     */
    public function __construct(CustomerRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource. Paginated.
     *
     * @authenticated
     *
     * @param  CustomerSearchRequest  $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(CustomerSearchRequest $request): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return CustomerResource::collection((new CustomerService())->paginateByArrayData($request->validated()));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @authenticated
     *
     * @param  \App\Http\Requests\CustomerSearchRequest  $request
     * @return CustomerResource
     */
    public function store(CustomerStoreRequest $request): CustomerResource
    {
        return new CustomerResource($this->repository->create($request->validated()));
    }

    /**
     * Display the specified resource.
     *
     * @authenticated
     *
     * @param  int  $id
     * @return CustomerPreviewResource
     */
    public function show(int $id): CustomerPreviewResource
    {
        return new CustomerPreviewResource($this->repository->getById($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @authenticated
     *
     * @param  \App\Http\Requests\CustomerUpdateRequest  $request
     * @param  int  $id
     * @return CustomerResource
     */
    public function update(CustomerUpdateRequest $request, int $id): CustomerResource
    {
        return new CustomerResource($this->repository->update($id, $request->validated()));
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

    /**
     * Add new notes to customer
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
     * Show all customer notes
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

    /**
     * Show all customer jobs
     *
     * @authenticated
     *
     * @param  mixed  $request
     * @param  mixed  $id
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function jobs_index(JobSearchRequest $request, int $id): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $customer = $this->repository->getById($id);
        $data = [];
        $data['customer_id'] = $customer->id;

        return JobResource::collection((new JobService())->paginateByArrayData($data));
    }
}
