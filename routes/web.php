<?php

use App\Filament\Resources\Employee\EmployeeResource\Pages\ImportEmployee;
use App\Filament\Resources\Learning\CourseResource\Pages\CreateCourse;
use App\Filament\Resources\Learning\EnrollmentResource\Pages\Enroll;
use App\Filament\Resources\Learning\MyLearningResource\Pages\QuizFeedback;
use App\Filament\Resources\Settings\PayslipResource\Pages\PayslipTemplate;
use App\Filament\Resources\Timesheet\ProjectDocResource\Pages\CreateProjectDoc;
use App\Imports\EmployeeImport;
use App\Livewire\CreateOfferLetter;
use App\Livewire\CustomVariable;
use App\Livewire\FileUpload;
use App\Livewire\JobApplication;
use App\Livewire\JobDescription;
use App\Livewire\Livechat\Chatapplication;
use App\Livewire\OpenPositions;
use App\Livewire\PasswordCreate;
use App\Livewire\SetupPage;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/form', function () {
    return view('livewire.password-create');
});
// Route::get('/payrunReport/{id}',[PayslipTemplate::class,'pdfGenerator']);
// Route::get('form',PasswordCreate::class);

// Route::get('/openpositions', function () {
//     return view('livewire.open-positions');
// });

Route::get('/open-positions', OpenPositions::class); // Route to call the OpenPositions page

Route::get('/job-description/{id}', JobDescription::class); // Route to call the JobDescriptions page

// Route::get('/job-application', JobApplication::class); // Route to call the JobApplication page

Route::get('/job-application/{id}', JobApplication::class); // Route to call the JobApplication page
Route::post('/upload_chunk', [CreateCourse::class,'uploadChunk']); // Route to call the JobApplication page
Route::post('/upload_excel', [ImportEmployee::class,'uploadChunk']); // Route to call the JobApplication page

// Route::get('/offer-letter',FileUpload::class);
Route::get('/offers/{id}',CreateOfferLetter::class);

Route::get('/certificate-download/{record}',[QuizFeedback::class,'downloadCertificate']);
Route::get('/setup', SetupPage::class)->name('setup')->middleware(\Shipu\WebInstaller\Middleware\RedirectIfNotInstalled::class);;
// Route::post('/filesupload',[Chatapplication::class,'uploadLargeFiles'])->name('upload.store');
Route::post('/upload_doc', [CreateProjectDoc::class,'uploadChunk']);

