<?php

namespace App\Http\Livewire;

use App\Models\CourrierSortant;
use App\Models\CourriersEntrants;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;

class CourriersSortantsList extends Component
{
    use WithPagination;
    
    // Définir explicitement le thème de pagination
    protected $paginationTheme = 'tailwind';
    
    // Variables pour les filtres
    public $search = '';
    public $dateFrom = '';
    public $dateTo = '';
    public $filter = '';
    
    // Définir les paramètres d'URL pour les filtres
    protected $queryString = [
        'search' => ['except' => ''],
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
        'filter' => ['except' => '']
    ];
    
    // Listeners pour les événements Livewire
    protected $listeners = [
        'courrierSortantCreated' => '$refresh',
        'dechargeUploaded' => '$refresh',
        'delete' => 'delete'
    ];
    
    // Réinitialiser la pagination lors de la mise à jour des filtres
    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function updatingFilter()
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
    
    // Réinitialiser tous les filtres
    public function resetFilters()
    {
        $this->reset(['search', 'dateFrom', 'dateTo', 'filter']);
        $this->resetPage();
    }
    
    // Confirmation de suppression
    public function deleteConfirm($id)
    {
        $this->dispatchBrowserEvent('swal:confirm', [
            'title' => 'Êtes-vous sûr?',
            'text' => "Cette action est irréversible!",
            'id' => $id
        ]);
    }
    
    // Supprimer un courrier sortant
    public function delete($id)
    {
        try {
            $courrierSortant = CourrierSortant::findOrFail($id);
            
            // Supprimer la décharge associée si elle existe
            if ($courrierSortant->decharge) {
                Storage::disk('public')->delete($courrierSortant->decharge);
            }
            
            $courrierSortant->delete();
            
            session()->flash('success', 'Courrier sortant supprimé avec succès.');
        } catch (\Exception $e) {
            session()->flash('error', 'Erreur lors de la suppression: ' . $e->getMessage());
        }
    }
    
    // Ouvrir le modal pour ajouter une décharge
    public function openDechargeModal($id)
    {
        $this->emit('openModal', $id);
    }
    
    // Méthode de rendu qui construit la requête avec les filtres
    public function render()
    {
        // Construire la requête de base avec la relation
        $query = CourrierSortant::with('courrierEntrant');
        
        // Appliquer le filtre de recherche
        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('destinataire', 'like', '%' . $this->search . '%')
                  ->orWhere('objet', 'like', '%' . $this->search . '%')
                  ->orWhere('numero', 'like', '%' . $this->search . '%');
            });
        }
        
        // Appliquer les filtres de date
        if (!empty($this->dateFrom)) {
            $query->whereDate('date', '>=', $this->dateFrom);
        }
        
        if (!empty($this->dateTo)) {
            $query->whereDate('date', '<=', $this->dateTo);
        }
        
        // Appliquer le filtre de décharge
        if ($this->filter === 'with-decharge') {
            $query->where('decharge_manquante', false);
        } elseif ($this->filter === 'without-decharge') {
            $query->where('decharge_manquante', true);
        }
        
        // Obtenir les résultats paginés
        $courriersSortants = $query->orderBy('date', 'desc')->paginate(10);
        
        // Journaliser le nombre de résultats (pour le débogage)
        \Log::info('Nombre de courriers sortants trouvés: ' . $courriersSortants->total());
        
        // Rendre la vue avec les résultats
        return view('livewire.courriers-sortants-list', [
            'courriersSortants' => $courriersSortants
        ]);
    }
}