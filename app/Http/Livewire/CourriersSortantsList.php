<?php

namespace App\Http\Livewire;

use App\Models\CourrierSortant;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;

class CourriersSortantsList extends Component
{
    use WithPagination;
    
    protected $paginationTheme = 'tailwind';
    
    public $search = '';
    public $dateFrom = '';
    public $dateTo = '';
    public $filter = '';
    
    protected $listeners = [
        'courrierSortantCreated' => '$refresh',
        'dechargeUploaded' => '$refresh',
        'refreshCourriersSortants' => '$refresh',
        'delete' => 'delete'
    ];
    
    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function resetFilters()
    {
        $this->reset(['search', 'dateFrom', 'dateTo', 'filter']);
    }
    
    public function deleteConfirm($id)
    {
        $this->dispatchBrowserEvent('swal:confirm', [
            'title' => 'Êtes-vous sûr?',
            'text' => "Cette action est irréversible!",
            'id' => $id
        ]);
    }
    
    public function delete($id)
    {
        $courrierSortant = CourrierSortant::findOrFail($id);
        
        if ($courrierSortant->decharge) {
            Storage::disk('public')->delete($courrierSortant->decharge);
        }
        
        $courrierSortant->delete();
        
        session()->flash('success', 'Courrier sortant supprimé avec succès.');
    }
    
    // Méthode pour ouvrir le modal de décharge
    public function openDechargeModal($id)
    {
        $this->emit('openModal', $id);
    }
    
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
        
        $courriersSortants = $query->orderBy('date', 'desc')->paginate(10);
        
        return view('livewire.courriers-sortants-list', [
            'courriersSortants' => $courriersSortants
        ]);
    }
}