<?php

namespace Sokeio\Translatable\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Sokeio\Translatable\ModelTranslatable;

/**
 * @property-read string $translationModel
 * @property-read string $translationForeignKey
 */
trait Relationship
{
    /**
     * @deprecated
     */
    public function getRelationKey(): string
    {
        return $this->getTranslationRelationKey();
    }

    /**
     * @internal will change to protected
     */
    public function getTranslationModelName(): string
    {
        return $this->translationModel ?: $this->getTranslationModelNameDefault();
    }

    /**
     * @internal will change to private
     */
    public function getTranslationModelNameDefault(): string
    {
        return ModelTranslatable::class;
    }
    /**
     * @internal will change to protected
     */
    public function getTranslationRelationKey(): string
    {
        if ($this->translationForeignKey) {
            return $this->translationForeignKey;
        }

        return $this->getForeignKey();
    }

    public function translation(): HasOne
    {
        return $this
            ->hasOne($this->getTranslationModelName(), $this->getTranslationRelationKey())
            ->ofMany([
                $this->getTranslationRelationKey() => 'max',
            ], function (Builder $query): void {
                $query->where($this->getLocaleKey(), $this->localeOrFallback());
            });
    }

    public function translations(): HasMany
    {
        return $this->hasMany($this->getTranslationModelName(), $this->getTranslationRelationKey());
    }
    /**
     * Create a new model instance for a related model.
     *
     * @param  string  $class
     * @return mixed
     */
    protected function newRelatedInstance($class)
    {
        $instance = parent::newRelatedInstance($class);
        if ($class === ModelTranslatable::class) {
            $tableTranslatable = $this->getTable() . '_translations';
            $instance->setTable($tableTranslatable);
            if (isset($this->translatedAttributes)) {
                $instance->fillable($this->translatedAttributes);
            }
        }
        return $instance;
    }
    protected function localeOrFallback()
    {
        return $this->useFallback() && !$this->translations()->where($this->getLocaleKey(), $this->locale())->exists()
            ? $this->getFallbackLocale()
            : $this->locale();
    }
}
