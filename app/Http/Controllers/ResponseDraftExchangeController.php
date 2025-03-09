<?php

namespace App\Http\Controllers;

use App\Models\LecteurResponseDraft;
use App\Models\ResponseDraftExchange;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ResponseDraftExchangeController extends Controller
{
    /**
     * Afficher le formulaire pour créer un nouvel échange
     */
    public function create(LecteurResponseDraft $draft)
    {
        // Vérifier les permissions
        if (Auth::id() !== $draft->user_id && !Auth::user()->canAnnotateCourriers()) {
            abort(403, 'Vous n\'avez pas l\'autorisation d\'ajouter un échange sur ce projet.');
        }
        
        // Vérifier que le projet n'est pas déjà approuvé
        if ($draft->is_reviewed && $draft->status === 'approved') {
            return redirect()->route('lecteur-response-drafts.show', $draft)
                ->with('error', 'Ce projet a déjà été approuvé et ne peut plus être modifié.');
        }
        
        // Vérifier que le lecteur ne peut soumettre une révision que si elle est demandée
        if (Auth::id() === $draft->user_id && !$draft->needs_revision && $draft->status !== 'pending') {
            return redirect()->route('lecteur-response-drafts.show', $draft)
                ->with('error', 'Vous ne pouvez pas soumettre une révision si elle n\'a pas été demandée par un gestionnaire.');
        }
        
        return view('lecteur-response-drafts.exchanges.form', compact('draft'));
    }
    
    /**
     * Enregistrer un nouvel échange
     */
    public function store(Request $request, LecteurResponseDraft $draft)
    {
        // Vérifier les permissions
        if (Auth::id() !== $draft->user_id && !Auth::user()->canAnnotateCourriers()) {
            abort(403, 'Vous n\'avez pas l\'autorisation d\'ajouter un échange sur ce projet.');
        }
        
        // Vérifier que le projet n'est pas déjà approuvé
        if ($draft->is_reviewed && $draft->status === 'approved') {
            return redirect()->route('lecteur-response-drafts.show', $draft)
                ->with('error', 'Ce projet a déjà été approuvé et ne peut plus être modifié.');
        }
        
        // Validation de base commune
        $rules = [
            'comment' => 'required|string|max:1000',
            'type' => 'required|in:feedback,revision',
        ];
        
        // Validation spécifique selon le type d'échange
        if ($request->type === 'revision') {
            $rules['response_file'] = 'required|file|max:10240'; // 10MB max
            
            // Vérifier que l'utilisateur est bien le propriétaire du projet
            if (Auth::id() !== $draft->user_id) {
                abort(403, 'Seul le créateur du projet peut soumettre une révision.');
            }
        } else {
            $rules['status'] = 'required|in:pending,approved';
            
            // Vérifier que l'utilisateur est bien un gestionnaire
            if (!Auth::user()->canAnnotateCourriers()) {
                abort(403, 'Seul un gestionnaire peut ajouter un commentaire de feedback.');
            }
        }
        
        $validated = $request->validate($rules);
        
        // Création de l'échange
        $exchange = new ResponseDraftExchange([
            'draft_id' => $draft->id,
            'user_id' => Auth::id(),
            'comment' => $validated['comment'],
            'type' => $validated['type']
        ]);
        
        // Si c'est une révision, enregistrer le fichier
        if ($request->type === 'revision') {
            $filePath = $request->file('response_file')->store('response_drafts', 'public');
            $exchange->file_path = $filePath;
            
            // Mettre à jour le projet avec le nouveau fichier
            $draft->update([
                'file_path' => $filePath,
                'status' => 'revised',
                'is_reviewed' => false,
                'needs_revision' => false
            ]);
        } else {
            // Mettre à jour le statut du projet selon la décision du gestionnaire
            $draft->update([
                'status' => $validated['status'],
                'is_reviewed' => $validated['status'] === 'approved',
                'feedback' => $validated['comment'],
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now(),
                'needs_revision' => $validated['status'] === 'pending'
            ]);
        }
        
        $exchange->save();
        
        return redirect()->route('lecteur-response-drafts.show', $draft)
            ->with('success', 'Votre ' . ($request->type === 'revision' ? 'révision' : 'commentaire') . ' a été enregistré avec succès.');
    }
    
    /**
     * Afficher l'historique des échanges pour un projet
     */
    public function history(LecteurResponseDraft $draft)
    {
        // Vérifier les permissions
        if (Auth::id() !== $draft->user_id && !Auth::user()->canAnnotateCourriers()) {
            abort(403, 'Vous n\'avez pas l\'autorisation de voir les échanges sur ce projet.');
        }
        
        $exchanges = $draft->exchanges()->with('user')->orderBy('created_at', 'desc')->get();
        
        return view('lecteur-response-drafts.exchanges.history', compact('draft', 'exchanges'));
    }
}