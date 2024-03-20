<?php

namespace Sokeio\Platform\Concerns;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

trait WithPlatformAdmin
{
    private const KEY_ADMIN_SESSION = 'admin_id-ba664433-be1d-45bc-ab25-4a196c19c387';
    private $isThemeAdmin = null;
    private $keyAdmin = null;
    private $keyAdminEncrypted = null;
    public function cleanThemeAdmin()
    {
        $this->isThemeAdmin = null;
        if (session(self::KEY_ADMIN_SESSION) === null) {
            session([self::KEY_ADMIN_SESSION => Str::random(24)]);
        }
        $this->keyAdmin = session(self::KEY_ADMIN_SESSION);
        $this->keyAdminEncrypted = Crypt::encryptString($this->keyAdmin);
    }
    public function isThemeAdmin()
    {
        if ($this->isThemeAdmin !== null) {
            return $this->isThemeAdmin;
        }
        $is_admin = false;
        $arrMiddleware = [];
        if (Request()->route()?->gatherMiddleware()) {
            $arrMiddleware = Request()->route()->gatherMiddleware();
            $is_admin = in_array(\Sokeio\Middleware\ThemeAdmin::class,  $arrMiddleware);
        }
        $url_admin = adminUrl();
        if (request()->segment(1) === $url_admin || $url_admin === '') {
            $is_admin = true;
        }
        if (isLivewireRequest() && isset(request()->get('components')[0]['snapshot'])) {
            $snapshot = request()->get('components')[0]['snapshot'];
            $snapshot = json_decode($snapshot, true);
            if (
                isset($snapshot['data']['soIsAdmin'])
            ) {
                $admin_id = Crypt::decryptString($snapshot['data']['soIsAdmin']);
                $is_admin = $admin_id === $this->keyAdmin;
            }
        }
        $is_admin = applyFilters(SOKEIO_IS_ADMIN, $is_admin);
        $this->isThemeAdmin = ($is_admin === true) ? true : false;
        return $this->isThemeAdmin;
    }
    public function keyAdmin()
    {
        return $this->keyAdminEncrypted;
    }
}
