<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Users\CustomerController as UsersCustomerController;
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
    URL::forceSchema('https');
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


    // User Comments Routes
    Route::get('customers-list',[UsersCustomerController::class,'index'])->name('user.customersList');
    Route::get('customers-comments/{customerId}',[UsersCustomerController::class,'viewAllComments'])->name('user.viewAllComments');

    Route::get('customers-add-comment/{customerId}',[UsersCustomerController::class,'addComments'])->name('user.addComments');
    Route::post('customers-add-comment/{customerId?}',[UsersCustomerController::class,'storeComments'])->name('user.storeComments');

    Route::get('customers-edit-comment/{comment}',[UsersCustomerController::class,'editComment'])->name('user.editComment');
    Route::post('customers-update-comment/{comment?}',[UsersCustomerController::class,'updateComments'])->name('user.updateComments');


    Route::patch('/update-follow-up-status/{id}',[UsersCustomerController::class,'updateFollowUpStatus'])->name('user.updateFollowUpStatus');

    Route::patch('/update-customer-status/{id}',[UsersCustomerController::class,'updateCustomerStatus'])->name('user.updateCustomerStatus');

});



require __DIR__ . '/auth.php';
