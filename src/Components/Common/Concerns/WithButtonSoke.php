<?php

namespace Sokeio\Components\Common\Concerns;

trait WithButtonSoke
{
    public function ModalRoute($name, $paramOrcallback = []): static
    {
        return $this->ModalUrl(function ($item, $manager) use ($name, $paramOrcallback) {
            if ($paramOrcallback && is_callable($paramOrcallback)) {
                $paramOrcallback = call_user_func($paramOrcallback, $item->getDataItem(), $item, $manager);
            }
            return route($name, $paramOrcallback);
        });
    }
    public function ModalUrl($ModalUrl): static
    {
        return $this->setKeyValue('ModalUrl', $ModalUrl);
    }
    public function getModalUrl()
    {
        return $this->getValue('ModalUrl');
    }
    public function ModalSize($ModalSize): static
    {
        return $this->setKeyValue('ModalSize', $ModalSize);
    }
    public function getModalSize()
    {
        return $this->getValue('ModalSize');
    }
    public function ModalSmall()
    {
        return $this->ModalSize('modal-sm');
    }
    public function ModalLarge()
    {
        return $this->ModalSize('modal-lg');
    }
    public function ModalExtraLarge()
    {
        return $this->ModalSize('modal-xl');
    }
    public function ModalFullscreen()
    {
        return $this->ModalSize('modal-fullscreen');
    }
    public function ModalFullscreenSM()
    {
        return $this->ModalSize('modal-fullscreen-sm-down');
    }
    public function ModalFullscreenMD()
    {
        return $this->ModalSize('modal-fullscreen-md-down');
    }
    public function ModalFullscreenLG()
    {
        return $this->ModalSize('modal-fullscreen-lg-down');
    }
    public function ModalFullscreenXL()
    {
        return $this->ModalSize('modal-fullscreen-xl-down');
    }

    public function ModalFullscreenXXL()
    {
        return $this->ModalSize('modal-fullscreen-xxl-down');
    }

    public function ModalTitle($ModalTitle): static
    {
        return $this->setKeyValue('ModalTitle', $ModalTitle);
    }
    public function getModalTitle()
    {
        return $this->getValue('ModalTitle');
    }
    private $wireConfirm;
    public function Confirm($message, $title, $yes = 'Yes', $no = 'No'): static
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
            if ($size = $this->getModalSize()) {
                $buttonAtrr .= ' sokeio:modal-size="' . $size . '" ';
            }
            if ($title = $this->getModalTitle()) {
                $buttonAtrr .= ' sokeio:modal-title="' . $title . '" ';
            }
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
