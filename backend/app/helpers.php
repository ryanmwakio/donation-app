<?php
if (! function_exists('generate_token')) {
    function generate_token($model, $resource = null)
    {
        $resource = $resource ?? plural_from_model($model);

        return route("{$resource}.show", $model);
    }
}
