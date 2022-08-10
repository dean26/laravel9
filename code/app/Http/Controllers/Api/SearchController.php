<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SearchRequest;
use App\Http\Resources\SearchResource;
use App\Services\SearchService;

/**
 * @group Search
 */
class SearchController extends Controller
{
    /**
     * Global search for users and customers.
     *
     * @param  SearchRequest  $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function __invoke(SearchRequest $request)
    {
        return SearchResource::collection((new SearchService())->getData($request->validated()));
    }
}
