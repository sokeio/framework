<?php

namespace Sokeio\UI;
/*
.so-modal-overlay {
    --tblr-backdrop-zindex: 1050;
    --tblr-backdrop-bg: #182433;
    --tblr-backdrop-opacity: 0.75;
    position: fixed;
    top: 0;
    left: 0;
    z-index: var(--tblr-backdrop-zindex);
    width: 100vw;
    height: 100vh;
    background-color: var(--tblr-backdrop-bg);
    opacity: var(--tblr-backdrop-opacity);

    &~.so-modal-overlay {
        z-index: var(--tblr-backdrop-zindex) - 1;
        display: none;
    }
}

.so-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1056 !important;
    animation: fadein 0.5s;
    .so-modal-dialog {
        width: 100%;
        flex: none;
        position: relative;
        width: auto;
        padding: 3px;
        margin: 65px auto 0 auto;
        pointer-events: auto;
        min-height: 50px;

        .so-modal-loader {
            height: 100%;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #eee;
        }

        .so-modal-content-error {
            height: 100%;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            background: #b55555;
            color: #fff;
            font-size: medium;
            min-height: 120px;
        }

        .so-modal-close {
            text-decoration: none;

            &:hover {
                color: #a29b9b;
            }

            &::before {
                cursor: pointer;
                border: 1px solid #1F5968;
                background: #1F5968;
                padding: 0px 10px;
                font-size: 17px;
                position: absolute;
                right: 5px;
                top: 5px;
                color: #fff;
                border-radius: 2px;
                content: "\00d7";
                z-index: 999999;
            }
        }

        .so-modal-content {
            position: relative;
            display: flex;
            flex-direction: column;
            width: 100%;
            // background-color: #ffffff;
            background-clip: padding-box;
            border-radius: 4px;
            box-shadow: #1f596837 0 2px 4px 0;
            outline: 0;
            // overflow: hidden;
            height: 100%;



            .so-modal-header {
                display: flex;
                align-items: flex-start;
                justify-content: space-between;
                padding-top: 10px;
                padding-left: 5px;

                .so-modal-icon {
                    font-size: 20px;
                    color: #1F5968;
                }

                .so-modal-title {
                    display: flex;
                    align-items: center;
                    color: #1F5968;
                    padding-left: 5px;
                    flex: 1;
                }
            }

            .so-modal-body {
                position: relative;
                flex: 1 1 auto;
                min-height: 50px;
            }

            .so-modal-footer {
                display: flex;
                align-items: flex-start;
                justify-content: space-between;
            }

        }
    }

    &.so-modal-size-full {
        .so-modal-dialog {
            width: 100%;
            max-width: 100%;
            height: 100%;
            margin: 0;
        }
    }

    &.so-modal-size-auto {
        .so-modal-dialog {
            width: auto;
            max-width: 100%;
            height: 100%;
            margin: 0;
        }
    }

    &.so-modal-size-fullscreen {
        .so-modal-dialog {
            width: 100vw;
            max-width: none;
            height: 100%;
            margin: 0;
        }
    }

    &.so-modal-size-fullscreen-xxl-down {
        @media (max-width: 1400px) {
            .so-modal-dialog {
                width: 100vw;
                max-width: none;
                height: 100%;
                margin: 0;
            }
        }
    }

    &.so-modal-size-fullscreen-xl-down {
        @media (max-width: 1200px) {
            .so-modal-dialog {
                width: 100vw;
                max-width: none;
                height: 100%;
                margin: 0;
            }
        }
    }

    &.so-modal-size-fullscreen-lg-down {
        @media (max-width: 992px) {
            .so-modal-dialog {
                width: 100vw;
                max-width: none;
                height: 100%;
                margin: 0;
            }
        }
    }

    &.so-modal-size-fullscreen-md-down {
        @media (max-width: 768px) {
            .so-modal-dialog {
                width: 100vw;
                max-width: none;
                height: 100%;
                margin: 0;
            }
        }
    }

    &.so-modal-size-fullscreen-sm-down {
        @media (max-width: 576px) {
            .so-modal-dialog {
                width: 100vw;
                max-width: none;
                height: 100%;
                margin: 0;
            }
        }
    }

    &.so-modal-size-lg {
        .so-modal-dialog {
            max-width: 720px;
        }
    }

    &.so-modal-size-xl {
        .so-modal-dialog {
            max-width: 1140px;
        }
    }

    &.so-modal-size-xxl {
        .so-modal-dialog {
            max-width: 1320px;
        }
    }

    &.so-modal-size-sm {
        .so-modal-dialog {
            max-width: 380px;
        }
    }

    &.so-modal-size-md {
        .so-modal-dialog {
            max-width: 500px;
        }
    }
}*/

class ModalUI extends BaseUI
{
    public function initUI()
    {
        $this->className('sokeio-modal-ui');
        $this->render(function () {
            if (!$this->getIcon()) {
                $this->icon('ti ti-dashboard');
            }
        });
    }
    public function title($title)
    {
        return $this->vars('title', $title);
    }
    public function rightUI($ui)
    {
        return $this->child($ui, 'right');
    }
    public function beforeUI($ui)
    {
        return $this->child($ui, 'before');
    }
    public function afterUI($ui)
    {
        return $this->child($ui, 'after');
    }
    public function size($size)
    {
        return $this->attr('data-modal-size', $size);
    }
    public function autoSize()
    {
        return $this->size('auto');
    }
    public function lgSize()
    {
        return $this->size('lg');
    }
    public function smSize()
    {
        return $this->size('sm');
    }
    public function mdSize()
    {
        return $this->size('md');
    }
    public function xlSize()
    {
        return $this->size('xl');
    }
    public function xxlSize()
    {
        return $this->size('xxl');
    }
    public function fullscreenSize()
    {
        return $this->size('fullscreen');
    }

    public function fullscreenXlSize()
    {
        return $this->size('fullscreen-xl-down');
    }

    public function fullscreenLgSize()
    {
        return $this->size('fullscreen-lg-down');
    }

    public function fullscreenMdSize()
    {
        return $this->size('fullscreen-md-down');
    }

    public function fullscreenSmSize()
    {
        return $this->size('fullscreen-sm-down');
    }
    public function skipOverlayClose()
    {
        return $this->attr('data-skip-overlay-close', true);
    }
    public function hideButtonClose()
    {
        return $this->attr('data-hide-button-close', true);
    }
    public function view()
    {
        $this->className('position-relative');
        $attr = $this->getAttr();
        $title = $this->getVar('title', '', true);
        $icon = $this->getIcon();
        return <<<HTML
        <div {$attr}>
            <div wire:loading class="position-absolute top-50 start-50 translate-middle">
                <span  class="spinner-border  text-blue  " role="status"></span>
            </div>
            <div class="row align-items-center sokeio-modal-header">
                <div class="col">
                    {$icon}
                    <span>{$title}</span>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <div class="d-flex align-items-center">
                    {$this->renderChilds('right')}
                    </div>
                </div>
            </div>
            <div class="sokeio-modal-body p-1">
                {$this->renderChilds('before')}
                {$this->renderChilds()}
                {$this->renderChilds('after')}
            </div>
        </div>
    HTML;
    }
}
