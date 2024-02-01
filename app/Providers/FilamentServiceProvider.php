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
use FilamentQuickCreate\Facades\QuickCreate;


class FilamentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Filament::navigation(function (NavigationBuilder $builder): NavigationBuilder {
        //     return $builder
        //     ->items([
        //         ...EmployeeResource::getNavigationItems(),
        //     ])
        //     ->groups([
        //         NavigationGroup::make('Settings')
        //             ->items([
        //                 NavigationGroup::make('Employee')
        //                 ->items([
        //                     ...GenderResource::getNavigationItems(),
        //                 ])
        //                 ->collapsed(),
        //             ]),
        //         ]);
        // });


        Filament::serving(function () {
            // Using Vite
            Filament::navigation(function (NavigationBuilder $builder): NavigationBuilder {
                $builderItems = [];

                if (auth()->user()->hasRole('Supervisor') || auth()->user()->hasRole('HR') || auth()->user()->hasRole('Super Admin')) {
                    $builderItems[] = NavigationItem::make('Dashboard')
                            ->icon('heroicon-o-home')
                            ->activeIcon('heroicon-s-home')
                            ->isActiveWhen(fn (): bool => request()->routeIs('filament.pages.dashboard'))
                            ->url(route('filament.pages.dashboard'));
                }

                $navigationGroups = [];

                $navigationGroups[] =  NavigationGroup::make('Employee Directory')
                ->items([
                    NavigationItem::make('My Info')
                    ->icon('heroicon-o-user')
                    ->isActiveWhen(fn (): bool => request()->routeIs('filament.resources.employees.view')
                    && request()->route('record') == auth()->user()->id)
                    ->url(route('filament.resources.employees.view', ['record' => auth()->user()->id, 'activeRelationManager' => 0])),

                    NavigationItem::make('Employees')
                    ->icon('heroicon-o-user-group')
                    ->isActiveWhen(fn (): bool => (request()->routeIs('filament.resources.employees.index')
                                                  || request()->routeIs('filament.resources.employees.create')
                                                  || (request()->routeIs('filament.resources.employees.edit')
                                                  && request()->route('record') != auth()->user()->id)
                                                  || (request()->routeIs('filament.resources.employees.view')
                                                  && request()->route('record') != auth()->user()->id)))
                    ->url(route('filament.resources.employees.index')),


                    NavigationItem::make('Org Chart')
                    ->icon('heroicon-o-chart-pie')
                    ->url(route('filament.pages.orgchart'))
                    ->isActiveWhen(function (): bool {
                        return request()->routeIs('filament.pages.orgchart');
                    }),
                ]);
                $navigationGroups[] =  NavigationGroup::make('Time Off')
                ->items([
                    ...LeaveResource::getNavigationItems(),
                    ...(auth()->user()->hasRole('Supervisor') || auth()->user()->hasRole('HR') || auth()->user()->hasRole('Super Admin') ? MyCompanyResource::getNavigationItems() : []),
                    ...(auth()->user()->hasPermissionTo('Time Off Approval') ? PendingRequestResource::getNavigationItems(): []),
                ]);
                if (auth()->user()->hasPermissionTo('Timesheet Management')) {
                    $navigationGroups[] =  NavigationGroup::make('Timesheet')
                    ->items([
                        ...ProjectResource::getNavigationItems(),
                        ...TimesheetResource::getNavigationItems(),
                    ]);
                }

                if (auth()->user()->hasPermissionTo('Manage Performance Goals')) {
                    $navigationGroups[] =  NavigationGroup::make('Performance')
                    ->items([
                        ...GoalResource::getNavigationItems(),
                        ...ReviewResource::getNavigationItems(),
                    ]);
                }

                if (auth()->user()->hasPermissionTo('HR Toolkit')) {
                $navigationGroups[] = NavigationGroup::make('HR Tools')
                ->items([
                    ...FileResource::getNavigationItems(),
                    ...AssetResource::getNavigationItems(),
                    // ...OnboardingResource::getNavigationItems(),
                    // ...(EmployeeResource::canViewAny() ? OffboardingResource::getNavigationItems() : []),
                ]);
                }

                if (auth()->user()->hasPermissionTo('Ticket Management')) {
                    $navigationGroups[] = NavigationGroup::make('HelpDesk')
                    ->items([
                        ...TicketResource::getNavigationItems(),
                    ]);
                }
                if (auth()->user()->hasPermissionTo('Report Access')) {
                    $navigationGroups[] =  NavigationGroup::make('Insights')
                    ->items([
                        ...ReportResource::getNavigationItems(),
                    ]);
                }

                if (auth()->user()->hasPermissionTo('Settings Management')) {
                $navigationGroups[] = NavigationGroup::make('Settings')
                ->items([
                    NavigationItem::make('Company')
                    ->icon('heroicon-o-office-building')
                    ->isActiveWhen(function (): bool {
                        return request()->routeIs('filament.resources.company/companies.index') || request()->routeIs('filament.resources.employee/teams.index') || request()->routeIs('filament.resources.company/departments.index')  || request()->routeIs('filament.resources.company/designations.index');
                    })
                    ->url(route('filament.resources.company/companies.index')),

                    NavigationItem::make('Employees')
                    ->icon('heroicon-o-users')
                    ->isActiveWhen(function (): bool {
                        return request()->routeIs('filament.resources.employee/employee-statuses.index') || request()->routeIs('filament.resources.employee/shifts.index')  || request()->routeIs('filament.resources.employee/genders.index')  || request()->routeIs('filament.resources.employee/marital-statuses.index') || request()->routeIs('filament.resources.employee/payment-intervals.index') || request()->routeIs('filament.resources.employee/payment-methods.index') || request()->routeIs('filament.resources.employee/relations.index')  || request()->routeIs('filament.resources.employee/employee-types.index');
                    })
                    ->url(route('filament.resources.employee/employee-statuses.index')),

                    NavigationItem::make('TimeOff')
                    ->icon('heroicon-o-calendar')
                    ->isActiveWhen(function (): bool {
                        return request()->routeIs('filament.resources.time-off/holidays.index') || request()->routeIs('filament.resources.time-off/leave-types.index')  || request()->routeIs('filament.resources.time-off/policy-frequencies.index') || request()->routeIs('filament.resources.time-off/work-weeks.index') || request()->routeIs('filament.resources.time-off/policies.index');
                    })
                    ->url(route('filament.resources.time-off/holidays.index')),
                    ...RoleResource::getNavigationItems(),
                    ...OnboardingListResource::getNavigationItems(),
                    ...OffboardingListResource::getNavigationItems(),
                ]);
                }

                return $builder->items($builderItems)
                ->groups($navigationGroups);
            });
            Filament::registerViteTheme('resources/css/filament.css');
            QuickCreate::includes([
                EmployeeResource::class,
                LeaveResource::class
            ]);
            Filament::registerNavigationGroups([
                NavigationGroup::make()
                    ->label('Company Settings')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label('TimeOff Settings')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label('Employee Info Settings')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label('Settings')
                    ->collapsed(),
            ]);
        });
    }
}
