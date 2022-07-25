<?php

	$api->group(['namespace' => 'App\Api\V1\Controllers\Customer'], function($api) {

        $api->get ('/home',              			    ['uses' => 'HomeController@index']);
		$api->get ('/recommend',              			    ['uses' => 'HomeController@similariy']);
		

		//====================================================================================>Product
		$api->get ('/products',              			['uses' => 'ProductController@listing']);
		$api->get ('/products/{id}',              		['uses' => 'ProductController@view']);
		$api->post('/products/review',              	['uses' => 'ProductController@review']);
		
	});