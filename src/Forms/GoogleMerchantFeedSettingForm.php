<?php

namespace Shaqi\GoogleMerchantFeed\Forms;

use Botble\Base\Facades\Assets;
use Botble\Base\Forms\FieldOptions\AlertFieldOption;
use Botble\Base\Forms\FieldOptions\CheckboxFieldOption;
use Botble\Base\Forms\FieldOptions\OnOffFieldOption;
use Botble\Base\Forms\FieldOptions\TextareaFieldOption;
use Botble\Base\Forms\Fields\AlertField;
use Botble\Base\Forms\Fields\OnOffCheckboxField;
use Botble\Base\Forms\Fields\OnOffField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Setting\Forms\SettingForm;
use Shaqi\GoogleMerchantFeed\Http\Requests\GoogleMerchantFeedSettingRequest;

class GoogleMerchantFeedSettingForm extends SettingForm
{
    public function setup(): void
    {
        parent::setup();

        $this
            ->setSectionTitle(trans('plugins/google-merchant-feed::google-merchant-feed.settings.title'))
            ->setSectionDescription(trans('plugins/google-merchant-feed::google-merchant-feed.settings.description'))
            ->setValidatorClass(GoogleMerchantFeedSettingRequest::class)
            ->add(
                'enable_google_merchant_feed',
                OnOffCheckboxField::class,
                CheckboxFieldOption::make()
                    ->label(trans('plugins/google-merchant-feed::google-merchant-feed.settings.enable_google_merchant_feed'))
                    ->value($value = old('enable_google_merchant_feed', setting('enable_google_merchant_feed')))
            )
            ->addOpenCollapsible('enable_google_merchant_feed', '1', $value)
            ->add(
                'google_merchant_feed_url',
                AlertField::class,
                AlertFieldOption::make()
                    ->content(route('public.google-shopping.xml-feed'))
                    ->type('info')
            )
            ->addCloseCollapsible('enable_google_merchant_feed', '1')
           ;
    }
}
