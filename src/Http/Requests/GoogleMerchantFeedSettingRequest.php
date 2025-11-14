<?php

namespace Shaqi\GoogleMerchantFeed\Http\Requests;

use Botble\Base\Rules\EmailRule;
use Botble\Base\Rules\OnOffRule;
use Botble\Support\Http\Requests\Request;

class GoogleMerchantFeedSettingRequest extends Request
{


    public function rules(): array
    {
        return [
           'enable_google_merchant_feed' => new OnOffRule(),
        ];
    }

    // public function attributes(): array
    // {
    //     return [
    //         'is_quote_enabled.*' => trans('plugins/quote::quote.settings.receiver_emails'),
    //     ];
    // }
}
