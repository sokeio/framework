<?php

namespace BytePlatform\Livewire;

use BytePlatform\Component;
use BytePlatform\Dashboard as BytePlatformDashboard;
use Livewire\Attributes\Computed;

class Dashboard extends Component
{
    public $widgets = [];
    public $widgetKey = 0;
    protected function getListeners()
    {
        return [
            ...parent::getListeners(),
            'DashboardRefreshData'  => '__loadData',
        ];
    }
    #[Computed]
    public function locked()
    {
        return !checkPermission('admin.widget-setting');
    }
    public function mount()
    {
        $this->__loadData();
    }
    public function __loadData()
    {
        $this->widgets = BytePlatformDashboard::GetWidgetIdActives();
        $this->widgetKey++;
    }

    public function render()
    {
        page_title('Dashboard');
        return view('byte::dashboard', [
            'locked' => $this->locked
        ]);
    }
}
