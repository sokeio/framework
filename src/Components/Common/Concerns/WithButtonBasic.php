<?php

namespace Sokeio\Components\Common\Concerns;

use Sokeio\Components\UI;

trait WithButtonBasic
{
    use WithTitle, WithName;
    public function icon($icon = true): static
    {
        return $this->setKeyValue('icon', $icon);
    }
    public function getIcon()
    {
        return $this->getValue('icon');
    }
    public function buttonLink($buttonLink = true): static
    {
        return $this->setKeyValue('buttonLink', $buttonLink);
    }
    public function getButtonLink()
    {
        return $this->getValue('buttonLink', false);
    }
    public function link($link): static
    {
        return $this->setKeyValue('link', $link);
    }
    public function getLink()
    {
        return $this->getValue('link');
    }
    public function buttonSize($buttonSize): static
    {
        return $this->setKeyValue('buttonSize', $buttonSize);
    }
    public function getButtonSize()
    {
        return $this->getValue('buttonSize');
    }
    public function large(): static
    {
        return $this->buttonSize(UI::BUTTON_LARGE);
    }
    public function small(): static
    {
        return $this->buttonSize(UI::BUTTON_SMALL);
    }

    public function route($name, $paramOrcallback = [])
    {
        return $this->link(function ($item, $manager) use ($name, $paramOrcallback) {
            if ($paramOrcallback && is_callable($paramOrcallback)) {
                $paramOrcallback = call_user_func($paramOrcallback, $item->getDataItem(), $item, $manager);
            }
            return route($name, $paramOrcallback);
        });
    }
    public function getClassButton()
    {
        if ($this->getButtonLink()) {
            return '';
        }
        $classButton = 'btn';
        if ($buttonColor = $this->getButtonColor()) {
            $classButton = $classButton . ' btn' . $buttonColor;
        } else {
            $classButton = $classButton . ' btn-primary';
        }
        if ($buttonSize = $this->getButtonSize()) {
            $classButton = $classButton . ' btn-' . $buttonSize;
        }
        return $classButton;
    }
}
