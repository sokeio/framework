<?php

$formField = $column->getFormField();
?>
@error($formField)
    <span class="error">{{ $message }}</span>
@enderror
