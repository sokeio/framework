<?php

use BytePlatform\FormCollection;
use BytePlatform\Item;
use BytePlatform\OptionHook;

return OptionHook::Create()->Options(function () {
    return FormCollection::Create()->Register(function (\BytePlatform\ItemManager $form) {
        $form->Title('Theme Option')->Item([]);

        return $form;
    });
});
