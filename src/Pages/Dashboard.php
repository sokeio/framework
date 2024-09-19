<?php

namespace Sokeio\Pages;

use Sokeio\Concerns\WithPageAdminGuest;
use Sokeio\Theme;

class Dashboard extends \Sokeio\Page
{
    use WithPageAdminGuest;
    public $test;
    public static function pageUrl()
    {
        return '/';
    }
    public function change()
    {
        $this->test = 'changed';
    }
    public function render()
    {
        Theme::js('window.sokeio.Application.run({
        render(){
            return `<div>1[demo::test/]2<span>test</span> 3[demo::test/]4 </div>`;
        }
        
        },"#test-app");');
        return <<<html
        <div>
        <div id="test-app"></div>
        </div>
html;
    }
}
