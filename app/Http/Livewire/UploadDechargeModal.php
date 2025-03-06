<?php

namespace App\Http\Livewire;

use App\Models\CourrierSortant;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class UploadDechargeModal extends Component
{
    use WithFileUploads;
    
    public $isOpen = false;
    public $courrierSortant = null;
    public $courrierSortantId = null;
    public $decharge = null;
    public $dateReception;
    
    // Événements écoutés
    protected $listeners = [
        'openModal' => 'openModal'
    ];
    
    protected $rules = [
        'decharge' => 'required|file|max:10240', // 10MB max
        'dateReception' => 'nullable|date',
    ];

    public function mount()
    {
        // Initialiser la date à aujourd'hui
        $this->dateReception = Carbon::today()->format('Y-m-d');
    }
    
    public function openModal($courrierSortantId)
    {
        $this->courrierSortantId = $courrierSortantId;
        $this->courrierSortant = CourrierSortant::find($courrierSortantId);
        $this->isOpen = true;
        $this->resetValidation();
        $this->reset(['decharge']);
        $this->dateReception = Carbon::today()->format('Y-m-d');
    }
    
    public function closeModal()
    {
        $this->isOpen = false;
    }
    
    public function save()
    {
        $this->validate();
        
        // Vérifier que le courrier sortant existe toujours
        if (!$this->courrierSortant) {
            $this->courrierSortant = CourrierSortant::find($this->courrierSortantId);
            if (!$this->courrierSortant) {
                session()->flash('error', 'Le courrier sélectionné n\'existe plus.');
                $this->closeModal();
                return;
            }
        }
        
        try {
            // Supprimer la décharge existante si présente
            if ($this->courrierSortant->decharge) {
                Storage::disk('public')->delete($this->courrierSortant->decharge);
            }
            
            // Enregistrer la nouvelle décharge
            $path = $this->decharge->store('decharges', 'public');
            
            $this->courrierSortant->update([
                'decharge' => $path,
                'decharge_manquante' => false,
                'date_reception_decharge' => $this->dateReception
            ]);
            
            session()->flash('success', 'Décharge ajoutée avec succès.');
            
            $this->closeModal();
            
            // Émettre un événement pour rafraîchir la liste
            $this->emit('dechargeUploaded');
            $this->emit('refreshCourriersSortants');
        } catch (\Exception $e) {
            session()->flash('error', 'Erreur lors de l\'enregistrement de la décharge: ' . $e->getMessage());
        }
    }
    
    public function render()
    {
        return view('livewire.upload-decharge-modal');
    }
}