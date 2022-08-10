<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\FileRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * @group Files
 */
class FileController extends Controller
{
    private FileRepository $repository;

    /**
     * @param  FileRepository  $repository
     */
    public function __construct(FileRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Retrieve the specified file from storage.
     *
     * @authenticated
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function retrieve(int $id): \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
    {
        $file = $this->repository->getById($id);

        if (Storage::disk('digitalocean')->exists($file->path)) {
            $file_digital = Storage::disk('digitalocean')->get($file->path);
            $headers = [
                'Content-Type' => Storage::mimeType($file->path),
            ];

            return response($file_digital, 200, $headers);
        } else {
            abort(404, 'File not found');
        }
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
