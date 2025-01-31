<?php

namespace Sokeio\Livewire\Concerns;

use Illuminate\Console\View\Components\Alert;
use Sokeio\Enums\AlertPosition;
use Sokeio\Enums\AlertType;

trait WithLivewireDispatch
{
    private const LIVEWIRE_MESSAGE = 'sokeio_message';
    private const LIVEWIRE_CLOSE = 'sokeio_close';
    private const LIVEWIRE_CALL_FUNC = 'sokeio_call_func';
    private const LIVEWIRE_REFRESH = 'sokeio_refresh';
    private const LIVEWIRE_REFRESH_PARENT = 'sokeio_refresh_parent';
    private const LIVEWIRE_REFRESH_PAGE = 'sokeio_refresh_page';
    private const LIVEWIRE_OPEN_TAB = 'sokeio_open_tab';
    private const LIVEWIRE_CLOSE_TAB = 'sokeio_close_tab';




    protected function sendMessageToClient($type, $payload = [])
    {
        $this->dispatch('sokeio::dispatch', [
            'type' => $type,
            'payload' => $payload
        ]);
    }
    public function alert(
        $message,
        $title = null,
        $messageType = AlertType::SUCCESS,
        $position = AlertPosition::TOP_CENTER,
        $timeout = 5000
    ) {
        $this->sendMessage($message, [
            'messageType' => $messageType,
            'title' => $title,
            'position' => $position,
            'timeout' => $timeout
        ]);
    }
    public function sendMessage(
        $message,
        $option = []
    ) {
        $this->sendMessageToClient(self::LIVEWIRE_MESSAGE, [
            'message' => $message,
            ...$option
        ]);
    }
    public function refreshPage($url = null)
    {
        $this->sendMessageToClient(self::LIVEWIRE_REFRESH_PAGE, [
            'url' => $url
        ]);
    }
    public function refreshToId($id)
    {
        $this->sendMessageToClient(self::LIVEWIRE_REFRESH, [
            'wireTargetId' => $id,
        ]);
    }
    public function refreshParentToChildId($id)
    {
        $this->sendMessageToClient(self::LIVEWIRE_REFRESH_PARENT, [
            'wireTargetId' => $id,
        ]);
    }
    public function refreshRef()
    {
        $this->refreshToId($this->getRefId());
    }
    public function refreshMe()
    {
        $this->refreshToId($this->getId());
    }
    public function refreshParentMe()
    {
        $this->refreshParentToChildId($this->getId());
    }
    public function callFunc($func, $option = [])
    {
        $this->sendMessageToClient(self::LIVEWIRE_CALL_FUNC, [
            'func' => $func,
            'option' => $option
        ]);
    }
    public function sokeioClose()
    {
        $this->sendMessageToClient(self::LIVEWIRE_CLOSE, []);
    }
    public function refreshDashboard()
    {
        $this->dispatch('sokeio:refresh-dashboard');
    }
    // public function callFuncByName($name, $func, $params = [])
    // {
    //     $this->callFunc($func, [
    //         'component' => $name,
    //         'params' => $params
    //     ]);
    // }
    public function callFuncById($id, $func, $params = [])
    {
        $this->callFunc($func, [
            'id' => $id,
            'params' => $params
        ]);
    }
    public function callFuncByRef($func, $params = [])
    {
        $this->callFuncById($this->getRefId(), $func, $params);
    }
    public function callFuncByParent($func, $params = [])
    {
        $this->callFunc($func, [
            'id' => $this->getId(),
            'parent' => true,
            'params' => $params
        ]);
    }
    public function openTab($url, $callbackClose = null)
    {
        $this->sendMessageToClient(self::LIVEWIRE_OPEN_TAB, [
            'url' => $url,
            'callbackClose' => $callbackClose
        ]);
    }
    public function closeTab()
    {
        $this->sendMessageToClient(self::LIVEWIRE_CLOSE_TAB, []);
    }
}
