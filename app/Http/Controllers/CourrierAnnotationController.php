<?php

namespace App\Http\Controllers;

use App\Models\CourriersEntrants;
use App\Models\CourrierAnnotation;
use App\Models\CourrierShare;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class CourrierAnnotationController extends Controller
{
    /**
     * Afficher le formulaire d'annotation pour un courrier
     */
    public function create(CourriersEntrants $courrier)
    {
        if (!Auth::user()->canAnnotateCourriers()) {
            abort(403, 'Vous n\'avez pas l\'autorisation d\'annoter les courriers.');
        }
        
        // Récupérer les annotations existantes
        $annotations = $courrier->annotations()->with('annotator')->orderBy('created_at', 'desc')->get();
        
        // Récupérer les lecteurs pour le partage
        $lecteurs = User::whereHas('roles', function($query) {
            $query->where('slug', 'lecteur');
        })->orderBy('name')->get();
        
        // Récupérer les partages existants
        $shares = $courrier->shares()->with(['recipient', 'annotation'])->get();
        
        return view('courriers.annotate', compact('courrier', 'annotations', 'lecteurs', 'shares'));
    }
    
    /**
     * Enregistrer une nouvelle annotation
     */
    public function store(Request $request, CourriersEntrants $courrier)
    {
        if (!Auth::user()->canAnnotateCourriers()) {
            abort(403, 'Vous n\'avez pas l\'autorisation d\'annoter les courriers.');
        }
        
        $validated = $request->validate([
            'annotation' => 'required|string|max:1000',
            'share_with' => 'nullable|array',
            'share_with.*' => 'exists:users,id',
        ]);
        
        // Créer l'annotation
        $annotation = new CourrierAnnotation([
            'courrier_entrant_id' => $courrier->id,
            'annotated_by' => Auth::id(),
            'annotation' => $validated['annotation']
        ]);
        
        $annotation->save();
        
        // Partager avec les lecteurs sélectionnés
        if (!empty($validated['share_with'])) {
            foreach ($validated['share_with'] as $userId) {
                // Vérifier si l'utilisateur est un lecteur
                $user = User::findOrFail($userId);
                if ($user->isLecteur()) {
                    CourrierShare::create([
                        'courrier_entrant_id' => $courrier->id,
                        'shared_by' => Auth::id(),
                        'shared_with' => $userId,
                        'annotation_id' => $annotation->id,
                        'is_read' => false
                    ]);
                }
            }
        }
        
        return redirect()->route('courriers.show', $courrier)
            ->with('success', 'Annotation et partages effectués avec succès.');
    }
    
    /**
     * Supprimer une annotation
     */
    public function destroy(CourrierAnnotation $annotation)
    {
        if (Auth::id() !== $annotation->annotated_by && !Auth::user()->isAdmin()) {
            abort(403, 'Vous n\'avez pas l\'autorisation de supprimer cette annotation.');
        }
        
        // Supprimer les partages associés à cette annotation
        $annotation->shares()->delete();
        
        // Supprimer l'annotation
        $annotation->delete();
        
        return redirect()->back()
            ->with('success', 'Annotation supprimée avec succès.');
    }
}