<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\AirportController;
use App\Http\Controllers\AirlineController;
use App\Http\Controllers\AirlineTypeController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CodesListController;
use App\Http\Controllers\LinkCategoryController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ProvinceController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\RegionTypeController;
use App\Http\Controllers\RegionCategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WebPagesController;
use App\Http\Controllers\FileManagmentController;
use App\Http\Controllers\SaleFormController;
use App\Http\Controllers\InquiriesDashboardController;
use App\Http\Controllers\SalesDashboardController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\Auth\TwoFactorAuthenticationController;
use App\Models\CharityOrganization;
use Illuminate\Support\Facades\Route;


Route::middleware('web')->group(function () {
    Route::get('/', [WebPagesController::class, 'home'])->name('home');
    Route::get('/about', [WebPagesController::class, 'about'])->name('about');
    Route::get('/inquiry', [WebPagesController::class, 'inquiry'])->name('inquiry');
    Route::get('/contact', [WebPagesController::class, 'contact'])->name('contact');
    Route::any('/emailContact', [WebPagesController::class, 'emailContact']);
    Route::get('/documents', [WebPagesController::class, 'documents'])->name('documents');
    Route::get('/flights', [WebPagesController::class, 'flights'])->name('flights');
    Route::get('/cruises', [WebPagesController::class, 'cruises'])->name('cruises');
    Route::get('/vacations', [WebPagesController::class, 'vacations'])->name('vacations');
    Route::get('/airport', [AirportController::class, 'index'])->name('airport');
    Route::get('/airline', [AirlineController::class, 'index'])->name('airline');
    Route::get('/region', [RegionController::class, 'index'])->name('region');
    Route::get('/country', [CountryController::class, 'index'])->name('country');
    Route::any('/agents', [UserController::class, 'getAgents'])->name('agents');
    Route::any('/saveInquiry', [BookingController::class, 'saveInquiry'])->name('saveInquiry');
    Route::any('/web-documents', [FileManagmentController::class, 'webDocuments'])->name('webDocuments');
     
});
$twoFactorLimiter = config('fortify.limiters.two-factor');
Route::post('/two-factor-challenge', [AuthenticatedSessionController::class, 'store'])
    ->middleware(array_filter([
        'guest:'.config('fortify.guard'),
        $twoFactorLimiter ? 'throttle:'.$twoFactorLimiter : null,
    ]));
Route::middleware(['auth', 'nocache'])->group(function () {
    // Two Factor Authentication

    // OR for traditional controllers:
    Route::get('api/exportinquiries', function () {
        return Excel::download(new \App\Exports\InquiriesExport(), 'inquiries.xlsx');
    });
    Route::get('/two-factor-challenge', [TwoFactorAuthenticationController::class, 'showChallengeForm'])
        ->name('two-factor-challenge');
    Route::post('/user/two-factor-authentication', [TwoFactorAuthenticationController::class, 'enable']);
    Route::delete('/user/two-factor-authentication', [TwoFactorAuthenticationController::class, 'disable']);
    Route::post('/user/two-factor-verification', [TwoFactorAuthenticationController::class, 'verify']);

    Route::get('/change-password', [PasswordController::class, 'showChangeForm'])
         ->name('password.change');
    Route::post('/change-password', [PasswordController::class, 'update']);
    Route::get('api/check-first-login', [UserController::class, 'checkFirstLogin']);
    Route::put('api/updatePassword', [UserController::class, 'updatePassword']);
    Route::post('api/getRoles', [UserController::class, 'getRoles']);
    Route::get('api/agents', [UserController::class, 'getAgents']);
    Route::get('api/suppliers', [UserController::class, 'getSuppliers']);

    Route::resource('api/file-managment', FileManagmentController::class)->only([
        'index', 'store', 'show', 'update', 'destroy',
    ]);
    Route::get('api/inquiry-counts/{from}/{to}',[InquiriesDashboardController::class,'InquiryCounts']);
    Route::get('api/daterange-inquiry-counts/{from}/{to}',[InquiriesDashboardController::class,'DaterangeInquiryCounts']);
    Route::get('api/agent-inquiry-counts/{agent}/{from}/{to}',[InquiriesDashboardController::class,'AgentInquiryCounts']);
    Route::get('api/airline-inquiry-counts/{airline}/{from}/{to}',[InquiriesDashboardController::class,'AirlineInquiryCounts']);
    Route::get('api/region-inquiry-counts/{from}/{to}',[InquiriesDashboardController::class,'RegionInquiryCounts']);
    Route::get('api/calender-inquiry-stats',[InquiriesDashboardController::class,'CalenderInquiryStats']);
    
    
    Route::get('api/sales-stats/{from}/{to}',[SalesDashboardController::class,'SalesStats']);
    Route::get('api/table-transactions',[SaleFormController::class,'index']);
    Route::get('api/saleform-inquiry-type-counts/{from}/{to}',[SalesDashboardController::class,'SaleformInquiryTypeCounts']);
    Route::get('api/saleform-line-chart-counts/{date}',[SalesDashboardController::class,'SaleformLineChartCounts']);
    Route::get('api/supplier-sales-counts/{id}/{from}/{to}',[SalesDashboardController::class,'SupplierSalesCounts']);
    Route::get('api/agent-sales-counts/{id}/{from}/{to}',[SalesDashboardController::class,'AgentSalesCounts']);
    Route::get('api/saleform-payment-breakdown-data/{from}/{to}',[SalesDashboardController::class,'SaleformPaymentBreakdown']);
    Route::get('api/cruise-region-payment-breakdown/{from}/{to}',[SalesDashboardController::class,'CruiseRegionPaymentBreakdown']);
    
    Route::get('api/flight-inquiry',[BookingController::class,'flightInquiry']);
    Route::get('api/vacation-inquiry',[BookingController::class,'vacationInquiry']);
    Route::get('api/cruise-inquiry',[BookingController::class,'cruiseInquiry']);
    Route::resource('api/sale-form', SaleFormController::class)->only([
        'index',
        'store',
        'show',
        'update',
        'destroy',
    ]);
    Route::resource('api/inquiry', BookingController::class)->only([
        'index',
        'store',
        'show',
        'update',
        'destroy',
    ]);

    Route::resource('api/codes-list', CodesListController::class)->only([
        'index',
        'store',
        'show',
        'update',
        'destroy',
    ]);
    
    Route::resource('api/region-category', RegionCategoryController::class)->only([
        'index',
    ]);
    Route::resource('api/region-type', RegionTypeController::class)->only([
        'index',
    ]);
    Route::resource('api/region', RegionController::class)->only([
        'index',
        'store',
        'show',
        'update',
        'destroy',
    ]);
    //Routes for Airline
    Route::resource('api/airline-type', AirlineTypeController::class)->only([
        'index',
    ]);
    Route::resource('api/airline', AirlineController::class)->only([
        'index',
        'store',
        'show',
        'update',
        'destroy',
    ]);

    Route::resource('api/country', CountryController::class)->only([
        'index',
        'store',
        'show',
        'update',
        'destroy',
    ]);

    Route::resource('api/province', ProvinceController::class)->only([
        'index',
        'store',
        'show',
        'update',
        'destroy',
    ]);
    Route::resource('api/city', CityController::class)->only([
        'index',
        'store',
        'show',
        'update',
        'destroy',
    ]);
    Route::resource('api/airport', AirportController::class)->only([
        'index',
        'store',
        'show',
        'update',
        'destroy',
    ]);
    Route::resource('api/linkCategory', LinkCategoryController::class)->only([
        'index',
        'store',
        'show',
        'update',
        'destroy',
    ]);

    Route::resource('api/users', UserController::class)->only([
        'index',
        'store',
        'show',
        'update',
        'destroy',
    ]);
    Route::get('/api/searchMember/{phone_no}', [MemberController::class, 'searchMember']);
    
    Route::resource('api/members', MemberController::class)->only([
        'index',
        'store',
        'show',
        'update',
        'destroy',
    ]);

    
    // ==============================
    // PermissionController
    // ==============================
    Route::resource('api/permission', PermissionController::class)->only([
        'index',
        'store',
        'show',
        'update',
        'destroy',
    ]);
    Route::get('api/get-tables', [PermissionController::class, 'getTables']);
    Route::get('api/get-permissions', [PermissionController::class, 'getPermissions']);
    Route::get('api/user-permissions', [PermissionController::class, 'userPermissions']);

    Route::resource('api/roles', RoleController::class)->only([
        'index',
        'store',
        'show',
        'update',
        'destroy',
    ]);

    Route::resource('api/tenant', TenantController::class)->only([
        'index',
        'store',
        'show',
        'update',
        'destroy',
    ]);
    

    // Define the impersonation routes within the auth middleware group
    Route::middleware('auth')->group(function () {
        Route::post('api/impersonate/{id}', [UserController::class, 'impersonate']);
        Route::post('api/leave-impersonate', [UserController::class, 'stopImpersonate'])->name('impersonate.stop'); // ✅ Add this name
    });

    // Check impersonation status route
    Route::get('/api/check-impersonation', function () {
        return response()->json([
            'isImpersonating' => session()->has('impersonate')
        ]);
    });



    Route::get('{view}', ApplicationController::class)->where('view', '(.*)');
});
