<?php

namespace App\Livewire;

use App\Models\Finance;
use App\Repositories\FinanceRepository;
use App\Services\FinanceService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class FinanceComponent extends Component
{
    use WithPagination;

    #[Url(as: 'type')]
    public ?string $filterType = null;

    #[Url(as: 'from')]
    public ?string $dateFrom = null;

    #[Url(as: 'to')]
    public ?string $dateTo = null;

    #[Url(as: 'q')]
    public string $search = '';

    public bool $showModal = false;

    public ?int $editingId = null;

    public string $type = 'expense';

    public ?string $category = null;

    public string $description = '';

    public float $amount = 0;

    public ?string $transaction_date = null;

    public function mount(): void
    {
        $this->transaction_date = now()->toDateString();
    }

    protected function rules(): array
    {
        return [
            'type' => ['required', 'in:income,expense'],
            'category' => ['nullable', 'string', 'max:100'],
            'description' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'transaction_date' => ['required', 'date'],
        ];
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterType(): void
    {
        $this->resetPage();
    }

    public function openCreate(string $type = 'expense'): void
    {
        $this->reset(['editingId', 'category', 'description', 'amount']);
        $this->type = $type;
        $this->category = $type === 'expense' ? 'Bahan Baku' : 'Pemasukan Lain';
        $this->transaction_date = now()->toDateString();
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $f = Finance::findOrFail($id);
        $this->editingId = $f->id;
        $this->type = $f->type;
        $this->category = $f->category;
        $this->description = $f->description;
        $this->amount = (float) $f->amount;
        $this->transaction_date = $f->transaction_date?->toDateString();
        $this->showModal = true;
    }

    public function save(): void
    {
        $data = $this->validate();
        /** @var FinanceService $svc */
        $svc = app(FinanceService::class);

        if ($this->editingId) {
            $finance = Finance::findOrFail($this->editingId);
            if ($finance->source === 'sale') {
                $this->dispatch('swal', icon: 'error', title: 'Tidak bisa diubah', text: 'Data pemasukan yang berasal dari penjualan hanya bisa dihapus dengan membatalkan transaksi.');

                return;
            }
            $svc->update($finance, $data);
            $this->dispatch('swal', icon: 'success', title: 'Data keuangan diperbarui');
        } else {
            $svc->create($data);
            $this->dispatch('swal', icon: 'success', title: 'Data keuangan ditambahkan');
        }
        $this->showModal = false;
    }

    public function delete(int $id): void
    {
        try {
            app(FinanceService::class)->delete(Finance::findOrFail($id));
            $this->dispatch('swal', icon: 'success', title: 'Data keuangan dihapus');
        } catch (\DomainException $e) {
            $this->dispatch('swal', icon: 'error', title: 'Gagal menghapus', text: $e->getMessage());
        }
    }

    public function render()
    {
        $repo = app(FinanceRepository::class);

        return view('livewire.finance-component', [
            'finances' => $repo->paginate($this->filterType, $this->dateFrom, $this->dateTo, $this->search ?: null, 10),
            'totalIncome' => $repo->totalIncome($this->dateFrom, $this->dateTo),
            'totalExpense' => $repo->totalExpense($this->dateFrom, $this->dateTo),
            'expenseCategories' => Finance::EXPENSE_CATEGORIES,
        ]);
    }
}
