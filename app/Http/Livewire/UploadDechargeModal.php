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
    public $courrierSortant;
    public $courrierSortantId;
    public $decharge;
    
    protected $rules = [
        'decharge' => 'required|file|max:10240', // 10MB max
    ];
    
    // Modifier cette ligne pour écouter 'ouvrirModal' au lieu de 'openModal'
    protected $listeners = ['ouvrirModal' => 'openModal'];
    
    public function openModal($courrierSortantId)
    {
        // Ajouter un log pour le débogage
        \Log::info('openModal appelé avec ID: ' . $courrierSortantId);
        
        $this->courrierSortantId = $courrierSortantId;
        $this->courrierSortant = CourrierSortant::find($courrierSortantId);
        $this->isOpen = true;
        $this->resetValidation();
        $this->reset(['decharge']);
    }
    
    public function closeModal()
    {
        $this->isOpen = false;
    }
    
    public function save()
    {
        $this->validate();
        
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