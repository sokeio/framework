<?php

use BytePlatform\FormCollection;
use BytePlatform\Item;
use BytePlatform\Models\Permission;
use BytePlatform\OptionHook;

return OptionHook::Create()->Options(function () {
    return FormCollection::Create()->Register(function (\BytePlatform\ItemManager $form) {
        $form->Title('System Information')->Item([
            Item::Add('page_title')->Type('tinymce')->Column(Item::Col12)->Title('Demo')->Attribute(function () {
                return 'style=""';
            }),
            Item::Add('page_tagify')->Type('tagify')->Column(Item::Col12)->Title('Tagify')->Attribute(function () {
                return 'style=""';
            })->FieldOption(function () {
                return [
                    "dropdown" => [
                        "classname"  => "color-blue",
                        "enabled"      => 0,
                        "maxItems"     => 5,
                        "position" => "text",
                        "closeOnSelect" => true,
                        "highlightFirst" => true,
                    ],
                    "enforceWhitelist" => false,
                    "templates" => '{
                        dropdownItemNoMatch: function(data) {
                            return `<div class=\'${this.settings.classNames.dropdownItem}\' value="noMatch" tabindex="0" role="option">
                                No suggestion found for: <strong>${data.value}</strong>
                            </div>`
                        }
                    }',
                    "whitelist" => Permission::query()->limit(10)->get()->map(function ($item) {
                        return [
                            'value' => $item->name,
                            'tagId' => $item->id
                        ];
                    }),
                    'whitelistAction' => 'LoadTest'
                ];
            }),
            Item::Add('page_start_date')->Type('number')->Column(Item::Col6)->Title('Start Date')
                ->Attribute(function () {
                    return 'style=""';
                })
                ->DataOption(function () {
                    return [
                        'toField' => 'page_end_date'
                    ];
                })
                ->FieldOption(function () {
                    return [
                        "enableTime" => true,
                        "dateFormat" => "Y-m-d H:i",

                    ];
                }),
            Item::Add('page_end_date')->Type('number')->Column(Item::Col6)->Title('End Date')
                ->Attribute(function () {
                    return 'style=""';
                })
                ->DataOption(function () {
                    return [
                        'fromField' => 'page_start_date'
                    ];
                })
                ->FieldOption(function () {
                    return [
                        "enableTime" => true,
                        "dateFormat" => "Y-m-d H:i",
                    ];
                }),
        ])
            ->Action('LoadTest', function ($params) {
                ['text' => $text] = $params;
                $query = Permission::query();
                if ($text) {
                    $query->where('name', 'like', '%' . $text . '%');
                }
                return  $query->limit(10)->get()->map(function ($item) {
                    return [
                        'value' => $item->name,
                        'tagId' => $item->id
                    ];
                });
            });
        return $form;
    });
});
