<?php

namespace Sokeio\Components\Common\Concerns;

use Sokeio\Components\UI;

trait WithButtonSoke
{
    public function modalRoute($name, $paramOrcallback = []): static
    {
        return $this->modalUrl(function ($item, $manager) use ($name, $paramOrcallback) {
            if ($paramOrcallback && is_callable($paramOrcallback)) {
                $paramOrcallback = call_user_func($paramOrcallback, $item->getDataItem(), $item, $manager);
            }
            return route($name, $paramOrcallback);
        });
    }
    public function modalUrl($modalUrl): static
    {
        return $this->setKeyValue('modalUrl', $modalUrl);
    }
    public function getModalUrl()
    {
        return $this->getValue('modalUrl');
    }
    public function modalSize($modalSize): static
    {
        return $this->setKeyValue('modalSize', $modalSize);
    }
    public function getModalSize()
    {
        return $this->getValue('modalSize');
    }
    public function modalSmall()
    {
        return $this->modalSize(UI::MODAL_SMALL);
    }
    public function modalLarge()
    {
        return $this->modalSize(UI::MODAL_LARGE);
    }
    public function modalExtraLarge()
    {
        return $this->modalSize(UI::MODAL_FULLSCREEN_XXL);
    }
    public function modalFullscreen()
    {
        return $this->modalSize(UI::MODAL_FULLSCREEN);
    }
    public function modalFullscreenSM()
    {
        return $this->modalSize(UI::MODAL_FULLSCREEN_SM);
    }
    public function modalFullscreenMD()
    {
        return $this->modalSize(UI::MODAL_FULLSCREEN_MD);
    }
    public function modalFullscreenLG()
    {
        return $this->modalSize(UI::MODAL_FULLSCREEN_LG);
    }
    public function modalFullscreenXL()
    {
        return $this->modalSize(UI::MODAL_FULLSCREEN_XL);
    }

    public function modalFullscreenXXL()
    {
        return $this->modalSize(UI::MODAL_FULLSCREEN_XXL);
    }

    public function modalTitle($modalTitle): static
    {
        return $this->setKeyValue('modalTitle', $modalTitle);
    }
    public function getModalTitle()
    {
        return $this->getValue('modalTitle');
    }
    private $wireConfirm;
    public function confirm($message, $title, $yes = 'Yes', $no = 'No'): static
    {
        $this->wireConfirm = [
            'message' => $message,
            'title' => $title,
            'yes' => $yes,
            'no' => $no,
        ];
        return $this;
    }
    public function getConfirm()
    {
        return $this->wireConfirm;
    }
    public function getSokeAttribute()
    {
        $buttonAtrr = '';
        if ($url = $this->getModalUrl()) {
            $buttonAtrr .= ' sokeio:modal="' . $url . '" ';
        }
        if ($size = $this->getModalSize()) {
            $buttonAtrr .= ' sokeio:modal-size="' . $size . '" ';
        }
        if ($title = $this->getModalTitle()) {
            $buttonAtrr .= ' sokeio:modal-title="' . $title . '" ';
        }
        if ([
            'message' => $message,
            'title' => $title,
            'yes' => $yes,
            'no' => $no,
        ] = $this->getConfirm()) {
            $buttonAtrr .= ' sokeio:confirm="' . $message . '" ';
            if ($yes) {
                $buttonAtrr .= ' sokeio:confirm-yes="' . $yes . '" ';
            }
            if ($no) {
                $buttonAtrr .= ' sokeio:confirm-no="' . $no . '" ';
            }
            if ($title) {
                $buttonAtrr .= ' sokeio:confirm-title="' . $title . '" ';
            }
        }
        return  $buttonAtrr;
    }
}
