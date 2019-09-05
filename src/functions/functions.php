<?php

if (!function_exists('to_cents')) {
    function to_cents($integer = 0)
    {
        return $integer * 100;
    }
}
