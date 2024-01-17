<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

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

if (env('APP_ENV') === 'production') {
    //URL::forceSchema('https');
   \URL::forceScheme('https');
}

Route::get('/', function () {
    return redirect()->route('login');
});

Route::group(['middleware' => ['auth', '\Spatie\Permission\Middleware\RoleMiddleware:superadmin']], function () {
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
    Route::get('/user-status-update/{id}', [UserController::class, 'userStatusUpdate'])->name('user.statusUpdate');
});

Route::middleware(['auth', 'verified'])->group(function () {


    // dashboard
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    // User Profile
    Route::get('/logout', function (Request $request) {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/update-password', [ProfileController::class, 'update_password'])->name('profile.update_password');


    // customers Routes
    Route::get('/customers', [CustomerController::class, 'bulkUploadCustomer'])->name('customers');
    Route::post('assign-customer-to-user', [CustomerController::class, "assignCustomer"])->name('assignCustomer');
    Route::get('/customer-view/{customerId?}', [CustomerController::class, 'bulkUploadCustomerView'])->name('customer.bulkUploadCustomerView');
    Route::get('/customer-add', [CustomerController::class, 'create'])->name('customer.create');
    Route::post('/customer-add', [CustomerController::class, 'store'])->name('customer.store');
    Route::get('/import-customer', [CustomerController::class, 'importFileView'])->name('customer.importFileView');

    Route::post('/import-customer', [CustomerController::class, 'customerImport'])->name('customer.import');
    Route::get('/download-customers-csv-file', [CustomerController::class, 'downloadCustomerSampleCsv'])->name('customer.downloadSampleCsv');
    Route::get('/customer-edit/{customer?}', [CustomerController::class, 'bulkUploadCustomerEdit'])->name('customer.bulkUploadCustomerEdit');
    Route::post('/customer-edit/{customer?}', [CustomerController::class, 'bulkUploadCustomerUpdate'])->name('customer.bulkUploadCustomerUpdate');

    Route::get('/customer-project-details', [CustomerController::class, 'projectDetailsList'])->name('customer.projectDetailsList');
    Route::post('/project-details-store', [CustomerController::class, 'projectDetails'])->name('customer.projectDetails');

    Route::get('edit-project-details/{projectdetails_id}',[CustomerController::class,'editProjectDetails'])->name('customer.editProjectDetails');
    Route::post('update-project-details/{projectdetails_id?}',[CustomerController::class,'updateProjectDetails'])->name('customer.updateProjectDetails');

    // add multiple customer name and phone route

    Route::post('update-cust-phone-details/{customer?}',[CustomerController::class,'addcustNamePhoneNumber'])->name('customer.addcustNamePhoneNumber');

    Route::post('update-cust-name-details/{customer?}',[CustomerController::class,'addcustName'])->name('customer.addcustName');

    Route::get('getCustomerComment/{customerId?}',[CustomerController::class,'getCustomerComment'])->name('customer.getCustomerComment');


    // Customer Comments Routes
    Route::get('customers-add-comment/{customerId?}',[CommentController::class,'addComments'])->name('user.addComments');
    Route::post('customers-add-comment/{customerId?}',[CommentController::class,'storeComments'])->name('user.storeComments');

    Route::get('customers-edit-comment/{comment}',[CommentController::class,'editComment'])->name('user.editComment');
    Route::post('customers-update-comment/{comment?}',[CommentController::class,'updateComments'])->name('user.updateComments');

    Route::patch('/update-customer-status/{id}',[CommentController::class,'updateCustomerStatus'])->name('customer.updateCustomerStatus');
    Route::post('/update-follow-up-status/{customerId}', [CommentController::class, 'updateFollowUpStatus'])->name('customer.updateFollowUpStatus');

    Route::patch('/update-communication-medium/{customerId}', [CommentController::class, 'CustomerCommunicationMedium'])->name('customer.customerCommunicationMedium');

     Route::get('/customer-comment-list/{customerId?}', [CommentController::class, 'customerAllComment'])->name('customer.customerAllComment');

     Route::post('/project-details-update/{customerId?}', [CommentController::class, 'customerProjectDetailsAddEdit'])->name('customer.customerProjectDetailsAddEdit');




});


require __DIR__ . '/auth.php';
