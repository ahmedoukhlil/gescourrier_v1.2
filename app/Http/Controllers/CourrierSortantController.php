<?php

namespace App\Http\Controllers;

use App\Models\CourrierSortant;
use App\Models\CourriersEntrants;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CourrierSortantController extends Controller
{
    public function index()
    {
        return view('courriers-sortants.index');
    }

    public function show(CourrierSortant $courrierSortant)
    {
        $courrierSortant->load('courrierEntrant');
        return view('courriers-sortants.show', compact('courrierSortant'));
    }

    public function edit(CourrierSortant $courrierSortant)
    {
        $courriersEntrants = CourriersEntrants::orderBy('created_at', 'desc')->get();
        return view('courriers-sortants.edit', compact('courrierSortant', 'courriersEntrants'));
    }

    public function update(Request $request, CourrierSortant $courrierSortant)
    {
        $validated = $request->validate([
            'objet' => 'required|string|max:255',
            'destinataire' => 'required|string|max:255',
            'date' => 'required|date',
            'courrier_entrant_id' => 'nullable|exists:courriers_entrants,id',
        ]);

        $courrierSortant->update($validated);

        return redirect()->route('courriers-sortants.index')
            ->with('success', 'Courrier sortant mis à jour avec succès.');
    }

    public function destroy(CourrierSortant $courrierSortant)
    {
        // Supprimer la décharge associée s'il en existe une
        if ($courrierSortant->decharge) {
            Storage::disk('public')->delete($courrierSortant->decharge);
        }
        
        $courrierSortant->delete();
        
        return redirect()->route('courriers-sortants.index')
            ->with('success', 'Courrier sortant supprimé avec succès.');
    }
    
    public function updateDecharge(Request $request, CourrierSortant $courrierSortant)
    {
        $request->validate([
            'decharge' => 'required|file|max:10240', // 10MB max
        ]);
        
        // Supprimer l'ancienne décharge s'il en existe une
        if ($courrierSortant->decharge) {
            Storage::disk('public')->delete($courrierSortant->decharge);
        }
        
        // Enregistrer la nouvelle décharge
        $path = $request->file('decharge')->store('decharges', 'public');
        
        $courrierSortant->update([
            'decharge' => $path,
            'decharge_manquante' => false
        ]);
        
        return redirect()->route('courriers-sortants.index')
            ->with('success', 'Décharge ajoutée avec succès.');
    }
}