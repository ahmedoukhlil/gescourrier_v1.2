<?php

namespace App\Http\Livewire;

use App\Models\CourriersEntrants;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;

class CourriersList extends Component
{
    use WithPagination;
    
    // Variables de filtrage
    public $search = '';
    public $status = '';
    public $dateFrom = '';
    public $dateTo = '';
    public $type = '';
    
    // Variable pour le mode d'affichage (tableau ou cartes)
    public $viewMode = 'table'; // 'table' ou 'cards'
    
    // Variable pour l'ouverture du modal document
    public $selectedDocument = null;
    public $showDocumentModal = false;
    
    // Variables pour le tri
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    
    // Paramètres d'URL pour conserver les filtres lors de la navigation
    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
        'type' => ['except' => ''],
        'viewMode' => ['except' => 'table'],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];
    
    // Écouteurs d'événements
    protected $listeners = [
        'refreshCourriers' => '$refresh',
        'delete' => 'delete',
        'toggleViewMode' => 'toggleViewMode',
        'sortBy' => 'sortBy'
    ];
    
    // Réinitialisation de la pagination lors de la mise à jour des filtres
    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function updatingStatus()
    {
        $this->resetPage();
    }
    
    public function updatingDateFrom()
    {
        $this->resetPage();
    }
    
    public function updatingDateTo()
    {
        $this->resetPage();
    }
    
    public function updatingType()
    {
        $this->resetPage();
    }
    
    // Méthode pour afficher le document dans un modal
    public function viewDocument($documentPath)
    {
        $this->selectedDocument = $documentPath;
        $this->showDocumentModal = true;
    }
    
    // Méthode pour fermer le modal document
    public function closeDocumentModal()
    {
        $this->showDocumentModal = false;
        $this->selectedDocument = null;
    }
    
    // Méthode pour changer le mode d'affichage
    public function toggleViewMode($mode = null)
    {
        if ($mode) {
            $this->viewMode = $mode;
        } else {
            $this->viewMode = $this->viewMode === 'table' ? 'cards' : 'table';
        }
    }
    
    // Méthode pour changer le tri
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }
    
    // Méthode de rendu principal
    public function render()
    {
        $query = CourriersEntrants::with(['destinataireInterne', 'destinataires', 'annotations', 'responseDrafts']);
        
        // Appliquer les filtres
        if ($this->search) {
            $query->where(function($q) {
                $q->where('expediteur', 'like', '%' . $this->search . '%')
                  ->orWhere('numero_ordre', 'like', '%' . $this->search . '%')
                  ->orWhere('objet', 'like', '%' . $this->search . '%')
                  ->orWhereHas('destinataireInterne', function($q2) {
                      $q2->where('name', 'like', '%' . $this->search . '%');
                  });
            });
        }
        
        if ($this->status) {
            $query->where('statut', $this->status);
        }
        
        if ($this->type) {
            $query->where('type', $this->type);
        }
        
        if ($this->dateFrom) {
            $query->whereDate('created_at', '>=', $this->dateFrom);
        }
        
        if ($this->dateTo) {
            $query->whereDate('created_at', '<=', $this->dateTo);
        }
        
        // Appliquer le tri
        $query->orderBy($this->sortField, $this->sortDirection);
        
        // Paginer les résultats
        $courriers = $query->paginate(10);
    
        return view('livewire.courriers-list', [
            'courriers' => $courriers,
            'destinataires' => User::orderBy('name')->get()
        ]);
    }
    
    // Méthode pour confirmer la suppression
    public function deleteConfirm($id)
    {
        $this->dispatchBrowserEvent('swal:confirm', [
            'title' => 'Êtes-vous sûr?',
            'text' => "Cette action est irréversible!",
            'id' => $id
        ]);
    }
    
    // Méthode pour supprimer un courrier
    public function delete($id)
    {
        $courrier = CourriersEntrants::findOrFail($id);
        
        // Supprimer le document associé s'il existe
        if ($courrier->document_path) {
            Storage::disk('public')->delete($courrier->document_path);
        }
        
        $courrier->delete();
        
        session()->flash('success', 'Courrier supprimé avec succès.');
    }
    
    // Méthode pour réinitialiser tous les filtres
    public function resetFilters()
    {
        $this->reset(['search', 'status', 'dateFrom', 'dateTo', 'type']);
        $this->resetPage();
    }
}