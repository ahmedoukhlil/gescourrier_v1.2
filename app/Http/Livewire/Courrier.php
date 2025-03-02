<?php

namespace App\Http\Livewire;

use App\Models\CourriersEntrants;
use Livewire\Component;
use Livewire\WithPagination;

class Courrier extends Component
{
    use WithPagination;
    
    public function render()
    {
        // On charge les relations à l'intérieur de la méthode, pas avec ->with()
        $courriers = CourriersEntrants::query()
            ->with(['destinataireInterne', 'destinataires'])
            ->latest()
            ->paginate(10);
                                    
        return view('livewire.courrier', [
            'courriers' => $courriers
        ]);
    }
    public function showDocument($path)
{
    // Vérifiez que le fichier existe
    if (Storage::disk('public')->exists($path)) {
        return response()->file(storage_path('app/public/' . $path));
    }
    
    // Gérer le cas où le fichier n'existe pas
    abort(404, 'Document not found');
}
}