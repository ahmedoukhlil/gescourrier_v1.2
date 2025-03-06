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
    
    // Propriétés pour l'autocomplétion
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

    // Événements écoutés
    protected $listeners = [
        'closeDropdowns' => 'closeDropdowns'
    ];

    public function closeDropdowns()
    {
        $this->showDestinataireDropdown = false;
    }

    public function mount()
    {
        // Initialiser la date à aujourd'hui
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
    
    // Méthode pour l'autocomplétion des destinataires
    public function updatedDestinataireSearch()
    {
        if (strlen($this->destinataireSearch) > 2) {
            $this->showDestinataireDropdown = true;
            $this->destinatairesResults = DestinataireSortant::where('nom', 'like', '%' . $this->destinataireSearch . '%')
                ->orWhere('organisation', 'like', '%' . $this->destinataireSearch . '%')
                ->take(5)
                ->get();
        } else {
            $this->showDestinataireDropdown = false;
            $this->destinatairesResults = [];
        }
    }
    
    // Sélectionner un destinataire depuis l'autocomplétion
    public function selectDestinataire($id)
    {
        $destinataire = DestinataireSortant::find($id);
        if ($destinataire) {
            $this->selectedDestinataire = $destinataire;
            $this->destinataireSearch = $destinataire->nom;
            $this->showDestinataireDropdown = false;
        }
    }
    
    public function save()
    {
        $this->validate();
        
        try {
            // Vérifier ou créer le destinataire
            $destinataireId = null;
            $this->destinataire = $this->destinataireSearch;
            
            if ($this->selectedDestinataire) {
                $destinataireId = $this->selectedDestinataire->id;
            } else {
                // Créer un nouveau destinataire si nécessaire
                $destinataire = DestinataireSortant::create([
                    'nom' => $this->destinataire,
                    'organisation' => null,
                ]);
                $destinataireId = $destinataire->id;
            }
            
            // Créer le courrier sortant
            CourrierSortant::create([
                'objet' => $this->objet,
                'destinataire' => $this->destinataire,
                'destinataire_sortant_id' => $destinataireId,
                'date' => $this->date,
                'courrier_entrant_id' => $this->courrier_entrant_id,
                'decharge_manquante' => true
            ]);
            
            session()->flash('success', 'Courrier sortant créé avec succès.');
            
            $this->closeModal();
            
            // Émettre des événements pour rafraîchir la liste
            $this->emit('courrierSortantCreated');
            $this->emit('refreshCourriersSortants');
        } catch (\Exception $e) {
            session()->flash('error', 'Erreur lors de la création du courrier: ' . $e->getMessage());
        }
    }
    
    public function render()
    {
        return view('livewire.create-courrier-sortant-modal', [
            'courriersEntrants' => CourriersEntrants::orderBy('created_at', 'desc')->get()
        ]);
    }
}