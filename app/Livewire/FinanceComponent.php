<?php

namespace App\Livewire;

use App\Models\Finance;
use App\Repositories\FinanceRepository;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Keuangan')]
class FinanceComponent extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url(as: 'type')]
    public string $typeFilter = '';

    #[Url(as: 'dari')]
    public string $dateFrom = '';

    #[Url(as: 'sampai')]
    public string $dateTo = '';

    public bool $showModal = false;
    public bool $editMode = false;
    public ?int $financeId = null;

    public string $type = 'expense';
    public string $amount = '';
    public string $description = '';
    public string $source = '';
    public string $date = '';

    public array $sources = [
        'Bahan Baku',
        'Operasional',
        'Listrik',
        'Transportasi',
        'Kemasan',
        'Gaji',
        'Lain-lain',
    ];

    public function mount(): void
    {
        $this->date = now()->toDateString();
    }

    protected function rules(): array
    {
        return [
            'type' => ['required', 'in:income,expense'],
            'amount' => ['required', 'numeric', 'min:0'],
            'description' => ['required', 'string', 'max:255'],
            'source' => ['required', 'string', 'max:100'],
            'date' => ['required', 'date'],
        ];
    }

    protected array $messages = [
        'type.required' => 'Jenis transaksi wajib dipilih.',
        'amount.required' => 'Jumlah wajib diisi.',
        'amount.numeric' => 'Jumlah harus angka.',
        'description.required' => 'Deskripsi wajib diisi.',
        'source.required' => 'Sumber wajib diisi.',
        'date.required' => 'Tanggal wajib diisi.',
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingTypeFilter(): void
    {
        $this->resetPage();
    }

    public function updatingDateFrom(): void
    {
        $this->resetPage();
    }

    public function updatingDateTo(): void
    {
        $this->resetPage();
    }

    public function openCreate(): void
    {
        $this->resetForm();
        $this->editMode = false;
        $this->showModal = true;
    }

    public function edit(int $id): void
    {
        $finance = Finance::findOrFail($id);
        $this->financeId = $finance->id;
        $this->type = $finance->type;
        $this->amount = (string) $finance->amount;
        $this->description = $finance->description;
        $this->source = $finance->source;
        $this->date = $finance->date?->toDateString() ?? now()->toDateString();
        $this->editMode = true;
        $this->showModal = true;
    }

    public function store(FinanceRepository $repo): void
    {
        $data = $this->validate();
        if ($this->editMode && $this->financeId) {
            $repo->update(Finance::findOrFail($this->financeId), $data);
            $this->dispatch('swal', icon: 'success', title: 'Data keuangan diperbarui');
        } else {
            $repo->create($data);
            $this->dispatch('swal', icon: 'success', title: 'Data keuangan ditambahkan');
        }
        $this->showModal = false;
        $this->resetForm();
    }

    public function delete(int $id, FinanceRepository $repo): void
    {
        $repo->delete(Finance::findOrFail($id));
        $this->dispatch('swal', icon: 'success', title: 'Data keuangan dihapus');
    }

    public function resetForm(): void
    {
        $this->reset(['financeId', 'type', 'amount', 'description', 'source', 'editMode']);
        $this->type = 'expense';
        $this->date = now()->toDateString();
        $this->resetErrorBag();
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function render(FinanceRepository $repo): View
    {
        $from = $this->dateFrom ?: null;
        $to = $this->dateTo ?: null;

        return view('livewire.finance-component', [
            'finances' => $repo->paginate($this->search, $this->typeFilter ?: null, $from, $to, 10),
            'totalIncome' => $repo->totalIncome($from, $to),
            'totalExpense' => $repo->totalExpense($from, $to),
            'netProfit' => $repo->netProfit($from, $to),
        ]);
    }
}
