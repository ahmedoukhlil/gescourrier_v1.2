<?php

namespace App\Http\Controllers;

use App\Models\CourriersEntrants;
use App\Models\CourrierSortant;
use App\Models\CourrierShare;
use App\Models\LecteurResponseDraft;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistiques pour tous les utilisateurs
        $stats = [
            'total_courriers_entrants' => CourriersEntrants::count(),
            'total_courriers_sortants' => CourrierSortant::count(),
            'courriers_urgent' => CourriersEntrants::where('type', 'urgent')->count(),
        ];
        
        $user = Auth::user();
        
        // Statistiques spécifiques au rôle
        if ($user->isLecteur()) {
            // Statistiques pour les lecteurs
            $stats['unread_shares'] = CourrierShare::where('shared_with', $user->id)
                                                  ->where('is_read', false)
                                                  ->count();
            
            $stats['pending_drafts'] = LecteurResponseDraft::where('user_id', $user->id)
                                                          ->where('is_reviewed', false)
                                                          ->count();
            
            $stats['reviewed_drafts'] = LecteurResponseDraft::where('user_id', $user->id)
                                                           ->where('is_reviewed', true)
                                                           ->count();
        } elseif ($user->canAnnotateCourriers()) {
            // Statistiques pour les gestionnaires et admins
            $stats['pending_drafts_review'] = LecteurResponseDraft::where('is_reviewed', false)->count();
            
            $stats['shared_courriers'] = CourrierShare::where('shared_by', $user->id)->count();
            
            $stats['recent_annotations'] = $user->annotations()
                                               ->orderBy('created_at', 'desc')
                                               ->limit(5)
                                               ->get();
        }
        
        // Activité récente (pour tous les utilisateurs)
        $stats['recent_courriers'] = CourriersEntrants::orderBy('created_at', 'desc')
                                                     ->limit(5)
                                                     ->get();
        
        return view('dashboard', compact('stats'));
    }
}