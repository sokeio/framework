<?php

namespace ByteTheme\Admin\Livewire\Pages\Profile;

use BytePlatform\Component;
use BytePlatform\Item;
use BytePlatform\ItemForm;
use BytePlatform\ItemManager;
use BytePlatform\Models\User;
use BytePlatform\Traits\WithItemManager;

class Edit extends Component
{
    use WithItemManager;
    protected function ItemManager()
    {
        return ItemManager::Form()->Model(User::class)->Item([
            Item::Add('name')->Title('Account Name')->Required(),
            Item::Add('phone_number')->Title('Phone Number')->Required(),
            Item::Add('email')->Title('Email')->Type('readonly')
        ]);
    }
    public ItemForm $form;
    public function mount()
    {
        $this->refreshUser();
    }
    public function refreshUser()
    {
        $this->form->DataToForm(auth()->user());
    }
    public function saveUser()
    {
        $this->form->setDataId(auth()->user()->id);
        $this->form->DataFromForm();
        return redirect(route('admin.profile'));
    }
    public function render()
    {
        page_title('Profile Edit');
        return view('theme::pages.profile.edit', [
            'itemManager' => $this->getItemManager()
        ]);
    }
}
