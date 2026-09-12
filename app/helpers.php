<?php

if (!function_exists('rupiah')) {
    function rupiah($amount): string
    {
        return 'Rp '.number_format((float) $amount, 0, ',', '.');
    }
}