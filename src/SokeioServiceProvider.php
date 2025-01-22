<?php

namespace Sokeio;

use Livewire\Livewire;
use Sokeio\Concerns\WithServiceProvider;
use Sokeio\Providers\SocialiteServiceProvider;
use Sokeio\Livewire\LivewireServiceProvider;
use Sokeio\MediaStorage\MediaStorageServiceProvider;
use Sokeio\Core\PlatformServiceProvider;
use Sokeio\Dashboard\DashboardServiceProvider;
use Sokeio\Enums\AlertType;
use Sokeio\Enums\UIKey;
use Sokeio\Theme\ThemeServiceProvider;
use Sokeio\UI\Common\Button;
use Sokeio\UI\Common\Card;
use Sokeio\UI\Field\Input;
use Sokeio\UI\Field\MediaIcon;
use Sokeio\UI\SoUI;

class SokeioServiceProvider extends \Illuminate\Support\ServiceProvider
{
    use WithServiceProvider;
    public function configurePackage(ServicePackage $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         */
        $package
            ->name('sokeio')
            ->hasConfigFile(['sokeio', 'sokeio-stubs'])
            ->routeWeb()
            ->hasViews()
            ->hasHelpers()
            ->hasAssets()
            ->hasTranslations()
            ->runsMigrations();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function registeringPackage()
    {
        WatchTime::start();
        $this->app->register(MediaStorageServiceProvider::class);
        $this->app->register(ThemeServiceProvider::class);
        $this->app->register(PlatformServiceProvider::class);
        $this->app->register(LivewireServiceProvider::class);
        $this->app->register(DashboardServiceProvider::class);
        $this->app->register(SocialiteServiceProvider::class);
        Theme::bodyAfter(function () {
            if (setting('SOKEIO_SHOW_PROGRESS_TIMER')) {
                echo '<p class="text-center text-dark position-fixed m-0 p-0 z-0 bottom-0 w-100 " > Load time: ' . WatchTime::showSeconds() . '</p>';
            }
            if (setting('SOKEIO_SHOW_POSITION_DEBUG') && Platform::isUrlAdmin()) {
                echo '<script>document.body.classList.add("so-position-show-debug");</script>';
            }
            echo Livewire::mount('sokeio::global-body');
        });
    }
    public function bootingPackage()
    {
        config(['auth.providers.users.model' => config('sokeio.model.user')]);

        // packageBooted
        Theme::location('SOKEIO_ADMIN_THEME_HEADER_RIGHT_BEFORE', function () {
            echo "<div class='btn-group me-2 py-1'>
                        <a href='" . url('/') . "' class='btn btn-primary' target='_blank'>Go To Site</a>
                    </div>";
        });

        SoUI::registerUI(
            [
                Input::make('name')->label('Name'),
                MediaIcon::make('icon')->label('Icon'),
                Input::make('link')->label('Link'),
                Button::make()->boot(function (Button $button) {
                    if ($button->getValueByKey('id')) {
                        $button->text('Update')
                            ->icon('ti ti-check')
                            ->className('btn btn-success p-2 mt-1');
                    } else {
                        $button->text(__('Add to menu'))
                            ->icon('ti ti-plus')
                            ->className('btn btn-primary p-2 mt-1');
                    }
                })
                    ->wireClick(function (Button $button) {
                        $button->resetErrorBag();
                        $fail = false;
                        if (!($button->getValueByKey('name'))) {
                            $fail = true;
                            $button->addError('name', 'Name is required');
                        }
                        if (!($button->getValueByKey('link'))) {
                            $fail = true;
                            $button->addError('link', 'Link is required');
                        }
                        // if (!($button->getValueByKey('icon'))) {
                        //     $fail = true;
                        //     $button->addError('icon', 'Icon is required');
                        // }
                        if ($fail) {
                            return;
                        }
                        $id = $button->getValueByKey('id');
                        $button->getWire()->updateItemMenu(
                            $id,
                            function ($menuItem) use ($button) {
                                $menuItem->name = $button->getValueByKey('name');
                                $menuItem->link = $button->getValueByKey('link');
                                $menuItem->icon = $button->getValueByKey('icon');
                                $menuItem->type = 'link';
                            }
                        );
                        $button->wireAlert(
                            $button->getValueByKey('name') . ' has been saved!',
                            'Menu',
                            AlertType::SUCCESS
                        );
                        if (!$id) {
                            $button->changeValue('name', '');
                            $button->changeValue('link', '');
                            $button->changeValue('icon', '');
                        }
                    })
                    ->when(function (Button $button) {
                        return $button->getWire()->menuId;
                    }),
                Button::make()->text('Remove')
                    ->icon('ti ti-trash')
                    ->className('btn btn-danger p-2 ms-2 mt-1')
                    ->confirm('Are you really want to remove?')
                    ->wireClick(function (Button $button) {
                        $id = $button->getValueByKey('id');
                        $button->getWire()->removeItemMenu($id);
                    })->when(function (Button $button) {
                        return $button->getValueByKey('id');
                    })
            ],
            UIKey::MENU_ITEM_TYPE->value . 'link',
            UIKey::MENU_ADD_ITEM->value,
            fn(Card $card) => $card->title('Custom Link')->className('mb-2')
                ->boot(function (Card $card) {
                    $card->groupField(null);
                    if (!$card->getValueByKey('id')) {

                        $card->groupField('links');
                    }
                })
        );
    }
}
