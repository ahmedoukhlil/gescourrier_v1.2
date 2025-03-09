<?php

namespace App\Http\Livewire;

use App\Models\CourriersEntrants;
use Livewire\Component;

class QuickSearch extends Component
{
    public $searchTerm = '';
    public $searchResults = [];
    public $showResults = false;
    
    public function updatedSearchTerm()
    {
        $this->resetPage();
        
        if (strlen($this->searchTerm) < 3) {
            $this->searchResults = [];
            $this->showResults = false;
            return;
        }
        
        $this->searchResults = CourriersEntrants::where('numero_ordre', 'like', '%' . $this->searchTerm . '%')
                                               ->orWhere('expediteur', 'like', '%' . $this->searchTerm . '%')
                                               ->orWhere('objet', 'like', '%' . $this->searchTerm . '%')
                                               ->limit(5)
                                               ->get();
                                               
        $this->showResults = true;
    }
    
    public function resetPage()
    {
        $this->reset(['searchResults']);
    }
    
    public function hideResults()
    {
        $this->showResults = false;
    }
    
    public function render()
    {
        return view('livewire.quick-search');
    }
}