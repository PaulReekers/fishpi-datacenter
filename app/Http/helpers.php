<?php

/**
 * Add .class if current page matches the $path
 * @param string $path   slug/link
 * @param string $active class name
 */
function set_active($path, $active = 'active')
{
    return Request::is($path) ? $active : '';
}
