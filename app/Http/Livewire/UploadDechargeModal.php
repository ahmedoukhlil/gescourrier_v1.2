<?php

namespace App\Http\Livewire;

use App\Models\CourrierSortant;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class UploadDechargeModal extends Component
{
    use WithFileUploads;
    
    public $isOpen = false;
    public $courrierSortant = null;
    public $courrierSortantId = null;
    public $decharge = null;
    
    protected $listeners = [
        'openModal' => 'openModal'
    ];
    
    protected $rules = [
        'decharge' => 'required|file|max:10240', // 10MB max
    ];
    
    public function openModal($courrierSortantId)
    {
        $this->courrierSortantId = $courrierSortantId;
        $this->courrierSortant = CourrierSortant::find($courrierSortantId);
        $this->isOpen = true;
        $this->resetValidation();
        $this->reset(['decharge']);
    }
    
    public function closeModal()
    {
        $this->isOpen = false;
        $this->courrierSortant = null;
        $this->courrierSortantId = null;
        $this->decharge = null;
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
        
        // Supprimer l'ancienne décharge si elle existe
        if ($this->courrierSortant->decharge) {
            Storage::disk('public')->delete($this->courrierSortant->decharge);
        }
        
        // Enregistrer la nouvelle décharge
        $path = $this->decharge->store('decharges', 'public');
        
        $this->courrierSortant->update([
            'decharge' => $path,
            'decharge_manquante' => false
        ]);
        
        $this->closeModal();
        $this->emit('dechargeUploaded');
        
        session()->flash('success', 'Décharge ajoutée avec succès.');
    }
    
    public function render()
    {
        return view('livewire.upload-decharge-modal');
    }
}