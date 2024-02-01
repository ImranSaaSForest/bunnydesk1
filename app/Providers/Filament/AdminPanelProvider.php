<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\EditProfile as AuthEditProfile;
use App\Filament\Pages\ManageFooter;
use App\Filament\Pages\ManageSettings;
use App\Filament\Resources\Asset\AssetResource;
use App\Filament\Resources\Attendance\AttendanceRecordResource;
use App\Filament\Resources\Chat\ChatRoomResource;
use App\Filament\Resources\DailyUpdate\DateResource;
use App\Filament\Resources\DailyUpdate\QuestionResource;
use App\Filament\Resources\Employee\EmployeeResource;
use App\Filament\Resources\FeedResource;
use App\Filament\Resources\File\FileResource;
use App\Models\Settings\Recruitment;
use App\Filament\Resources\Finance\BudgetResource;
use App\Filament\Resources\Finance\EmployeeTaxSlabResource;
use App\Filament\Resources\Finance\PayrunEmployeeAllowanceResource;
use App\Filament\Resources\Finance\PayrunEmployeeDeductionResource;
use App\Filament\Resources\Finance\PayrunEmployeeTaxValueResource;
use App\Filament\Resources\Finance\PayrunPaymentResource;
use App\Filament\Resources\Finance\PayrunResource;
use App\Filament\Resources\Finance\PendingReimbursementRequestResource;
use App\Filament\Resources\Finance\ReimbursementResource;
use App\Filament\Resources\Finance\ReportResource as FinanceReportResource;
use App\Filament\Resources\HelpDesk\TicketResource;
use App\Filament\Resources\Learning\CourseResource;
use App\Filament\Resources\Learning\EnrollmentResource;
use App\Filament\Resources\Learning\MyLearningResource;
use App\Filament\Resources\Offboarding\OffboardingListResource;
use App\Filament\Resources\Onboarding\OnboardingListResource;
use App\Filament\Resources\PatientResource;
use App\Filament\Resources\Performance\GoalResource;
use App\Filament\Resources\Performance\PerformanceGoalResource;
use App\Filament\Resources\Performance\ReviewResource;
use App\Filament\Resources\Recruitment\CandidateResource;
use App\Filament\Resources\Recruitment\JobResource;
use App\Filament\Resources\RecruitmentReportResource;
use App\Filament\Resources\Reports\ReportResource;
use App\Filament\Resources\Schedule\ScheduleResource;
use App\Filament\Resources\Settings\RoleResource;
use App\Filament\Resources\TimeOff\LeaveResource;
use App\Filament\Resources\TimeOff\MyCompanyResource;
use App\Filament\Resources\TimeOff\PendingRequestResource;
use App\Filament\Resources\Timesheet\ProjectResource;
use App\Filament\Resources\Timesheet\TimesheetResource;
use App\Http\Middleware\RedirectIfNotSetup;
use App\Models\Employee\Employee;
use App\Models\Employee\JobInfo;
use App\Models\Recruitment\Job;
use App\Settings\GeneralSettings;
use Filament\FontProviders\GoogleFontProvider;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Filament\Pages;
use Filament\Pages\Auth\EditProfile;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\View\View;
use Jeffgreco13\FilamentBreezy\BreezyCore;
use Saade\FilamentFullCalendar\FilamentFullCalendarPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        // $generalSetting = app(GeneralSettings::class);
    //    dd($generalSetting);
        return $panel
            ->default()
            ->databaseNotifications()
            ->databaseNotificationsPolling('30s')
            ->id('admin')
            ->path('/')
            ->passwordReset()
            ->login()
            ->colors([
                'primary' => Color::Blue,
            ])
            // ->favicon('/storage/'.$generalSetting->favicon)

            ->font('DM Sans', provider: GoogleFontProvider::class)
            ->viteTheme('resources/css/filament/admin/theme.css')

            ->profile(AuthEditProfile::class)
            // ->renderHook(
            //     name:'panels::user-menu.before',
            //     hook:fn (array $scopes): Blade => view('random', ['scopes' => $scopes]),
            //     scopes: ChatRoomResource::class,
            // )
      ->renderHook(
                name: 'panels::user-menu.before',
                hook: fn (): string => Blade::render('@livewire(\'chatimage\')'),
            )
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                // Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                \Shipu\WebInstaller\Middleware\RedirectIfNotInstalled::class,
                RedirectIfNotSetup::class,

            ])
            ->plugin(
                FilamentFullCalendarPlugin::make()
                    // ->schedulerLicenseKey()
                    ->selectable()
                    ->editable()
                // ->disableDragging()
                // ->timezone()
                // ->locale()
                // ->plugins()
                // ->config()
            )
            ->navigation(function (NavigationBuilder $builder): NavigationBuilder {
                $builderItems = [];
                if (auth()->user()->hasRole('Supervisor') || auth()->user()->hasRole('HR') || auth()->user()->hasRole('Super Admin')) {
                    $builderItems[] = NavigationItem::make('Dashboard')
                        ->icon('heroicon-o-home')
                        ->activeIcon('heroicon-s-home')
                        ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.pages.dashboard'))
                        ->url(route('filament.admin.pages.dashboard'));
                }

                $navigationGroups = [];
                $generalSetting = app(GeneralSettings::class);

                if ($generalSetting->employee_directory) {
                    $navigationGroups[] =  NavigationGroup::make('Employee Directory')
                        ->items([
                            NavigationItem::make('My Info')
                                ->icon('heroicon-o-user')
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.resources.employees.view')
                                    && request()->route('record') == auth()->user()->id)
                                ->url(route('filament.admin.resources.employees.edit', ['record' => auth()->user()->id, 'activeRelationManager' => 0])),

                            NavigationItem::make('Employees')
                                ->icon('heroicon-o-user-group')
                                ->isActiveWhen(fn (): bool => (request()->routeIs('filament.admin.resources.employees.index')
                                    || request()->routeIs('filament.admin.resources.employees.create')
                                    || (request()->routeIs('filament.admin.resources.employees.edit')
                                        && request()->route('record') != auth()->user()->id)
                                    || (request()->routeIs('filament.admin.resources.employees.view')
                                        && request()->route('record') != auth()->user()->id)))
                                ->url(route('filament.admin.resources.employees.index')),


                            NavigationItem::make('Org Chart')
                                ->icon('heroicon-o-chart-pie')
                                ->url(route('filament.admin.pages.orgchart'))
                                ->isActiveWhen(function (): bool {
                                    return request()->routeIs('filament.admin.pages.orgchart');
                                }),
                        ]);
                }
                if(auth()->user()){
                    $navigationGroups[] =  NavigationGroup::make('Message')
                        ->items([
                            ...ChatRoomResource::getNavigationItems(),
                        ]);
                }
                $worker = JobInfo::where('report_to', auth()->user()->id)->get();

                if ($generalSetting->time_off) {

                    $navigationGroups[] =  NavigationGroup::make('Time Off')
                        ->items([
                            ...LeaveResource::getNavigationItems(),
                            ...(auth()->user()->hasRole('Supervisor') || auth()->user()->hasRole('HR') || auth()->user()->hasRole('Super Admin') ? MyCompanyResource::getNavigationItems() : []),
                            ...(auth()->user()->hasRole('Staff') && count($worker) > 0 ? MyCompanyResource::getNavigationItems() : []),
                            ...(auth()->user()->hasRole('Staff') && count($worker) > 0 ? PendingRequestResource::getNavigationItems() : []),
                            ...(auth()->user()->hasPermissionTo('Time Off Approval') ? PendingRequestResource::getNavigationItems() : []),
                        ]);
                    if (auth()->user()->hasPermissionTo('Timesheet Management')) {
                        $navigationGroups[] =  NavigationGroup::make('Timesheet')
                            ->items([
                                ...ProjectResource::getNavigationItems(),
                                // ...TimesheetResource::getNavigationItems(),
                            ]);
                    }
                }

                if ($generalSetting->hr_tool) {
                    if (auth()->user()->hasPermissionTo('HR Toolkit')) {
                        $navigationGroups[] = NavigationGroup::make('HR Tools')
                            ->items([
                                ...FileResource::getNavigationItems(),
                                ...AssetResource::getNavigationItems(),
                                // ...OnboardingResource::getNavigationItems(),
                                // ...(EmployeeResource::canViewAny() ? OffboardingResource::getNavigationItems() : []),
                            ]);
                    }
                    if ($generalSetting->help_desk) {
                        if (auth()->user()->hasPermissionTo('Ticket Management')) {
                            $navigationGroups[] = NavigationGroup::make('HelpDesk')
                                ->items([
                                    ...TicketResource::getNavigationItems(),
                                ]);
                        }
                        if ($generalSetting->recruitment) {
                            if (!auth()->user()->hasRole('Staff')) {
                                $navigationGroups[] = NavigationGroup::make('Recruitment')
                                    ->items([
                                        ...(auth()->user()->hasRole('Supervisor') || auth()->user()->hasRole('HR') || auth()->user()->hasRole('Super Admin') ? JobResource::getNavigationItems() : []),
                                        ...CandidateResource::getNavigationItems(),
                                        ...RecruitmentReportResource::getNavigationItems()
                                    ]);
                            }
                        }
                    }
                }
                if ($generalSetting->lms) {
                    // if (auth()->user()->hasPermissionTo('LMS')) {
                    $navigationGroups[] = NavigationGroup::make('LMS')
                        ->items([
                            ...(auth()->user()->hasPermissionTo('Manage Course') ? CourseResource::getNavigationItems() : []),
                            ...EnrollmentResource::getNavigationItems(),
                            ...MyLearningResource::getNavigationItems(),
                        ]);
                    // }


                    if ($generalSetting->insights) {
                        if (auth()->user()->hasPermissionTo('Report Access')) {
                            $navigationGroups[] =  NavigationGroup::make('Insights')
                                ->items([
                                    ...ReportResource::getNavigationItems(),
                                ]);
                        }
                    }
                }


                if ($generalSetting->attendance) {
                    $navigationGroups[] =  NavigationGroup::make('Attendance')
                        ->items([

                            NavigationItem::make('Attendance')
                                ->icon('heroicon-o-building-office')
                                // ->isActiveWhen(function (): bool {
                                //     return request()->routeIs('filament.admin.resources.attendance.attendance-records.create') ;
                                // })
                                ->url(route('filament.admin.resources.attendance.attendance-records.create')),
                            ...AttendanceRecordResource::getNavigationItems(),

                        ]);
                }

                if ($generalSetting->finance) {
                    // FINANCE REIMBURSEMENT
                    $navigationGroups[] =  NavigationGroup::make('Finance')
                        ->items([
                            ...ReimbursementResource::getNavigationItems(),
                            ...PendingReimbursementRequestResource::getNavigationItems(),
                            ...BudgetResource::getNavigationItems(),
                            ...(auth()->user()->hasPermissionTo('Manage Reimbursement') ? FinanceReportResource::getNavigationItems() : []),
                            ...(auth()->user()->hasPermissionTo('Manage Payrun') ? PayrunResource::getNavigationItems() : []),
                            // ...(auth()->user()->hasPermissionTo('Manage Reimbursement') ?PayrunEmployeeAllowanceResource::getNavigationItems(): []),
                            // ...(auth()->user()->hasPermissionTo('Manage Reimbursement') ?PayrunEmployeeDeductionResource::getNavigationItems(): []),
                            // ...(auth()->user()->hasPermissionTo('Manage Reimbursement') ?EmployeeTaxSlabResource::getNavigationItems(): []),
                            // ...(auth()->user()->hasPermissionTo('Manage Reimbursement') ?PayrunPaymentResource::getNavigationItems(): []),

                        ]);
                }

                if ($generalSetting->forum) {
                    // FORUM
                    $navigationGroups[] =  NavigationGroup::make('Forum')
                        ->items([
                            ...ScheduleResource::getNavigationItems(),
                            ...FeedResource::getNavigationItems(),
                            // ...DailyWorkResource::getNavigationItems(),
                            ...DateResource::getNavigationItems(),
                            //   ...(auth()->user()->hasPermissionTo('Manage Course') ? CourseResource::getNavigationItems() :
                            ...(auth()->user()->hasRole('Super Admin') ?
                                QuestionResource::getNavigationItems() : [])


                        ]);
                }
                if ($generalSetting->settings) {
                    if (auth()->user()->hasPermissionTo('Settings Management')) {
                        $navigationGroups[] = NavigationGroup::make('Settings')
                            ->items([
                                NavigationItem::make('Recruitment')
                                    ->icon('heroicon-o-building-office')
                                    ->isActiveWhen(function (): bool {
                                        return request()->routeIs('filament.admin.resources.settings.recruitments.index') || request()->routeIs('filament.resources.employee/teams.index') || request()->routeIs('filament.resources.company/departments.index')  || request()->routeIs('filament.resources.company/designations.index');
                                    })
                                    ->url(route('filament.admin.resources.settings.recruitments.index')),

                                NavigationItem::make('Company')
                                    ->icon('heroicon-o-building-office')
                                    ->isActiveWhen(function (): bool {
                                        return request()->routeIs('filament.admin.resources.settings.companies.index') || request()->routeIs('filament.admin.resources.employee.shifts.index') || request()->routeIs('filament.admin.resources.employee.teams.index')  || request()->routeIs('filament.admin.resources.settings.departments.index') || request()->routeIs('filament.admin.resources.settings.designations.index');
                                    })
                                    ->url(route('filament.admin.resources.settings.companies.index')),

                                NavigationItem::make('Pay Roll')
                                    ->icon('heroicon-o-currency-dollar')
                                    ->isActiveWhen(function (): bool {
                                        return request()->routeIs('filament.admin.resources.settings.tax-slabs.index') || request()->routeIs('filament.admin.resources.settings.allowances.index') || request()->routeIs('filament.admin.resources.settings.deductions.index')  || request()->routeIs('filament.admin.resources.settings.over-time-rates.index')   || request()->routeIs('filament.admin.resources.settings.social-securities.index')  || request()->routeIs('filament.admin.resources.settings.payroll-policies.index')  || request()->routeIs('filament.admin.resources.settings.payslips.index');
                                    })
                                    ->url(route('filament.admin.resources.settings.tax-slabs.index')),

                                NavigationItem::make('Budget')
                                    ->icon('heroicon-o-banknotes')
                                    ->isActiveWhen(function (): bool {
                                        return request()->routeIs('filament.admin.resources.settings.budgets.index') || request()->routeIs('filament.admin.resources.settings.expense-categories.index') || request()->routeIs('filament.admin.resources.settings.expense-types.index');
                                    })
                                    ->url(route('filament.admin.resources.settings.expense-categories.index')),
                                NavigationItem::make('Employees')
                                    ->icon('heroicon-o-users')
                                    ->isActiveWhen(function (): bool {
                                        return request()->routeIs('filament.admin.resources.employee.employee-statuses.index') || request()->routeIs('filament.resources.employee/shifts.index')  || request()->routeIs('filament.resources.employee/genders.index')  || request()->routeIs('filament.resources.employee/marital-statuses.index') || request()->routeIs('filament.resources.employee/payment-intervals.index') || request()->routeIs('filament.resources.employee/payment-methods.index') || request()->routeIs('filament.resources.employee/relations.index')  || request()->routeIs('filament.resources.employee/employee-types.index');
                                    })
                                    ->url(route('filament.admin.resources.employee.employee-statuses.index')),

                                NavigationItem::make('TimeOff')
                                    ->icon('heroicon-o-calendar')
                                    ->isActiveWhen(function (): bool {
                                        return request()->routeIs('filament.admin.resources.time-off.holidays.index') || request()->routeIs('filament.resources.time-off/leave-types.index') || request()->routeIs('filament.resources.time-off/policy-frequencies.index') || request()->routeIs('filament.resources.time-off/work-weeks.index') || request()->routeIs('filament.resources.time-off/policies.index') || request()->routeIs('filament.admin.resources.time-off.leave-types.index') || request()->routeIs('filament.admin.resources.time-off.policy-frequencies.index') || request()->routeIs('filament.admin.resources.time-off.work-weeks.index') || request()->routeIs('filament.admin.resources.time-off.policies.index');
                                    })
                                    ->url(route('filament.admin.resources.time-off.holidays.index')),
                                ...RoleResource::getNavigationItems(),
                                ...OnboardingListResource::getNavigationItems(),
                                ...OffboardingListResource::getNavigationItems(),
                                ...ManageSettings::getNavigationItems(),

                            ]);
                    }
                }

                return $builder->items($builderItems)
                    ->groups($navigationGroups);
            })
            ->databaseNotifications()
            ->databaseNotificationsPolling('30s')
            ->plugin(
                BreezyCore::make(),
            )


            ->authMiddleware([
                Authenticate::class,
            ]);
      
    }
}
