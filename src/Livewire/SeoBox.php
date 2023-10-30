<?php

namespace BytePlatform\Livewire;

use BytePlatform\Component;
use BytePlatform\Concerns\WithFormData;
use BytePlatform\Item;
use BytePlatform\ItemManager;

class SeoBox extends Component
{
    use WithFormData;
    protected function ItemManager()
    {
        if (!class_exists('\\BytePlatform\\Seo\\Models\\SEO')) return null;
        return ItemManager::Form()->Model(\BytePlatform\Seo\Models\SEO::class)->Item([
            Item::Add('title')->Column(Item::Col12)->Title('Title'),
            Item::Add('description')->Column(Item::Col12)->Title('Description')->Type('textarea'),
            Item::Add('image')->Column(Item::Col12)->Title('Image')->Type('images'),
            Item::Add('author')->Column(Item::Col12)->Title('Author'),
            Item::Add('robots')->Column(Item::Col12)->Title('Robots')->Type('textarea'),
            Item::Add('canonical_url')->Column(Item::Col12)->Title('Canonical Url'),
        ]);
    }
}
