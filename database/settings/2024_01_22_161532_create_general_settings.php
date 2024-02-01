<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.employee_directory', 'employee');
        $this->migrator->add('general.time_off', 'timeoff');
        $this->migrator->add('general.time_sheet', 'timesheet');
        $this->migrator->add('general.hr_tool', 'hrtool');
        $this->migrator->add('general.help_desk', 'helpdesk');
        $this->migrator->add('general.recruitment', 'recruitments');
        $this->migrator->add('general.insights', 'insights');
        $this->migrator->add('general.lms', 'lms');
        $this->migrator->add('general.attendance', 'attendanceRecord');
        $this->migrator->add('general.forum', 'forum');
        $this->migrator->add('general.finance', 'finance');
        $this->migrator->add('general.settings', 'settings');
        $this->migrator->add('general.paid_break', 'paidBreak');
        $this->migrator->add('general.unpaid_break', 'unpaidBreak');
        $this->migrator->add('general.app_notification', 'appNotification');
        $this->migrator->add('general.email_notification', 'emailNotification');
        $this->migrator->add('general.company_logo', '/logo-light.svg');
        $this->migrator->add('general.favicon', '/logo-light.svg');
        
    }
};
