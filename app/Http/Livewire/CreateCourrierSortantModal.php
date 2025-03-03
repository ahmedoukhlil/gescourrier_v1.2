<?php
// app/Http/Livewire/CreateCourrierSortantModal.php

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
    
    // Propriétés pour l'autocomplétion des destinataires
    public $destinataireSearch = '';
    public $destinatairesResults = [];
    public $showDestinataireDropdown = false;
    public $selectedDestinataire = null;
    
    protected $rules = [
        'objet' => 'required|string|max:255',
        'destinataireSearch' => 'required|string|max:255', // Validation sur le champ de recherche
        'date' => 'required|date',
        'courrier_entrant_id' => 'nullable|exists:courriers_entrants,id',
    ];

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
    
    public function updatedCourrierEntrantId()
    {
        if ($this->courrier_entrant_id) {
            $courrierEntrant = CourriersEntrants::find($this->courrier_entrant_id);
            if ($courrierEntrant) {
                $this->objet = 'Réponse à: ' . $courrierEntrant->objet;
                $this->destinataireSearch = $courrierEntrant->expediteur;
                $this->destinataire = $courrierEntrant->expediteur;
                
                // Vérifier si ce destinataire existe déjà
                $this->checkExistingDestinataire();
            }
        }
    }
    
    public function updatedDestinataireSearch()
    {
        $this->destinataire = $this->destinataireSearch;
        
        if (strlen($this->destinataireSearch) > 2) {
            $this->destinatairesResults = DestinataireSortant::where('nom', 'like', '%' . $this->destinataireSearch . '%')
                ->orWhere('organisation', 'like', '%' . $this->destinataireSearch . '%')
                ->take(5)
                ->get();
            $this->showDestinataireDropdown = true;
        } else {
            $this->destinatairesResults = [];
            $this->showDestinataireDropdown = false;
            $this->selectedDestinataire = null;
        }
    }
    
    public function selectDestinataire($id)
    {
        $destinataire = DestinataireSortant::find($id);
        if ($destinataire) {
            $this->selectedDestinataire = $destinataire;
            $this->destinataireSearch = $destinataire->nom;
            $this->destinataire = $destinataire->nom;
        }
        $this->showDestinataireDropdown = false;
    }
    
    private function checkExistingDestinataire()
    {
        // Chercher si le destinataire existe déjà
        $existingDestinataire = DestinataireSortant::where('nom', $this->destinataireSearch)->first();
        if ($existingDestinataire) {
            $this->selectedDestinataire = $existingDestinataire;
        } else {
            $this->selectedDestinataire = null;
        }
    }
    
    public function save()
    {
        $this->validate();
        
        // Vérifier ou créer le destinataire
        $destinataireId = null;
        $this->destinataire = $this->destinataireSearch; // S'assurer que destinataire est à jour
        
        if ($this->selectedDestinataire) {
            $destinataireId = $this->selectedDestinataire->id;
        } else {
            // Si le destinataire n'existe pas, on le crée
            $destinataire = DestinataireSortant::create([
                'nom' => $this->destinataire,
                'organisation' => null, // Vous pouvez ajouter d'autres champs si nécessaire
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
        
        $this->closeModal();
        $this->emit('courrierSortantCreated');
        
        session()->flash('success', 'Courrier sortant créé avec succès.');
    }
    
    public function render()
    {
        return view('livewire.create-courrier-sortant-modal', [
            'courriersEntrants' => CourriersEntrants::orderBy('created_at', 'desc')->get()
        ]);
    }
}