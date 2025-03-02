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
    
    // Propriétés pour l'autocomplétion des destinataires
    public $destinataireSearch = '';
    public $destinatairesResults = [];
    public $showDestinataireDropdown = false;
    public $selectedDestinataire = null;
    
    protected $rules = [
        'objet' => 'required|string|max:255',
        'destinataire' => 'required|string|max:255',
        'date' => 'required|date',
        'courrier_entrant_id' => 'nullable|exists:courriers_entrants,id',
    ];
    
    public function mount()
    {
        // Initialiser la date à aujourd'hui
        $this->date = Carbon::today()->format('Y-m-d');
    }
    
    public function openModal()
    {
        $this->isOpen = true;
        $this->resetExcept('date');
    }
    
    public function closeModal()
    {
        $this->isOpen = false;
    }
    
    public function updatedCourrierEntrantId()
    {
        if ($this->courrier_entrant_id) {
            $courrierEntrant = CourriersEntrants::find($this->courrier_entrant_id);
            if ($courrierEntrant) {
                $this->objet = 'Réponse à: ' . $courrierEntrant->objet;
                $this->destinataire = $courrierEntrant->expediteur;
                $this->destinataireSearch = $courrierEntrant->expediteur;
            }
        }
    }
    
    public function updatedDestinataireSearch()
    {
        if (strlen($this->destinataireSearch) > 2) {
            $this->destinatairesResults = DestinataireSortant::where('nom', 'like', '%' . $this->destinataireSearch . '%')
                ->orWhere('organisation', 'like', '%' . $this->destinataireSearch . '%')
                ->take(5)
                ->get();
            $this->showDestinataireDropdown = true;
        } else {
            $this->destinatairesResults = [];
            $this->showDestinataireDropdown = false;
        }
    }
    
    public function selectDestinataire($id)
    {
        $destinataire = DestinataireSortant::find($id);
        if ($destinataire) {
            $this->selectedDestinataire = $destinataire;
            $this->destinataire = $destinataire->nom;
            $this->destinataireSearch = $destinataire->nom;
        }
        $this->showDestinataireDropdown = false;
    }
    
    public function save()
    {
        $this->validate();
        
        // Vérifier si le destinataire existe déjà
        $destinataireId = null;
        if ($this->selectedDestinataire) {
            $destinataireId = $this->selectedDestinataire->id;
        } else {
            // Si le destinataire n'existe pas, on le crée
            $destinataire = DestinataireSortant::create([
                'nom' => $this->destinataire,
            ]);
            $destinataireId = $destinataire->id;
        }
        
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