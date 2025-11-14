<?php

namespace Shaqi\GoogleMerchantFeed\Providers;

use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\Base\Supports\ServiceProvider;
use Illuminate\Routing\Events\RouteMatched;
use Botble\Base\Facades\PanelSectionManager;
use Botble\Base\PanelSections\PanelSectionItem;
use Botble\Ecommerce\PanelSections\SettingEcommercePanelSection;
use Botble\Setting\PanelSections\SettingOthersPanelSection;


class GoogleMerchantFeedServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function boot(): void
    {
        $this
            ->setNamespace('plugins/google-merchant-feed')
            ->loadHelpers()
            ->loadAndPublishTranslations()
            ->loadAndPublishViews()
            ->loadRoutes();

        $this->app['events']->listen(RouteMatched::class, function () {

        });

        PanelSectionManager::default()->beforeRendering(function (): void {
            PanelSectionManager::registerItem(
                SettingEcommercePanelSection::class,
                fn () => PanelSectionItem::make('google-merchant-feed')
                    ->setTitle(trans('plugins/google-merchant-feed::google-merchant-feed.settings.title'))
                    ->withIcon('ti ti-rss')
                    ->withPriority(140)
                    ->withDescription(trans('plugins/google-merchant-feed::google-merchant-feed.settings.description'))
                    ->withRoute('google-merchant-feed.settings')
            );
        });

    }
}
