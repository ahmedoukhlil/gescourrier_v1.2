<?php

namespace App\Http\Controllers;

use App\Models\LecteurResponseDraft;
use App\Models\CourriersEntrants;
use App\Models\CourrierShare;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;

class LecteurResponseDraftController extends Controller
{
    /**
     * Afficher le formulaire pour créer un projet de réponse
     */
    public function create(CourriersEntrants $courrier)
    {
        // Vérifier si le courrier est partagé avec l'utilisateur
        $share = CourrierShare::where('courrier_entrant_id', $courrier->id)
            ->where('shared_with', Auth::id())
            ->first();
            
        if (!$share) {
            abort(403, 'Ce courrier n\'est pas partagé avec vous.');
        }
        
        // Vérifier si l'utilisateur a déjà soumis un projet de réponse pour ce courrier
        $existingDraft = LecteurResponseDraft::where('courrier_entrant_id', $courrier->id)
            ->where('user_id', Auth::id())
            ->first();
            
        return view('lecteur-response-drafts.create', compact('courrier', 'share', 'existingDraft'));
    }

    /**
     * Enregistrer un nouveau projet de réponse
     */
    public function store(Request $request, CourriersEntrants $courrier)
    {
        // Vérifier si le courrier est partagé avec l'utilisateur
        $share = CourrierShare::where('courrier_entrant_id', $courrier->id)
            ->where('shared_with', Auth::id())
            ->first();
            
        if (!$share) {
            abort(403, 'Ce courrier n\'est pas partagé avec vous.');
        }
        
        // Valider la requête
        $validated = $request->validate([
            'comment' => 'nullable|string|max:1000',
            'response_file' => 'required|file|max:10240', // 10MB max
        ]);
        
        // Enregistrer le fichier
        $filePath = $request->file('response_file')->store('response_drafts', 'public');
        
        // Créer ou mettre à jour le projet de réponse
        $draft = LecteurResponseDraft::updateOrCreate(
            [
                'courrier_entrant_id' => $courrier->id,
                'user_id' => Auth::id(),
            ],
            [
                'comment' => $validated['comment'],
                'file_path' => $filePath,
                'is_reviewed' => false,
                'feedback' => null,
                'reviewed_by' => null,
                'reviewed_at' => null,
            ]
        );
        
        return redirect()->route('courriers.shared.show', $share->id)
            ->with('success', 'Votre projet de réponse a été soumis avec succès.');
    }
    
    /**
     * Afficher les projets de réponse pour un courrier (pour les gestionnaires/admins)
     */
    public function index(CourriersEntrants $courrier)
    {
        // Vérifier les permissions
        if (!Auth::user()->canAnnotateCourriers()) {
            abort(403, 'Vous n\'avez pas l\'autorisation de voir les projets de réponse.');
        }
        
        $drafts = $courrier->responseDrafts()->with('user', 'reviewer')->get();
        
        return view('lecteur-response-drafts.index', compact('courrier', 'drafts'));
    }
    
    /**
     * Afficher un projet de réponse spécifique
     */
    public function show(LecteurResponseDraft $draft)
    {
        // Vérifier les permissions
        if (Auth::id() !== $draft->user_id && !Auth::user()->canAnnotateCourriers()) {
            abort(403, 'Vous n\'avez pas l\'autorisation de voir ce projet de réponse.');
        }
        
        return view('lecteur-response-drafts.show', compact('draft'));
    }
    
    /**
 * Examiner un projet de réponse (pour les gestionnaires/admins)
 */
public function review(Request $request, LecteurResponseDraft $draft)
{
    // Vérifier les permissions
    if (!Auth::user()->canAnnotateCourriers()) {
        abort(403, 'Vous n\'avez pas l\'autorisation d\'examiner les projets de réponse.');
    }
    
    $validated = $request->validate([
        'feedback' => 'required|string|max:1000',
        'status' => 'required|in:pending,approved', // pending = en attente de révision, approved = approuvé 
    ]);
    
    // Créer un nouvel échange
    $exchange = new ResponseDraftExchange([
        'draft_id' => $draft->id,
        'user_id' => Auth::id(),
        'comment' => $validated['feedback'],
        'type' => 'feedback'
    ]);
    
    $exchange->save();
    
    // Mettre à jour le statut du projet
    $draft->update([
        'status' => $validated['status'],
        'is_reviewed' => $validated['status'] === 'approved',
        'feedback' => $validated['feedback'],
        'reviewed_by' => Auth::id(),
        'reviewed_at' => now(),
        'needs_revision' => $validated['status'] === 'pending'
    ]);
    
    return redirect()->route('lecteur-response-drafts.index', $draft->courrier_entrant_id)
        ->with('success', 'Le projet de réponse a été examiné avec succès.');
}

/**
 * Ajouter une révision à un projet de réponse (pour les lecteurs)
 */
public function addRevision(Request $request, LecteurResponseDraft $draft)
{
    // Vérifier les permissions
    if (Auth::id() !== $draft->user_id) {
        abort(403, 'Vous n\'avez pas l\'autorisation de réviser ce projet de réponse.');
    }
    
    $validated = $request->validate([
        'comment' => 'required|string|max:1000',
        'response_file' => 'required|file|max:10240', // 10MB max
    ]);
    
    // Enregistrer le nouveau fichier
    $filePath = $request->file('response_file')->store('response_drafts', 'public');
    
    // Créer un nouvel échange
    $exchange = new ResponseDraftExchange([
        'draft_id' => $draft->id,
        'user_id' => Auth::id(),
        'comment' => $validated['comment'],
        'file_path' => $filePath,
        'type' => 'revision'
    ]);
    
    $exchange->save();
    
    // Mettre à jour le projet avec le nouveau fichier et statut
    $draft->update([
        'file_path' => $filePath, // Mettre à jour le fichier principal
        'status' => 'revised', // Révisé, en attente de nouvel examen
        'is_reviewed' => false, // Nécessite un nouvel examen
        'needs_revision' => false // La révision a été soumise
    ]);
    
    // Rediriger vers la page du projet
    return redirect()->route('lecteur-response-drafts.show', $draft)
        ->with('success', 'Votre révision a été soumise avec succès.');
}
}