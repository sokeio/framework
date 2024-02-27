<?php

namespace Sokeio\Components\Common\Concerns;


trait WithButtonBasic
{
    public function Icon($Icon = true): static
    {
        return $this->setKeyValue('Icon', $Icon);
    }
    public function getIcon()
    {
        return $this->getValue('Icon');
    }
    public function ButtonLink($ButtonLink = true): static
    {
        return $this->setKeyValue('ButtonLink', $ButtonLink);
    }
    public function getButtonLink()
    {
        return $this->getValue('ButtonLink', false);
    }
    public function Title($Title)
    {
        return $this->setKeyValue('Title', $Title);
    }
    public function getTitle()
    {
        return $this->getValue('Title');
    }
    public function Name($Name): static
    {
        return $this->setKeyValue('Name', $Name);
    }
    public function getName()
    {
        return $this->getValue('Name');
    }
    public function Link($Link): static
    {
        return $this->setKeyValue('Link', $Link);
    }
    public function getLink()
    {
        return $this->getValue('Link');
    }
    public function ButtonSize($ButtonSize): static
    {
        return $this->setKeyValue('ButtonSize', $ButtonSize);
    }
    public function getButtonSize()
    {
        return $this->getValue('ButtonSize');
    }
    public function Large(): static
    {
        return $this->ButtonSize('large');
    }
    public function Small(): static
    {
        return $this->ButtonSize('sm');
    }

    public function Route($name, $paramOrcallback = [])
    {
        return $this->Link(function ($item, $manager) use ($name, $paramOrcallback) {
            if ($paramOrcallback && is_callable($paramOrcallback)) {
                $paramOrcallback = call_user_func($paramOrcallback, $item->getDataItem(), $item, $manager);
            }
            return route($name, $paramOrcallback);
        });
    }
    public function getClassButton()
    {
        if ($this->getButtonLink()) return '';
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
