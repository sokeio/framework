<?php

namespace Sokeio\Support\Livewire\Concerns;

trait WithLivewireDispatch
{
    private const LIVEWIRE_MESSAGE = 'sokeio_message';
    private const LIVEWIRE_CLOSE = 'sokeio_close';
    private const LIVEWIRE_FUNCTION = 'sokeio_function';
    protected function sendMessageToClient($type, $payload = [])
    {
        $this->dispatch('sokeio::dispatch', [
            'type' => $type,
            'payload' => $payload
        ]);
    }
    public function alert($message)
    {
        $this->sendMessage($message);
    }
    public function sendMessage(
        $message,
        $option = []
    ) {
        $this->sendMessageToClient(self::LIVEWIRE_MESSAGE, [
            'message' => $message,
            'option' => $option
        ]);
    }
    public function callFunc($func, $option = [])
    {
        $this->sendMessageToClient(self::LIVEWIRE_FUNCTION, [
            'func' => $func,
            'option' => $option
        ]);
    }
    public function sokeioClose()
    {
        $this->sendMessageToClient(self::LIVEWIRE_CLOSE, []);
    }
}
