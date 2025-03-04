<?php

namespace App\Http\Livewire;

use App\Models\CourrierSortant;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;

class CourriersSortantsList extends Component
{
    use WithPagination;
    
    // Match the pagination theme used in CourriersList
    protected $paginationTheme = 'tailwind';
    
    public $search = '';
    public $dateFrom = '';
    public $dateTo = '';
    public $filter = '';
    
    // Include the same queryString approach as in CourriersList
    protected $queryString = [
        'search' => ['except' => ''],
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
        'filter' => ['except' => '']
    ];
    
    // Match the listeners pattern in CourriersList
    protected $listeners = [
        'courrierSortantCreated' => '$refresh',
        'dechargeUploaded' => '$refresh',
        'delete' => 'delete'
    ];
    
    // Reset page when updating search
    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    // Reset page when updating filter
    public function updatingFilter()
    {
        $this->resetPage();
    }
    
    // Reset page when updating date filters
    public function updatingDateFrom()
    {
        $this->resetPage();
    }
    
    public function updatingDateTo()
    {
        $this->resetPage();
    }
    
    // Reset all filters
    public function resetFilters()
    {
        $this->reset(['search', 'dateFrom', 'dateTo', 'filter']);
        $this->resetPage();
    }
    
    // Confirm deletion modal
    public function deleteConfirm($id)
    {
        $this->dispatchBrowserEvent('swal:confirm', [
            'title' => 'Êtes-vous sûr?',
            'text' => "Cette action est irréversible!",
            'id' => $id
        ]);
    }
    
    // Handle delete action
    public function delete($id)
    {
        $courrierSortant = CourrierSortant::findOrFail($id);
        
        if ($courrierSortant->decharge) {
            Storage::disk('public')->delete($courrierSortant->decharge);
        }
        
        $courrierSortant->delete();
        
        session()->flash('success', 'Courrier sortant supprimé avec succès.');
    }
    
    // Open discharge modal
    public function openDechargeModal($id)
    {
        $this->emitTo('upload-decharge-modal', 'openModal', $id);
    }
    
    // Render function following CourriersList pattern
    public function render()
    {
        $query = CourrierSortant::with('courrierEntrant');
        
        if ($this->search) {
            $query->where(function($q) {
                $q->where('destinataire', 'like', '%' . $this->search . '%')
                  ->orWhere('objet', 'like', '%' . $this->search . '%')
                  ->orWhere('numero', 'like', '%' . $this->search . '%');
            });
        }
        
        if ($this->dateFrom) {
            $query->whereDate('date', '>=', $this->dateFrom);
        }
        
        if ($this->dateTo) {
            $query->whereDate('date', '<=', $this->dateTo);
        }
        
        if ($this->filter === 'with-decharge') {
            $query->where('decharge_manquante', false);
        } elseif ($this->filter === 'without-decharge') {
            $query->where('decharge_manquante', true);
        }
        
        return view('livewire.courriers-sortants-list', [
            'courriersSortants' => $query->orderBy('date', 'desc')->paginate(10)
        ]);
    }
}