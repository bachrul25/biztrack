<?php

use App\Http\Controllers\Auth\AuthController;
use App\Livewire\BmcComponent;
use App\Livewire\CategoryComponent;
use App\Livewire\DashboardComponent;
use App\Livewire\FinanceComponent;
use App\Livewire\FinanceReportComponent;
use App\Livewire\ProductComponent;
use App\Livewire\ProfitLossReportComponent;
use App\Livewire\SaleComponent;
use App\Livewire\SalesPredictionComponent;
use App\Livewire\SalesReportComponent;
use App\Livewire\StockComponent;
use App\Livewire\StockReportComponent;
use App\Models\Sale;
use Barryvdh\DomPDF\Facade\Pdf;
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

    // Admin-only operational pages
    Route::middleware('role:admin')->group(function () {
        Route::get('/products', ProductComponent::class)->name('products');
        Route::get('/categories', CategoryComponent::class)->name('categories');
        Route::get('/stocks', StockComponent::class)->name('stocks');
        Route::get('/sales', SaleComponent::class)->name('sales');
        Route::get('/finances', FinanceComponent::class)->name('finances');
    });

    // Reports + predictions: admin + owner
    Route::middleware('role:admin,owner')->group(function () {
        Route::get('/reports/sales', SalesReportComponent::class)->name('reports.sales');
        Route::get('/reports/stocks', StockReportComponent::class)->name('reports.stocks');
        Route::get('/reports/finance', FinanceReportComponent::class)->name('reports.finance');
        Route::get('/reports/profit-loss', ProfitLossReportComponent::class)->name('reports.profit-loss');
        Route::get('/predictions/sales', SalesPredictionComponent::class)->name('predictions.sales');
        Route::get('/bmc', BmcComponent::class)->name('bmc');

        // Invoice PDF (simple sale detail print)
        Route::get('/sales/{sale}/invoice', function (Sale $sale) {
            $pdf = Pdf::loadView('pdf.invoice', ['sale' => $sale->load(['user', 'items.product'])])
                ->setPaper('a5', 'portrait');

            return $pdf->stream('invoice-'.$sale->invoice_number.'.pdf');
        })->name('sales.invoice');
    });
});
