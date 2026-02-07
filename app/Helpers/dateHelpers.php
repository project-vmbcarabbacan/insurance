<?php

use Carbon\Carbon;

if (!function_exists('lead_new_due_date')) {
    function lead_new_due_date()
    {
        return Carbon::now()->addMinutes(15)->toDateTimeString();
    }
}

if (!function_exists('format_fe_date_time')) {
    function format_fe_date_time(string $dateTime)
    {
        if (empty($dateTime)) {
            return null;
        }

        return Carbon::parse($dateTime)->format('d/m/Y h:i a');
    }
}
