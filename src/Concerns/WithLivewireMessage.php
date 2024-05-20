<?php

namespace Sokeio\Concerns;



trait WithLivewireMessage
{
    use WithHelpers;

    public const MESSAGE_TYPE_SUCCESS = 'success';
    public const MESSAGE_TYPE_INFO = 'info';
    public const MESSAGE_TYPE_WARNING = 'warning';
    public const MESSAGE_TYPE_DANGER = 'text-bg-danger';
    
    public const MESSAGE_POSITION_TOP_RIGHT = 'top_right';
    public const MESSAGE_POSITION_TOP_CENTER = 'top_center';
    public const MESSAGE_POSITION_TOP_LEFT = 'top_left';
    public const MESSAGE_POSITION_MIDDLE_RIGHT = 'middle_right';
    public const MESSAGE_POSITION_MIDDLE_CENTER = 'middle_center';
    public const MESSAGE_POSITION_MIDDLE_LEFT = 'middle_left';
    public const MESSAGE_POSITION_BOTTOM_RIGHT = 'bottom_right';
    public const MESSAGE_POSITION_BOTTOM_CENTER = 'bottom_center';
    public const MESSAGE_POSITION_BOTTOM_LEFT = 'bottom_left';

    public function showMessage($message, $title=null, $icon=null, $type = 'success', $position = 'top-right', $option = [])
    {
        $this->showMessageByOption([
            ...$option ?? [],
            'message' => $message,
            'title' => $title,
            'icon' => $icon,
            'type' => $type,
            'position' => $position
        ]);
    }
    public function showMessageByOption($option)
    {
        $this->dispatch('sokeio::message', option: $option);
    }
}
