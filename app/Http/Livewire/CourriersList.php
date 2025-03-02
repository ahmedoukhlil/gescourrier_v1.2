<?php

namespace App\Http\Livewire;

use App\Models\CourriersEntrants;
use App\Models\Destinataire;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;

class CourriersList extends Component
{
    use WithPagination;
    
    public $search = '';
    public $status = '';
    public $dateFrom = '';
    public $dateTo = '';
    public $type = '';
    
    public $selectedDocument = null;
    public $showDocumentModal = false;
    
    protected $queryString = ['search', 'status', 'dateFrom', 'dateTo', 'type'];
    
    protected $listeners = ['refreshCourriers' => '$refresh', 'delete' => 'delete'];
    
    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function viewDocument($documentPath)
    {
        $this->selectedDocument = $documentPath;
        $this->showDocumentModal = true;
    }
    
    public function closeDocumentModal()
    {
        $this->showDocumentModal = false;
        $this->selectedDocument = null;
    }
    
    public function render()
    {
        $query = CourriersEntrants::with(['destinataireInterne', 'destinataires']);
        
        if ($this->search) {
            $query->where(function($q) {
                $q->where('expediteur', 'like', '%' . $this->search . '%')
                  ->orWhere('numero_ordre', 'like', '%' . $this->search . '%')
                  ->orWhere('objet', 'like', '%' . $this->search . '%')
                  ->orWhereHas('destinataireInterne', function($q2) {
                      $q2->where('nom', 'like', '%' . $this->search . '%');
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
        
        $courriers = $query->latest()->paginate(10);
        
        return view('livewire.courriers-list', [
            'courriers' => $courriers,
            'destinataires' => Destinataire::orderBy('nom')->get()
        ]);
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
        $courrier = CourriersEntrants::findOrFail($id);
        
        // Supprimer le document associé s'il existe
        if ($courrier->document_path) {
            Storage::disk('public')->delete($courrier->document_path);
        }
        
        $courrier->delete();
        
        session()->flash('success', 'Courrier supprimé avec succès.');
    }
    
    public function resetFilters()
    {
        $this->reset(['search', 'status', 'dateFrom', 'dateTo', 'type']);
    }
}