<?php

use App\Livewire\LandingPageComponent;
use App\Livewire\HomePageComponent;
use App\Livewire\Auth\LoginComponent;
use App\Livewire\Auth\RegisterComponent;
use App\Livewire\Seller\SellerRegisterComponent;
use App\Livewire\Admin\AdminDashboardComponent;
use App\Livewire\Admin\ManageCompanyStructureComponent;
use App\Livewire\Admin\ManageSellersComponent;
use App\Livewire\Admin\ManageProductsComponent as AdminManageProductsComponent;
use App\Livewire\Admin\ManageServicesComponent as AdminManageServicesComponent;
use App\Livewire\Admin\ViewReportsComponent as AdminViewReportsComponent;
use App\Livewire\Buyer\BuyerDashboardComponent;
use App\Livewire\Buyer\ProductListComponent;
use App\Livewire\Buyer\ProductDetailComponent;
use App\Livewire\Buyer\CartComponent;
use App\Livewire\Buyer\CheckoutComponent;
use App\Livewire\Buyer\ServiceListComponent;
use App\Livewire\Buyer\ServiceBookingComponent;
use App\Livewire\Buyer\ServiceTrackingComponent;
use App\Livewire\Seller\SellerDashboardComponent;
use App\Livewire\Seller\SellerProfileComponent;
use App\Livewire\Seller\ManageProductsComponent as SellerManageProductsComponent;
use App\Livewire\Seller\ManageServicesComponent as SellerManageServicesComponent;
use App\Livewire\Seller\ViewReportsComponent as SellerViewReportsComponent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', LandingPageComponent::class)->name('landing');
Route::get('/home', HomePageComponent::class)->name('home');
Route::get('/login', LoginComponent::class)->name('login');
Route::get('/register', RegisterComponent::class)->name('register');
Route::get('/seller/register', SellerRegisterComponent::class)->name('seller.register');

// Logout
Route::get('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

// Admin routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', AdminDashboardComponent::class)->name('admin.dashboard');
    Route::get('/admin/company-structure', ManageCompanyStructureComponent::class)->name('admin.company-structure');
    Route::get('/admin/sellers', ManageSellersComponent::class)->name('admin.sellers');
    Route::get('/admin/products', AdminManageProductsComponent::class)->name('admin.products');
    Route::get('/admin/services', AdminManageServicesComponent::class)->name('admin.services');
    Route::get('/admin/reports', AdminViewReportsComponent::class)->name('admin.reports');
});

// Buyer routes
Route::middleware(['auth', 'role:buyer'])->group(function () {
    Route::get('/buyer/dashboard', BuyerDashboardComponent::class)->name('buyer.dashboard');
    Route::get('/buyer/products', ProductListComponent::class)->name('buyer.products');
    Route::get('/buyer/products/{id}', ProductDetailComponent::class)->name('buyer.products.detail');
    Route::get('/buyer/cart', CartComponent::class)->name('buyer.cart');
    Route::get('/buyer/checkout', CheckoutComponent::class)->name('buyer.checkout');
    Route::get('/buyer/services', ServiceListComponent::class)->name('buyer.services');
    Route::get('/buyer/services/{id}/booking', ServiceBookingComponent::class)->name('buyer.services.booking');
    Route::get('/buyer/service-tracking/{id}', ServiceTrackingComponent::class)->name('buyer.service-tracking');
});

// Seller routes
Route::middleware(['auth', 'role:seller'])->group(function () {
    Route::get('/seller/dashboard', SellerDashboardComponent::class)->name('seller.dashboard');
    Route::get('/seller/profile', SellerProfileComponent::class)->name('seller.profile');
    Route::get('/seller/products', SellerManageProductsComponent::class)->name('seller.products');
    Route::get('/seller/services', SellerManageServicesComponent::class)->name('seller.services');
    Route::get('/seller/reports', SellerViewReportsComponent::class)->name('seller.reports');
});
