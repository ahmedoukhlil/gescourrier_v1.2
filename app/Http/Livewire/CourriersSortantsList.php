<?php

namespace App\Http\Livewire;

use App\Models\CourrierSortant;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class CourriersSortantsList extends Component
{
    use WithPagination;
    use WithFileUploads; // Assurez-vous d'ajouter ce trait
    
    protected $paginationTheme = 'tailwind';
    
    public $search = '';
    public $dateFrom = '';
    public $dateTo = '';
    public $filter = '';
    
    // Variables pour le modal
    public $isModalOpen = false;
    public $selectedCourrier = null;
    public $decharge = null;
    
    protected $listeners = [
        'courrierSortantCreated' => '$refresh',
        'dechargeUploaded' => '$refresh',
        'refreshCourriersSortants' => '$refresh',
        'delete' => 'delete'
    ];
    
    protected $rules = [
        'decharge' => 'required|file|max:10240', // 10MB max
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
        $this->selectedCourrier = CourrierSortant::find($id);
        $this->isModalOpen = true;
        $this->resetValidation();
        $this->decharge = null;
    }
    
    // Méthode pour fermer le modal
    public function closeDechargeModal()
    {
        $this->isModalOpen = false;
        $this->selectedCourrier = null;
        $this->decharge = null;
    }
    
    // Méthode pour sauvegarder la décharge
    public function saveDecharge()
    {
        $this->validate();
        
        if (!$this->selectedCourrier) {
            return;
        }
        
        // Supprimer l'ancienne décharge si elle existe
        if ($this->selectedCourrier->decharge) {
            Storage::disk('public')->delete($this->selectedCourrier->decharge);
        }
        
        // Enregistrer la nouvelle décharge
        $path = $this->decharge->store('decharges', 'public');
        
        $this->selectedCourrier->update([
            'decharge' => $path,
            'decharge_manquante' => false
        ]);
        
        $this->closeDechargeModal();
        session()->flash('success', 'Décharge ajoutée avec succès.');
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