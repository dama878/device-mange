<?php

use App\Http\Controllers\Admin\BorrowController;
use App\Http\Controllers\Admin\BorrowDetailModelController;
use App\Http\Controllers\Admin\BorrowerController;
use App\Http\Controllers\Admin\BorrowerGroupController;
use App\Http\Controllers\Admin\BorrowReturnController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DeviceController;

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ExportController;
use App\Http\Controllers\Admin\ExportDetailController;
use App\Http\Controllers\Admin\FineController;
use App\Http\Controllers\Admin\ImportController;
use App\Http\Controllers\Admin\ImportDetailController;
use App\Http\Controllers\Admin\ManufacturerController;
use App\Http\Controllers\Admin\ModelController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\PermissionRoleController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\TypeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Api\BorrowDetailsModelController;
use App\Http\Controllers\API\BorrowerGroupsController;
use App\Http\Controllers\API\BorrowersController;
use App\Http\Controllers\API\BorrowReturnsController;
use App\Http\Controllers\Api\BorrowsController;
use App\Http\Controllers\Api\CustomersController;
use App\Http\Controllers\Api\DevicesController;
use App\Http\Controllers\Api\ExportDetailsController;
use App\Http\Controllers\Api\ExportsController;
use App\Http\Controllers\API\FinesController;
use App\Http\Controllers\Api\ImportDetailsController;
use App\Http\Controllers\Api\ImportsController;
use App\Http\Controllers\Api\ManufacturersController;
use App\Http\Controllers\Api\ModelsController;
use App\Http\Controllers\Api\PermissionRolesController;
use App\Http\Controllers\Api\PermissionsController;
use App\Http\Controllers\Api\RolesController;
use App\Http\Controllers\Api\TypesController;
use App\Http\Controllers\Api\UsersController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('login', [UserController::class, 'index'])->name('login');
Route::get('/app/login', [UserController::class, 'index']);
Route::get('/logins', [UserController::class, 'index']);
Route::get('/user/login', [UserController::class, 'index']);
Route::post('/user/do-login', [UserController::class, 'doLogin']);

// Admin site routes
Route::group(['middleware' => 'auth'], function () {
    Route::get('/app/user', [UserController::class, 'indexUser']);
    Route::get('/internal/users/get/{id?}', [UsersController::class, 'get']);
    Route::post('/internal/users/add', [UsersController::class, 'add']);
    Route::post('/internal/users/update', [UsersController::class, 'update']);
    Route::post('/internal/users/delete/{id}', [UsersController::class, 'delete']);
    Route::get('/internal/users/exist', [UsersController::class, 'exist']);

    Route::get('/app/dashboard', [DashboardController::class, 'index']);
    Route::post('/logout', [UserController::class, 'destroy']);
    Route::get('/app/type', [TypeController::class, 'index']);
    Route::get('/internal/types/get/{id?}', [TypesController::class, 'get']);
    Route::get('/internal/types/get-parent-list', [TypesController::class, 'getParentList']);
    Route::post('/internal/types/add', [TypesController::class, 'add']);
    Route::post('/internal/types/update', [TypesController::class, 'update']);
    Route::post('/internal/types/delete/{id}', [TypesController::class, 'delete']);
    
    Route::get('/app/device', [DeviceController::class, 'index']);
    Route::get('/internal/devices/get/{id?}', [DevicesController::class, 'get']);
    Route::post('/internal/devices/add', [DevicesController::class, 'add']);
    Route::post('/internal/devices/update', [DevicesController::class, 'update']);
    Route::post('/internal/devices/delete/{id}', [DevicesController::class, 'delete']);

    Route::get('/app/manufacturer', [ManufacturerController::class, 'index']);
    Route::get('/internal/manufacturers/get/{id?}', [ManufacturersController::class, 'get']);
    Route::post('/internal/manufacturers/add', [ManufacturersController::class, 'add']);
    Route::post('/internal/manufacturers/update', [ManufacturersController::class, 'update']);
    Route::post('/internal/manufacturers/delete/{id}', [ManufacturersController::class, 'delete']);

    // Borrow site routes
    Route::get('/app/borrow', [BorrowController::class, 'index']);
    Route::get('/internal/borrows/get/{id?}', [BorrowsController::class, 'get']);
    Route::post('/internal/borrows/add', [BorrowsController::class, 'add']);
    Route::post('/internal/borrows/update', [BorrowsController::class, 'update']);
    Route::post('/internal/borrows/delete/{id?}', [BorrowsController::class, 'delete']);

    // Model site routes
    Route::get('/app/model', [ModelController::class, 'index']);
    Route::get('/internal/models/get/{id?}', [ModelsController::class, 'get']);
    Route::post('/internal/models/add', [ModelsController::class, 'add']);
    Route::post('/internal/models/update', [ModelsController::class, 'update']);
    Route::post('/internal/models/delete/{id?}', [ModelsController::class, 'delete']);

    // Borrow Detail Model site routes
    Route::get('/app/borrowdetailmodel', [BorrowDetailModelController::class, 'index']);
    Route::get('/internal/borrowdetailmodels/get/{id?}', [BorrowDetailsModelController::class, 'get']);
    Route::get('/internal/borrowdetailmodels/get-by-id/{id?}', [BorrowDetailsModelController::class, 'getByID']);
    Route::post('/internal/borrowdetailmodels/add', [BorrowDetailsModelController::class, 'add']);
    Route::post('/internal/borrowdetailmodels/update', [BorrowDetailsModelController::class, 'update']);
    Route::post('/internal/borrowdetailmodels/delete/{id?}', [BorrowDetailsModelController::class, 'delete']);


    Route::get('/app/borrower', [BorrowerController::class, 'index']);
    Route::get('/app/borrowergroup', [BorrowerGroupController::class, 'index']);
    Route::get('/app/borrowerreturn', [BorrowReturnController::class, 'index']);
    Route::get('/app/fine', [FineController::class, 'index']);


    
    Route::get('/internal/borrower_groups/get/{id?}', [BorrowerGroupsController::class, 'get']);
    Route::post('/internal/borrower_groups/add', [BorrowerGroupsController::class, 'add']);
    Route::post('/internal/borrower_groups/update', [BorrowerGroupsController::class, 'update']);
    Route::post('/internal/borrower_groups/delete/{id}', [BorrowerGroupsController::class, 'delete']);

    Route::get('/internal/borrowers/get/{id?}', [BorrowersController::class, 'get']);
    Route::post('/internal/borrowers/add', [BorrowersController::class, 'add']);
    Route::post('/internal/borrowers/update', [BorrowersController::class, 'update']);
    Route::post('/internal/borrowers/delete/{id}', [BorrowersController::class, 'delete']);

    Route::get('/internal/borrow_returns/get/{id?}', [BorrowReturnsController::class, 'get']);
    Route::post('/internal/borrow_returns/add', [BorrowReturnsController::class, 'add']);
    Route::post('/internal/borrow_returns/update', [BorrowReturnsController::class, 'update']);
    Route::post('/internal/borrow_returns/delete/{id}', [BorrowReturnsController::class, 'delete']);

    Route::get('/internal/fines/get/{id?}', [FinesController::class, 'get']);
    Route::post('/internal/fines/add', [FinesController::class, 'add']);
    Route::post('/internal/fines/update', [FinesController::class, 'update']);
    Route::post('/internal/fines/delete/{id}', [FinesController::class, 'delete']);

    Route::get('app/export', [ExportController::class, 'index']);
    Route::get('/internal/exports/get/{id?}', [ExportsController::class, 'get']);
    Route::post('/internal/exports/add', [ExportsController::class, 'add']);
    Route::post('/internal/exports/update', [ExportsController::class, 'update']);
    Route::post('/internal/exports/delete/{id}', [ExportsController::class, 'delete']);

    // Export Detail
    Route::get('app/export-detail', [ExportDetailController::class, 'index']);
    Route::get('/internal/export-details/get/{id?}', [ExportDetailsController::class, 'get']);
    Route::post('/internal/export-details/add', [ExportDetailsController::class, 'add']);
    Route::post('/internal/export-details/update', [ExportDetailsController::class, 'update']);
    Route::post('/internal/export-details/delete/{id}', [ExportDetailsController::class, 'delete']);

    // Import
    Route::get('app/import', [ImportController::class, 'index']);
    Route::get('/internal/imports/get/{id?}', [ImportsController::class, 'get']);


    Route::post('/internal/imports/add', [ImportsController::class, 'add']);
    Route::post('/internal/imports/update', [ImportsController::class, 'update']);
    Route::post('/internal/imports/delete/{id}', [ImportsController::class, 'delete']);

    // Import Detail
    Route::get('app/import-detail', [ImportDetailController::class, 'index']);
    Route::get('/internal/import-details/get/{id?}', [ImportDetailsController::class, 'get']);
    Route::get('/internal/import-details/get-by-id/{id?}', [ImportDetailsController::class, 'getByID']);
    Route::post('/internal/import-details/add', [ImportDetailsController::class, 'add']);
    Route::post('/internal/import-details/update', [ImportDetailsController::class, 'update']);
    Route::post('/internal/import-details/delete/{id}', [ImportDetailsController::class, 'delete']);

    // customer 
    Route::get('app/customer', [CustomerController::class, 'index']);
    Route::get('/internal/customers/get/{id?}', [CustomersController::class, 'get']);
    Route::post('/internal/customers/add', [CustomersController::class, 'add']);
    Route::post('/internal/customers/update', [CustomersController::class, 'update']);
    Route::post('/internal/customers/delete/{id}', [CustomersController::class, 'delete']);


    Route::get('/app/role', [RoleController::class, 'index']);
    Route::get('/internal/roles/get/{id?}', [RolesController::class, 'get']);
    Route::post('/internal/roles/add', [RolesController::class, 'add']);
    Route::post('/internal/roles/update', [RolesController::class, 'update']);
    Route::post('/internal/roles/delete/{id}', [RolesController::class, 'delete']);

    Route::get('/app/permission', [PermissionController::class, 'index']);
    Route::get('/internal/permissions/get/{id?}', [PermissionsController::class, 'get']);
    Route::post('/internal/permissions/add', [PermissionsController::class, 'add']);
    Route::post('/internal/permissions/update', [PermissionsController::class, 'update']);
    Route::post('/internal/permissions/delete/{id}', [PermissionsController::class, 'delete']);

    Route::get('/app/permissionrole', [PermissionRoleController::class, 'index']);
    Route::get('/internal/permission_roles/get/{id?}', [PermissionRolesController::class, 'get']);
    Route::post('/internal/permission_roles/add', [PermissionRolesController::class, 'add']);
    Route::post('/internal/permission_roles/update', [PermissionRolesController::class, 'update']);
    Route::post('/internal/permission_roles/delete/{id}', [PermissionRolesController::class, 'delete']);
});