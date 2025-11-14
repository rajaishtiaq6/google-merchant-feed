<?php

use Botble\Base\Facades\AdminHelper;
use Botble\Base\Facades\BaseHelper;
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Shaqi\GoogleMerchantFeed\Http\Controllers', 'middleware' => ['web', 'core']], function () {

    AdminHelper::registerRoutes(function () {


        Route::group(['prefix' => 'settings'], function (): void {
            Route::get('google-merchant-feed', [
                'as' => 'google-merchant-feed.settings',
                'uses' => 'GoogleMerchantFeedSettingController@edit',
            ]);

            Route::put('google-merchant-feed', [
                'as' => 'google-merchant-feed.settings.update',
                'uses' => 'GoogleMerchantFeedSettingController@update',
                'permission' => 'google-merchant-feed.settings',
            ]);
        });

    });

    if (defined('THEME_MODULE_SCREEN_NAME')) {

            Route::group(apply_filters(BASE_FILTER_GROUP_PUBLIC_ROUTE, []), function () {
                Route::get('/google-shopping/xml-feed', [
                    'as' => 'public.google-shopping.xml-feed',
                    'uses' => 'PublicController@generateFeed',
                ]);
            });
         
    }

});
