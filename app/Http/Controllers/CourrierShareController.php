<?php

namespace App\Http\Controllers;

use App\Models\CourrierShare;
use App\Models\CourriersEntrants;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourrierShareController extends Controller
{
    /**
     * Afficher tous les courriers partagés avec l'utilisateur connecté
     */
    public function index()
    {
        $shares = CourrierShare::with(['courrier', 'sharer', 'annotation'])
            ->where('shared_with', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('courriers.shared-show', compact('shares')); // Changé de shared à shared-show
    }
    
    public function show(CourrierShare $share)
    {
        // Vérifier que l'utilisateur connecté est bien le destinataire du partage
        if ($share->shared_with !== Auth::id()) {
            abort(403, 'Vous n\'avez pas l\'autorisation de voir ce courrier partagé.');
        }
        
        // Charger les relations
        $share->load(['courrier', 'sharer', 'annotation', 'annotation.annotator']);
        
        return view('courriers.shared', compact('share')); // Changé de shared-show à shared
    }
    
    /**
     * Marquer un courrier partagé comme lu
     */
    public function markAsRead(CourriersEntrants $courrier)
    {
        $share = CourrierShare::where('courrier_entrant_id', $courrier->id)
            ->where('shared_with', Auth::id())
            ->first();
            
        if (!$share) {
            return redirect()->back()->with('error', 'Ce courrier n\'est pas partagé avec vous.');
        }
        
        $share->update(['is_read' => true]);
        
        return redirect()->back()->with('success', 'Courrier marqué comme lu.');
    }
    
    /**
     * Supprimer un partage de courrier
     */
    public function destroy(CourrierShare $share)
    {
        // Vérifier que l'utilisateur connecté est autorisé à supprimer ce partage
        if (Auth::id() !== $share->shared_by && !Auth::user()->isAdmin()) {
            abort(403, 'Vous n\'avez pas l\'autorisation de supprimer ce partage.');
        }
        
        $share->delete();
        
        return redirect()->back()->with('success', 'Partage supprimé avec succès.');
    }
}