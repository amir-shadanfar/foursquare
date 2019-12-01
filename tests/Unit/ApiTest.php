<?php

namespace Tests\Unit;

use App\Classes\ApiClass;
use Tests\TestCase;

class ApiTest extends TestCase
{
    /**
     * test getCategories method
     */
    public function testGetCategories()
    {
        $apiClass = new ApiClass();
        $apiClass->setMethod('GET');
        $apiClass->setUrl('https://api.foursquare.com/v2/venues/categories');
        $result = $apiClass->callAPI();
        $response = json_decode($result, true);
        // assert status code
        $this->assertEquals($response['meta']['code'], 200);
        // assert categories count
        $this->assertCount(10, $response['response']['categories']);
    }

    /**
     * test getInfo method
     */
    public function testGetInfo()
    {
        $category = 'Art_Gallery';
        $apiClass = new ApiClass();
        $apiClass->setMethod('GET');
        $apiClass->setUrl('https://api.foursquare.com/v2/venues/explore?near=valletta&query=' . $category);
        $result = $apiClass->callAPI();
        $response = json_decode($result, true);
        // assert status code
        $this->assertEquals($response['meta']['code'], 200);
        // assert categories count
        $this->assertCount(6, $response['response']['groups'][0]['items']);
        // control one of the item
        $flag = false;
        foreach ($response['response']['groups'][0]['items'] as $item) {
            if ($item['venue']['name'] == 'Nenu the Artisan Baker') {
                $flag = true;
            }
        }
        ($flag) ? $this->assertTrue(true) : $this->assertTrue(false);
    }
}
