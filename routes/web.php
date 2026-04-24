<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ReportPdfController;
use App\Livewire\DashboardComponent;
use App\Livewire\FinanceComponent;
use App\Livewire\ProductComponent;
use App\Livewire\Reports\FinanceReportComponent;
use App\Livewire\Reports\ProfitLossReportComponent;
use App\Livewire\Reports\SalesReportComponent;
use App\Livewire\Reports\StockReportComponent;
use App\Livewire\SaleComponent;
use App\Livewire\StockComponent;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route(auth()->check() ? 'dashboard' : 'login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', DashboardComponent::class)->name('dashboard');

    Route::middleware('role:admin')->group(function () {
        Route::get('/products', ProductComponent::class)->name('products');
        Route::get('/sales', SaleComponent::class)->name('sales');
        Route::get('/stocks', StockComponent::class)->name('stocks');
        Route::get('/finances', FinanceComponent::class)->name('finances');
    });

    Route::middleware('role:admin,owner')->group(function () {
        Route::get('/reports/sales', SalesReportComponent::class)->name('reports.sales');
        Route::get('/reports/finance', FinanceReportComponent::class)->name('reports.finance');
        Route::get('/reports/profit-loss', ProfitLossReportComponent::class)->name('reports.profit-loss');
        Route::get('/reports/stocks', StockReportComponent::class)->name('reports.stocks');

        Route::get('/reports/sales/pdf', [ReportPdfController::class, 'sales'])->name('reports.sales.pdf');
        Route::get('/reports/finance/pdf', [ReportPdfController::class, 'finance'])->name('reports.finance.pdf');
        Route::get('/reports/profit-loss/pdf', [ReportPdfController::class, 'profitLoss'])->name('reports.profit-loss.pdf');
        Route::get('/reports/stocks/pdf', [ReportPdfController::class, 'stock'])->name('reports.stocks.pdf');
    });
});
