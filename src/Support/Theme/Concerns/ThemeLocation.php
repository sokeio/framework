<?php

namespace Sokeio\Support\Theme\Concerns;

trait ThemeLocation
{
    private $location = [];
    public function renderLocation($location)
    {
        if (isset($this->location[$location])) {
            foreach ($this->location[$location] as $callback) {
                if (is_callable($callback)) {
                    $callback();
                }
            }
        }
        if (setting('SOKEIO_THEME_LOCATION_SHOW_DEBUG')) {
            echo "<div>$location</div>";
        }
    }
    public function location($location, $callback)
    {
        if (!isset($this->location[$location])) {
            $this->location = [];
        }
        $this->location[$location][] = $callback;
    }
}
