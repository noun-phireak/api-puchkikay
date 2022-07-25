<?php

	$api->group(['namespace' => 'App\Api\V1\Controllers\Printing'], function($api) {

        $api->get ('/pdf/{id}',              			    ['uses' => 'InvoicePrintingController@generateInvoice']);
	
		
	});