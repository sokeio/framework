<?php

namespace Sokeio\UI\Common\Concerns;

trait DivAlert
{
    public function alert($type = 'success')
    {
        return $this->className('alert alert-' . $type)->attr('role', 'alert');
    }
    public function alertDanger()
    {
        return $this->alert('danger');
    }
    public function alertSuccess()
    {
        return $this->alert('success');
    }
    public function alertWarning()
    {
        return $this->alert('warning');
    }
    public function alertInfo()
    {
        return $this->alert('info');
    }
}
