<?php

namespace App\Livewire;

use App\Models\CompanyStructure;
use App\Models\Product;
use App\Models\Service;
use Livewire\Component;

class LandingPageComponent extends Component
{
    public string $brandFilter = '';

    public function render()
    {
        $structures = CompanyStructure::where('status', 'active')
            ->orderBy('sort_order')
            ->get();

        $productsQuery = Product::where('status', 'active');
        if ($this->brandFilter) {
            $productsQuery->where('brand', $this->brandFilter);
        }
        $products = $productsQuery->latest()->take(6)->get();

        $services = Service::where('status', 'active')->latest()->take(4)->get();

        return view('livewire.landing-page-component', [
            'structures' => $structures,
            'products' => $products,
            'services' => $services,
        ])->layout('layouts.app', ['title' => 'PT Bikin Orang Bahagia - Company Profile']);
    }
}
