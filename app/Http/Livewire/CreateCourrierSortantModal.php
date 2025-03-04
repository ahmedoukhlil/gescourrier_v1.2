<?php

namespace App\Http\Livewire;

use App\Models\CourrierSortant;
use App\Models\CourriersEntrants;
use App\Models\DestinataireSortant;
use Livewire\Component;
use Illuminate\Support\Carbon;

class CreateCourrierSortantModal extends Component
{
    public $isOpen = false;
    
    public $objet = '';
    public $destinataire = '';
    public $date;
    public $courrier_entrant_id = null;
    
    // Properties for autocomplete
    public $destinataireSearch = '';
    public $destinatairesResults = [];
    public $showDestinataireDropdown = false;
    public $selectedDestinataire = null;
    
    protected $rules = [
        'objet' => 'required|string|max:255',
        'destinataireSearch' => 'required|string|max:255',
        'date' => 'required|date',
        'courrier_entrant_id' => 'nullable|exists:courriers_entrants,id',
    ];

    // Match listener pattern from CreateCourrierModal
    protected $listeners = [
        'closeDropdowns' => 'closeDropdowns'
    ];

    public function closeDropdowns()
    {
        $this->showDestinataireDropdown = false;
    }

    public function mount()
    {
        // Initialize date to today
        $this->date = Carbon::today()->format('Y-m-d');
    }
    
    public function openModal()
    {
        $this->isOpen = true;
        $this->reset(['objet', 'destinataireSearch', 'destinataire', 'courrier_entrant_id', 
                     'destinatairesResults', 'showDestinataireDropdown', 'selectedDestinataire']);
        $this->date = Carbon::today()->format('Y-m-d');
    }
    
    public function closeModal()
    {
        $this->isOpen = false;
        $this->resetValidation();
    }
    
    // (Rest of the methods remain unchanged)
    
    public function save()
    {
        $this->validate();
        
        // Verify or create the recipient
        $destinataireId = null;
        $this->destinataire = $this->destinataireSearch;
        
        if ($this->selectedDestinataire) {
            $destinataireId = $this->selectedDestinataire->id;
        } else {
            // Create new recipient if needed
            $destinataire = DestinataireSortant::create([
                'nom' => $this->destinataire,
                'organisation' => null,
            ]);
            $destinataireId = $destinataire->id;
        }
        
        // Create outgoing mail
        CourrierSortant::create([
            'objet' => $this->objet,
            'destinataire' => $this->destinataire,
            'destinataire_sortant_id' => $destinataireId,
            'date' => $this->date,
            'courrier_entrant_id' => $this->courrier_entrant_id,
            'decharge_manquante' => true
        ]);
        
        // Follow same pattern as in incoming mail component
        session()->flash('success', 'Courrier sortant créé avec succès.');
        
        $this->closeModal();
        $this->emit('courrierSortantCreated');
    }
    
    public function render()
    {
        return view('livewire.create-courrier-sortant-modal', [
            'courriersEntrants' => CourriersEntrants::orderBy('created_at', 'desc')->get()
        ]);
    }
}