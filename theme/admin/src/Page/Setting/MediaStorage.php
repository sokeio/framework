<?php

namespace SokeioTheme\Admin\Page\Setting;

use Sokeio\Attribute\PageInfo;
use Sokeio\MediaStorage as SokeioMediaStorage;
use Sokeio\UI\Common\Button;
use Sokeio\UI\Field\Select;
use Sokeio\UI\Field\SwitchField;
use Sokeio\UI\PageUI;
use Sokeio\UI\SettingUI;
use Sokeio\UI\WithSettingUI;

#[PageInfo(
    admin: true,
    auth: true,
    title: 'Media Storage',
    menu: true,
    menuTitle: 'Media Storage',
    icon: 'ti ti-cloud-data-connection',
    sort: -20,
)]
class MediaStorage extends \Sokeio\Page
{
    public const KEY_UI = "SOKEIO_MEDIA_STORAGE";
    use WithSettingUI;
    private function mediaStorageSetting()
    {
        return SettingUI::make([
            Select::make('SOKEIO_MEDIA_STORAGE_DEFAULT')
                ->label('Default Media Storage')
                ->dataSource(SokeioMediaStorage::getAll())->col12(),
            SwitchField::make('SOKEIO_MEDIA_STORAGE_PUBLIC_ENABLE')
                ->labelTrue('Enable')
                ->labelFalse('Disable')
                ->valueDefault(true)
                ->label('Public Media Storage')->col6(),
            SwitchField::make('SOKEIO_MEDIA_STORAGE_PRIVATE_ENABLE')
                ->labelTrue('Enable')
                ->labelFalse('Disable')
                ->valueDefault(true)
                ->label('Private Media Storage')->col6(),
        ])
            ->bodyRow()
            ->title('Media Storage Setting')
            ->icon('ti ti-settings-up')
            ->column(self::COLUMN_GROUP)
            
            ->prefix('formData.mediaStorage')
            ->className('mb-3');
    }
    protected function setupUI()
    {
        return [
            PageUI::make([
                $this->mediaStorageSetting(),
            ])->childWithKey(self::KEY_UI)->row()->rightUI([
                Button::make()
                    ->className('btn btn-primary')
                    ->label('Save Setting')
                    ->icon('ti ti-save')
                    ->wireClick('saveData')
            ])


        ];
    }
}
