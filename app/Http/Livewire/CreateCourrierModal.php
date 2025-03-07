<?php

namespace App\Http\Livewire;

use App\Models\CourriersEntrants;
use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateCourrierModal extends Component
{
    use WithFileUploads;
    
    public $isOpen = false;
    
    public $expediteur;
    public $type = 'normal';
    public $objet;
    public $user_id; // Modifié de destinataire_id à user_id
    public $nom_dechargeur;
    public $document;
    public $additional_destinataires = [];
    
    protected $rules = [
        'expediteur' => 'required|string|max:255',
        'type' => 'required|in:urgent,confidentiel,normal',
        'objet' => 'required|string|max:255',
        'user_id' => 'required|exists:users,id', // Modifié
        'nom_dechargeur' => 'required|string|max:255',
        'document' => 'nullable|file|max:10240',
        'additional_destinataires' => 'nullable|array',
        'additional_destinataires.*' => 'exists:users,id', // Modifié
    ];
    
    public function openModal()
    {
        $this->isOpen = true;
        $this->resetValidation();
        $this->reset([
            'expediteur', 
            'type', 
            'objet', 
            'user_id', // Modifié
            'nom_dechargeur', 
            'document',
            'additional_destinataires'
        ]);
    }
    
    public function closeModal()
    {
        $this->isOpen = false;
    }
    
    public function render()
    {
        return view('livewire.create-courrier-modal', [
            'destinataires' => User::orderBy('name')->get() // Modifié pour utiliser User au lieu de Destinataire
        ]);
    }
    
    public function save()
    {
        $validatedData = $this->validate();
        
        $data = [
            'expediteur' => $this->expediteur,
            'type' => $this->type,
            'objet' => $this->objet,
            'user_id' => $this->user_id, // Modifié
            'nom_dechargeur' => $this->nom_dechargeur,
            'statut' => 'en_cours',
        ];
        
        // Gérer le document
        if ($this->document) {
            $data['document_path'] = $this->document->store('courriers', 'public');
        }
        
        // Créer le courrier
        $courrier = CourriersEntrants::create($data);
        
        // Associer les destinataires additionnels (cc)
        if (!empty($this->additional_destinataires)) {
            $courrier->destinataires()->attach($this->additional_destinataires);
        }
        
        session()->flash('success', 'Courrier créé avec succès. Numéro d\'ordre: ' . $courrier->numero_ordre);
        
        $this->reset();
        $this->closeModal();
        $this->emit('refreshCourriers');
    }
}