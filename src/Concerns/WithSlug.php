<?php

namespace Sokeio\Concerns;

use Illuminate\Support\Str;

trait WithSlug
{
    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
    protected function getSlugText()
    {
        return $this->name;
    }
    private function getSlugCountMax($slug)
    {
        $slugMax = static::where('slug', 'like', "{$slug}%")
            ->orderBy('slug', 'desc')
            ->first();
        $count = 0;

        if ($slugMax === null) {
            $this->slug = $slug;
        } elseif ($slug !== $slugMax->slug) {
            $count = (int)str_replace("{$slug}-", "", $slugMax->slug) + 1;
        }
        return $count;
    }
    public function reSlug()
    {
        if (empty($this->slug)) {
            $slug = Str::slug($this->getSlugText() ?? '');
            if (empty($slug)) {
                return;
            }
            $count = $this->getSlugCountMax($slug);
            do {
                $this->slug = $count > 0 ? "{$slug}-{$count}" : $slug;

                if (static::where('slug', $this->slug)->exists()) {
                    $this->slug = null;
                }

                $count++;
            } while ($this->slug === null && $count < 100);
        }
    }
    public function initializeWithSlug()
    {
        static::creating(function ($model) {
            $model->reSlug();
        });
        static::saving(function ($model) {
            $model->reSlug();
        });
    }
}
