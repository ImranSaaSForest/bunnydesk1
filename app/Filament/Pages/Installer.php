<?php

namespace App\Filament\Pages;


use App\Models\Employee\EmployeeStatus;
use App\Models\Employee\Employment;
use Filament\Facades\Filament;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Shipu\WebInstaller\Concerns\InstallationContract;
use Spatie\Permission\Models\Role;

class Installer implements InstallationContract
{
    public function run($data): bool
    {
        try {
            // Artisan::call('migrate:fresh', [
            //     '--force' => true,
            // ]);

            $user = config('installer.user_model');

            $userRecord=$user::create([
                'name'       => array_get($data, 'applications.admin.name'),
                'email'      => array_get($data, 'applications.admin.email'),
                'password'   => array_get($data, 'applications.admin.password'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $adminRole=Role::where('name','Super Admin')->first();
            $userRecord->assignRole($adminRole);
                    // Artisan::call('db:seed', [
            //     '--force' => true,
            // ]);
            $activeStatus=EmployeeStatus::where('name','Active')->first();
            if ($activeStatus) {
                Employment::create([
                    'employee_status_id' => $activeStatus->id,
                    'user_id' => $userRecord->id
                ]);
            }
            file_put_contents(storage_path('installed'), 'installed');


            return true;
        } catch (\Exception $exception) {
            return false;
        }
    }

    public function redirect(): Application|Redirector|RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        try {
            if (class_exists(Filament::class)) {
                return redirect()->intended(Filament::getUrl());
            }

            return redirect(config('installer.redirect_route'));
        } catch (\Exception $exception) {
            Log::info("route not found...");
            Log::info($exception->getMessage());
            return redirect()->route('installer.success');
        }
    }

    public function dehydrate(): void
    {
        Log::info("installation dehydrate...");
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
    }
}

