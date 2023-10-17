<?php

namespace BytePlatform\Concerns;

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
    public function ReSlug()
    {
        if (($this->slug == null || $this->slug == "")) {
            // produce a slug based on the activity title
            $slug = isset($this->FieldSlug) ? Str::slug($this->{$this->FieldSlug}) : Str::slug($this->name);
            if (!$slug) return;
            // check to see if any other slugs exist that are the same & count them
            $slugMax =  static::whereRaw("slug RLIKE '^{$slug}(-[0-9]+)?$'")->orderByDesc('slug')->first();
            $count = 0;
            if ($slugMax == null) {
                $this->slug = $slug;
            } else {
                if ($slug != $slugMax->slug)
                    $count = (str_replace("{$slug}-", "", $slugMax->slug) ?? 0) + 1;
            }
            do {
                // if other slugs exist that are the same, append the count to the slug
                if ($this->slug == null)
                    $this->slug = $count > 0 ? "{$slug}-{$count}" : $slug;
                if (static::where('slug',   $this->slug)->exists()) {
                    $this->slug = null;
                }
                $count++;
            } while ($this->slug == null || $count < 100);
        }
    }
    public function initializeWithSlug()
    {
        static::creating(function ($model) {
            $model->ReSlug();
        });
        static::saving(function ($model) {
            $model->ReSlug();
        });
    }
}
