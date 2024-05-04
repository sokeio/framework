<?php

namespace Sokeio\Platform;

use Sokeio\Laravel\Hook\ActionHook;
use Sokeio\Events\PlatformChanged;
use Sokeio\Facades\Platform;
use Illuminate\Support\Facades\Request;
use Sokeio\Platform\Concerns\WithThemeProcess;

class ThemeManager extends ActionHook
{
    use \Sokeio\Concerns\WithSystemExtend;
    use WithThemeProcess;
    private $isHtmlAjax = true;
    private $isSiteInfo = null;
    public function DisableHtmlAjax(): self
    {
        $this->isHtmlAjax = false;
        return $this;
    }
    public function enableHtmlAjax(): self
    {
        $this->isHtmlAjax = true;
        return $this;
    }
    private $isAdmin = true;
    public function currentAdmin()
    {
        return $this->isAdmin;
    }
    private $layoutDefault = '';
    public function layoutDefault()
    {
        if (!$this->layoutDefault) {
            $this->layoutDefault = applyFilters('PLATFORM_THEME_LAYOUT_DEFAULT', 'default');
        }
        return $this->layoutDefault;
    }

    public function isRegisterBeforeLoad()
    {
        return false;
    }
    public function sitebarAdmin($flg = true)
    {
        session(['sitebarAdmin' => $flg ? 'true' : 'false']);
    }
    public function isSitebarMini()
    {
        return session('sitebarAdmin', 'false') === 'true';
    }
    public function getName()
    {
        return "theme";
    }
    public function setupOption()
    {
        $site = $this->SiteDataInfo();
        $site?->TriggerEvent('setupOption');
    }
    private $layout;
    public function setLayout($layout)
    {
        $this->layout = $layout;
    }
    public function AdminId()
    {
        return PlatformStatus::key(PLATFORM_THEME_ADMIN)->getFirstOrDefault('admin');
    }
    public function SiteId()
    {
        return PlatformStatus::key(PLATFORM_THEME_WEB)->getFirstOrDefault('none');
    }
    public function AdminDataInfo()
    {
        return $this->find($this->AdminId());
    }
    public function SiteDataInfo()
    {
        return $this->find($this->SiteId());
    }
    public function checkSite()
    {
        if ($this->isSiteInfo == null) {
            $this->isSiteInfo = $this->find($this->SiteId()) == null ? false : true;
        }
        return $this->isSiteInfo;
    }
    public function getStatusData($theme)
    {
        if (isset($theme['admin']) && $theme['admin'] == 1) {
            return $this->AdminId() == $theme->getId() ? 1 : 0;
        } else {
            return $this->SiteId() == $theme->getId() ? 1 : 0;
        }
    }

    public function setStatusData($theme)
    {
        if (isset($theme['admin']) && $theme['admin'] === 1) {
            $site = $this->AdminDataInfo();

            $site?->TriggerEvent('deactivate');
            $theme?->TriggerEvent('activate');
            PlatformStatus::key(PLATFORM_THEME_ADMIN)->active($theme->getId(), true);
            $theme?->TriggerEvent('activated');
            $site?->TriggerEvent('deactivated');
        } else {
            $site = $this->SiteDataInfo();

            $site?->TriggerEvent('deactivate');
            $theme?->TriggerEvent('activate');
            PlatformStatus::key(PLATFORM_THEME_WEB)->active($theme->getId(), true);
            $theme?->TriggerEvent('activated');
            $site?->TriggerEvent('deactivated');
        }
        PlatformChanged::dispatch($theme);
        Platform::makeLink();
    }

    public function isHtml()
    {
        if ($this->isHtmlAjax) {
            return Request::ajax();
        }
        return false;
    }
    public function layout($layout = '')
    {
        if ($layout != '') {
            $this->setLayout($layout);
        }
        if ($this->isHtml()) {
            return 'sokeio::html';
        }
        if ($this->layout != '') {
            $layout = $this->layout;
        }
        if ($layout == '') {
            $layout = $this->layoutDefault();
        }
        return 'theme::layouts.' . $layout;
    }
    protected $dataActive;
    public function themeCurrent()
    {
        if (!isset($this->dataActive) || !$this->dataActive) {
            $site_name = '';
            if (sokeioIsAdmin()) {
                $site_name = applyFilters(PLATFORM_THEME_FILTER_LAYOUT, $this->AdminId(), 1);
            } else {
                $site_name = applyFilters(PLATFORM_THEME_FILTER_LAYOUT, $this->SiteId(), 0);
            }
            $this->dataActive = $this->findAndRegister($site_name);
            if ($this->dataActive == null) {
                $this->dataActive = $this->findAndRegister(env('PLATFORM_THEME_DEFAULT', 'none'));
            }
        }
        return $this->dataActive;
    }
    public function reTheme()
    {
        $this->dataActive = null;
        $this->isSiteInfo = null;
        $this->themeCurrent();
    }
}
