<?php

namespace SokeioTheme\Admin\Page;

use Sokeio\Attribute\PageInfo;

#[PageInfo(
    admin: true,
    auth: true,
    url: '/',
    route: 'dashboard',
    title: 'Dashboard',
    menuTitle: 'Dashboard',
    icon: 'ti ti-dashboard ',
    menu: true,
    sort: 0
)]
class Dashboard extends \Sokeio\Dashboard\DashboardPage {}
