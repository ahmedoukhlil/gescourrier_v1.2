<?php

namespace App\Http\Controllers;

use App\Models\Destinataire;
use Illuminate\Http\Request;

class DestinataireController extends Controller
{
    public function index()
    {
        $destinataires = Destinataire::orderBy('nom')->paginate(10);
        return view('destinataires.index', compact('destinataires'));
    }

    public function create()
    {
        return view('destinataires.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'service' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
        ]);

        Destinataire::create($validated);

        return redirect()->route('destinataires.index')
            ->with('success', 'Destinataire créé avec succès.');
    }

    public function edit(Destinataire $destinataire)
    {
        return view('destinataires.edit', compact('destinataire'));
    }

    public function update(Request $request, Destinataire $destinataire)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'service' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
        ]);

        $destinataire->update($validated);

        return redirect()->route('destinataires.index')
            ->with('success', 'Destinataire mis à jour avec succès.');
    }

    public function destroy(Destinataire $destinataire)
    {
        // Vérifiez si le destinataire est utilisé comme destinataire principal dans des courriers
        $hasMainCourriers = $destinataire->courriersDestines()->exists();
        
        // Vérifiez s'il est utilisé comme destinataire en copie
        $hasCcCourriers = $destinataire->courriers()->exists();
        
        if ($hasMainCourriers || $hasCcCourriers) {
            return redirect()->route('destinataires.index')
                ->with('error', 'Ce destinataire ne peut pas être supprimé car il est utilisé dans des courriers.');
        }
        
        $destinataire->delete();
        return redirect()->route('destinataires.index')
            ->with('success', 'Destinataire supprimé avec succès.');
    }
}