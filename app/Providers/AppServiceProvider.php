<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Facades\Filament;
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationItem;
use Filament\Navigation\NavigationGroup;
use App\Filament\Resources\Employee\EmployeeResource;
use App\Filament\Resources\Asset\AssetResource;
use App\Filament\Resources\File\FileResource;
use App\Filament\Resources\HelpDesk\TicketResource;
use App\Filament\Resources\Offboarding\OffboardingResource;
use App\Filament\Resources\Offboarding\OffboardingListResource;
use App\Filament\Resources\Onboarding\OnboardingResource;
use App\Filament\Resources\Onboarding\OnboardingListResource;
use App\Filament\Resources\Performance\GoalResource;
use App\Filament\Resources\Performance\ReviewResource;
use App\Filament\Resources\Reports\ReportResource;
use App\Filament\Resources\Settings\RoleResource;
use App\Filament\Resources\TimeOff\LeaveResource;
use App\Filament\Resources\TimeOff\MyCompanyResource;
use App\Filament\Resources\TimeOff\PendingRequestResource;
use App\Filament\Resources\Timesheet\ProjectResource;
use App\Filament\Resources\Timesheet\TimesheetResource;
use App\Models\Employee\Employee;
use App\Settings\GeneralSettings;
use FilamentQuickCreate\Facades\QuickCreate;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
     
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::share('generalSetting', app(GeneralSettings::class));
    }
}
