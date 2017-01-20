<?php

if (! function_exists('formatDate')) {
    function formatDate($date, $format = 'F jS, Y')
    {
        return date($format, strtotime($date));
    }
}

if (! function_exists('set_active')) {
    function set_active($routeNames, $active = 'is-active')
    {
        return in_array(\Route::currentRouteName(), $routeNames) ? $active : '';
    }
}

if (! function_exists('auto_p')) {
    function auto_p($string)
    {
        return '<p>'.str_replace("\n", "</p>\n<p>", $string)."</p>\n";
    }
}

if (! function_exists('build_resource_backport')) {
    function build_resource_backport($name, array $only = [], array $except = [])
    {
        $routeNames = [];
        $methods = ['create', 'show', 'store', 'destroy', 'update', 'edit', 'index'];
        $methods = array_combine($methods, $methods);
        if (! empty($only)) {
            $methods = array_only($methods, $only);
        }
        if (! empty($except)) {
            $methods = array_except($methods, $except);
        }
        foreach ($methods as $action) {
            $routeNames[$action] = $name.'.'.$action;
        }

        return $routeNames;
    }
}