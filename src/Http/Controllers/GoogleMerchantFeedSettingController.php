<?php

namespace Shaqi\GoogleMerchantFeed\Http\Controllers;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Setting\Http\Controllers\SettingController;
use Shaqi\GoogleMerchantFeed\Forms\GoogleMerchantFeedSettingForm;
use Shaqi\GoogleMerchantFeed\Http\Requests\GoogleMerchantFeedSettingRequest;

class GoogleMerchantFeedSettingController  extends SettingController
{

    public function edit()
    {
        $this->pageTitle(trans('plugins/google-merchant-feed::google-merchant-feed.settings.title'));

        return GoogleMerchantFeedSettingForm::create()->renderForm();
    }

    public function update(GoogleMerchantFeedSettingRequest $request): BaseHttpResponse
    {
        return $this->performUpdate($request->validated());
    }

}
