<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Interfaces\ApiInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Validator;

class FoursquareController extends Controller
{
    // ApiClass instance
    private $apiInstance;

    /**
     * FoursquareController constructor.
     * SOLID principle (Dependency Injection)
     * @param ApiInterface $api
     */
    public function __construct(ApiInterface $api)
    {
        $this->apiInstance = $api;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function getCategories()
    {
        $categories = [];
        $response = [];
        // check cache for data
        if (Cache::has('categories')) {
            $categories = Cache::get('categories');
        } else {
            // call Api
            $this->apiInstance->setMethod('GET');
            $this->apiInstance->setUrl('https://api.foursquare.com/v2/venues/categories');
            $result = $this->apiInstance->callAPI();
            $response = json_decode($result, true);
            // check status code
            if ($response['meta']['code'] == 200) {
                // cache response data
                $categories = $response['response']['categories'];
                // cache 1 minute
                Cache::put('categories', $categories, 60);
            } else {
                abort($response['meta']['code'], $response['meta']['errorDetail']);
            }
        }
        // render page
        return view('home', compact('categories'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function getInfo(Request $request)
    {
        // validation
        $validator = Validator::make($request->all(), [
            'category' => 'required',
        ]);
        if ($validator->failed()) {
            return response()->json(['error' => 'category should be post!']);
        }
        // replace space with underline
        $category = str_replace(' ', '_', $request->category);
        $result_array = [
            'status'  => 200,
            'message' => '',
            'data'    => []
        ];
        // check cache for data
        if (Cache::has('valletta_' . $category)) {
            $result_array['data'] = Cache::get('valletta_' . $category . '_location');
        } else {
            // call Api
            $this->apiInstance->setMethod('GET');
            $this->apiInstance->setUrl('https://api.foursquare.com/v2/venues/explore?near=valletta&query=' . $category);
            $result = $this->apiInstance->callAPI();
            $response = json_decode($result, true);
            // check status code
            $result_status = $response['meta']['code'];
            if ($result_status == 200) {
                // cache response data
                $result_array['data'] = $response['response'];
                // cache 1 minute
                Cache::put('valletta_' . $category . '_location', $result_array, 60);
            } else {
                $result_array['message'] = $response['meta']['errorDetail'];
            }
        }
        // render page
        return response()->json($result_array);
    }
}
