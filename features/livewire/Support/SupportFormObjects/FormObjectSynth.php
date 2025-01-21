<?php

namespace Sokeio\Livewire\Support\SupportFormObjects;

use Livewire\Drawer\Utils;
use Livewire\Mechanisms\HandleComponents\Synthesizers\Synth;
use Livewire\Features\SupportAttributes\AttributeCollection;

use function Livewire\wrap;

class FormObjectSynth extends Synth
{
    public static $key = 'form2';

    public static function match($target)
    {
        return $target instanceof Form;
    }

    public function dehydrate($target, $dehydrateChild)
    {
        $data = $target->toArray();

        foreach ($data as $key => $child) {
            $data[$key] = $dehydrateChild($key, $child);
        }

        return [$data, ['class' => get_class($target)]];
    }

    public  function hydrate($data, $meta, $hydrateChild)
    {
        $form = new $meta['class']($this->context->component, $this->path);

        $callBootMethod = static::bootFormObject($this->context->component, $form, $this->path);

        foreach ($data as $key => $child) {
            //&& Utils::propertyIsTypedAndUninitialized($form, $key)
            if ($child === null) {
                continue;
            }

            $form->$key = $hydrateChild($key, $child);
        }

        $callBootMethod();

        return $form;
    }
    private function checkKeyInTarget($key, $target)
    {
        return isset($target->{$key});
    }
    public function set(&$target, $key, $value)
    {
        if ($value === null && $this->checkKeyInTarget($key, $target)) {
            unset($target->$key);
        } else {
            $target->$key = $value;
        }
    }

    public static function bootFormObject($component, $form, $path)
    {
        $component->mergeOutsideAttributes(
            AttributeCollection::fromComponent($component, $form, $path . '.')
        );

        return function () use ($form) {
            wrap($form)->boot();
        };
    }
}
