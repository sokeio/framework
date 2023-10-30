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
            Item::Add('title')->Title('Title'),
            Item::Add('description')->Title('Description')->Type('textarea'),
            Item::Add('image')->Title('Image')->Type('images'),
            Item::Add('author')->Title('Author'),
            Item::Add('robots')->Title('Robots')->Type('textarea'),
            Item::Add('canonical_url')->Title('Canonical Url'),
        ]);
    }
}
