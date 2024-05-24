<?php

namespace Sokeio\Concerns;

use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Sokeio\Models\Slug;

trait WithSlug
{
    public function getSlugAttribute()
    {
        if ($this->slugModel) {
            return $this->slugModel->key;
        }
        return null;
    }
    protected function getSlugText()
    {
        return $this->title;
    }
    private function getSlugCountMax($slug)
    {
        $slugMax = $this->slugModel()->where('key', 'like', "{$slug}%")
            ->orderBy('key', 'desc')
            ->first();
        $count = 0;

        if ($slugMax === null) {
            return 0;
        } elseif ($slug !== $slugMax->slug) {
            $count = (int)str_replace("{$slug}-", "", $slugMax->slug) + 1;
        }
        return $count;
    }
    public function checkSlug($lug)
    {
        return $this->slugModel()->where('key', $lug)->exists();
    }
    public function reSlug()
    {
        if (!$this->slugModel) {
            $slug = Str::slug($this->getSlugText() ?? '');
            if (empty($slug)) {
                return;
            }
            $slugTemp =  $slug;
            $count = $this->getSlugCountMax($slug);
            do {
                $slugTemp = $count > 0 ? "{$slug}-{$count}" : $slug;

                if ($this->checkSlug($slugTemp)) {
                    $slugTemp = null;
                }

                $count++;
            } while ($slugTemp === null && $count < 100);
            $locale = '';
            if (method_exists($this, 'locale')) {
                $locale = $this->locale();
            }
            $this->slugModel()->updateOrCreate([
                'key' => $slugTemp,
                'locale' => $locale,
            ], []);
        }
    }
    public function initializeWithSlug()
    {
        static::created(function ($model) {
            $model->reSlug();
        });
        static::saved(function ($model) {
            $model->reSlug();
        });
    }
    public function slugModel(): MorphOne
    {
        $query = $this->morphOne(Slug::class, 'reference');
        if (method_exists($this, 'locale')) {
            $query = $query->where('locale', $this->locale());
        }
        return $query;
    }
    public function scopeWithSlugKey(Builder $query, $key)
    {
        return $query->with('slugModel')->whereHas('slugModel', function ($query) use ($key) {
            $query->where('key', $key);
        });
    }
}
