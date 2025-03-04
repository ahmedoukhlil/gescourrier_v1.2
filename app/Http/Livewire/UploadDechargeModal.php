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
    
    // Match listener pattern from CreateCourrierModal
    protected $listeners = [
        'openModal' => 'openModal'
    ];
    
    protected $rules = [
        'decharge' => 'required|file|max:10240', // 10MB max
        'dateReception' => 'nullable|date',
    ];

    public function mount()
    {
        // Initialize date to today
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
        
        // Verify the courrier sortant still exists
        if (!$this->courrierSortant) {
            $this->courrierSortant = CourrierSortant::find($this->courrierSortantId);
            if (!$this->courrierSortant) {
                session()->flash('error', 'Le courrier sélectionné n\'existe plus.');
                $this->closeModal();
                return;
            }
        }
        
        try {
            // Delete existing discharge if present
            if ($this->courrierSortant->decharge) {
                Storage::disk('public')->delete($this->courrierSortant->decharge);
            }
            
            // Save the new discharge
            $path = $this->decharge->store('decharges', 'public');
            
            $this->courrierSortant->update([
                'decharge' => $path,
                'decharge_manquante' => false,
                'date_reception_decharge' => $this->dateReception
            ]);
            
            // Follow the same pattern as CreateCourrierModal
            session()->flash('success', 'Décharge ajoutée avec succès.');
            
            $this->closeModal();
            $this->emit('dechargeUploaded');
        } catch (\Exception $e) {
            session()->flash('error', 'Erreur lors de l\'enregistrement de la décharge: ' . $e->getMessage());
        }
    }
    
    public function render()
    {
        return view('livewire.upload-decharge-modal');
    }
}