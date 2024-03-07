<?php

namespace Sokeio\Components\Common\Concerns;

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
        return $this->modalSize('modal-sm');
    }
    public function modalLarge()
    {
        return $this->modalSize('modal-lg');
    }
    public function modalExtraLarge()
    {
        return $this->modalSize('modal-xl');
    }
    public function modalFullscreen()
    {
        return $this->modalSize('modal-fullscreen');
    }
    public function modalFullscreenSM()
    {
        return $this->modalSize('modal-fullscreen-sm-down');
    }
    public function modalFullscreenMD()
    {
        return $this->modalSize('modal-fullscreen-md-down');
    }
    public function modalFullscreenLG()
    {
        return $this->modalSize('modal-fullscreen-lg-down');
    }
    public function modalFullscreenXL()
    {
        return $this->modalSize('modal-fullscreen-xl-down');
    }

    public function modalFullscreenXXL()
    {
        return $this->modalSize('modal-fullscreen-xxl-down');
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
