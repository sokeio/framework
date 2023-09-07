<?php

namespace BytePlatform\Actions;

use BytePlatform\Item;
use BytePlatform\ItemManager;
use BytePlatform\Models\User;
use BytePlatform\Traits\WithAction;

class TestForm
{
  use WithAction;
  public function DoAction($slug)
  {
    return [
      'manager' => ItemManager::Form()
        ->NoBindData()
        ->Model(User::class)->Item([
          Item::Add('password')->Title('Password')->Column(Item::Col12)->Type('images')->Required()->Rules(['same:check_password']),
          Item::Add('check_password')->Title('check_Password')->Column(Item::Col12)->Type('password')->Required(),
        ])->ButtonSaveText(function () {
          return 'Change password';
        })->FormDoSave(function(){
          
        })
    ];
  }
}
