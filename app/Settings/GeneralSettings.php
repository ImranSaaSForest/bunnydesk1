<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{

    public string $employee_directory;
    public string $time_off;
    public string $time_sheet;
    public string $hr_tool;
    public string $help_desk;
    public string $recruitment;
    public string $insights;
    public string $lms;
    public string $attendance;
    public string $forum;
    public string $finance;
    public string $settings;
    public string $paid_break;
    public string $unpaid_break;
    public string $app_notification;
    public string $email_notification;
    public string $company_logo;
    public string $favicon;


    public static function group(): string
    {
        return 'general';
    }
}