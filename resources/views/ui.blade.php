@php
    $ui->setViewFactory($__env);
    $html = $ui->render();
    if (!$html) {
        $html = '<div class="alert alert-warning">No UI found.</div>';
    }
@endphp
{!! $html !!}
