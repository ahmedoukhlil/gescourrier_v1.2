<?php

namespace App\Http\Controllers;

use App\Models\CourriersEntrants;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class CourriersEntrantsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Si c'est un lecteur, montrer uniquement les courriers partagés avec lui
        if ($user->isLecteur()) {
            return redirect()->route('courriers.shared');
        }
        
        $courriers = CourriersEntrants::with(['destinataireInterne', 'destinataires'])
                                ->latest()
                                ->paginate(10);
        $destinataires = User::orderBy('name')->get();
        return view('Courriers.index', compact('courriers', 'destinataires'));
    }

    public function create()
    {
        $destinataires = User::orderBy('name')->get();
        return view('Courriers.create', compact('destinataires'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'expediteur' => 'required|string|max:255',
            'type' => 'required|string|in:urgent,confidentiel,normal',
            'objet' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id', // Modifié de destinataire_id à user_id
            'nom_dechargeur' => 'required|string|max:255',
            'additional_destinataires' => 'nullable|array',
            'additional_destinataires.*' => 'exists:users,id', // Modifié pour pointer vers users
            'document' => 'nullable|file|max:10240',
        ]);

        // Gérer le téléchargement du document
        if ($request->hasFile('document')) {
            $path = $request->file('document')->store('courriers', 'public');
            $validated['document_path'] = $path;
        }

        // Créer le courrier - numéro_ordre sera généré automatiquement dans le modèle
        $courrier = CourriersEntrants::create($validated);

        // Associer les destinataires additionnels (cc)
        if (!empty($request->additional_destinataires)) {
            $courrier->destinataires()->attach($request->additional_destinataires);
        }

        return redirect()->route('courriers.index')
            ->with('success', 'Courrier créé avec succès. Numéro d\'ordre: ' . $courrier->numero_ordre);
    }

    public function show(CourriersEntrants $courrier)
    {
        // Vérifier si l'utilisateur a le droit de voir ce courrier
        if (Gate::denies('view-courrier', $courrier)) {
            abort(403, 'Vous n\'avez pas l\'autorisation de voir ce courrier.');
        }
        
        $courrier->load(['destinataireInterne', 'destinataires', 'courriersSortants', 'annotations', 'annotations.annotator', 'annotations.shares', 'annotations.shares.recipient']);
        
        return view('Courriers.show', compact('courrier'));
    }

    public function edit(CourriersEntrants $courrier)
    {
        $destinataires = User::orderBy('name')->get();
        $courrier->load('destinataireInterne', 'destinataires');
        return view('Courriers.edit', compact('courrier', 'destinataires'));
    }

    public function update(Request $request, CourriersEntrants $courrier)
    {
        $validated = $request->validate([
            'expediteur' => 'required|string|max:255',
            'type' => 'required|string|in:urgent,confidentiel,normal',
            'objet' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id', // Modifié
            'nom_dechargeur' => 'required|string|max:255',
            'statut' => 'required|string|in:en_cours,traite,archive',
            'additional_destinataires' => 'nullable|array',
            'additional_destinataires.*' => 'exists:users,id', // Modifié
            'document' => 'nullable|file|max:10240',
        ]);

        // Gérer le téléchargement du document
        if ($request->hasFile('document')) {
            // Supprimer l'ancien document s'il existe
            if ($courrier->document_path) {
                Storage::disk('public')->delete($courrier->document_path);
            }
            
            $path = $request->file('document')->store('courriers', 'public');
            $validated['document_path'] = $path;
        }

        $courrier->update($validated);

        // Synchroniser les destinataires additionnels
        if (isset($request->additional_destinataires)) {
            $courrier->destinataires()->sync($request->additional_destinataires);
        } else {
            $courrier->destinataires()->detach();
        }

        return redirect()->route('courriers.index')
            ->with('success', 'Courrier mis à jour avec succès.');
    }

    public function destroy(CourriersEntrants $courrier)
    {
        // Supprimer le document associé s'il existe
        if ($courrier->document_path) {
            Storage::disk('public')->delete($courrier->document_path);
        }
        
        // Supprimer les annotations et partages associés
        $courrier->annotations()->delete();
        $courrier->shares()->delete();
        
        $courrier->delete();
        return redirect()->route('courriers.index')
            ->with('success', 'Courrier supprimé avec succès.');
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