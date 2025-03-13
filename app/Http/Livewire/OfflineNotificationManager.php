<?php

namespace App\Http\Livewire;

use App\Models\Notification;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class OfflineNotificationManager extends Component
{
    public $showNotifications = false;
    public $newActivities = [];
    public $lastLoginTimestamp = null;
    
    protected $listeners = [
        'checkNewActivities' => 'checkNewActivities',
        'markAllAsRead' => 'markAllAsRead',
        'dismissNotifications' => 'dismissNotifications'
    ];
    
    public function mount()
    {
        // Récupérer le timestamp de la dernière connexion de l'utilisateur depuis le localStorage
        $this->dispatchBrowserEvent('get-last-login');
        
        // Vérifier les nouvelles activités au chargement
        $this->checkNewActivities();
    }
    
    /**
     * Vérifier les nouvelles activités depuis la dernière connexion
     */
    public function checkNewActivities($lastLoginTime = null)
    {
        if ($lastLoginTime) {
            $this->lastLoginTimestamp = $lastLoginTime;
        }
        
        if (!Auth::check()) {
            return;
        }
        
        $user = Auth::user();
        $query = Notification::where('user_id', $user->id)
                            ->where('is_read', false)
                            ->orderBy('created_at', 'desc');
        
        // Si nous avons un timestamp de dernière connexion, filtrer les notifications plus récentes
        if ($this->lastLoginTimestamp) {
            $lastLogin = Carbon::createFromTimestamp($this->lastLoginTimestamp);
            $query->where('created_at', '>', $lastLogin);
        }
        
        $this->newActivities = $query->get()->toArray();
        
        // Si nous avons de nouvelles activités, afficher la notification
        if (count($this->newActivities) > 0) {
            $this->showNotifications = true;
            
            // Stocker le timestamp actuel comme dernière connexion
            $this->dispatchBrowserEvent('set-last-login', [
                'timestamp' => Carbon::now()->timestamp
            ]);
        }
    }
    
    /**
     * Marquer toutes les notifications comme lues
     */
    public function markAllAsRead()
    {
        if (!Auth::check()) {
            return;
        }
        
        $user = Auth::user();
        
        Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);
            
        $this->newActivities = [];
        $this->showNotifications = false;
        
        // Émettre un événement pour rafraîchir l'indicateur de notification
        $this->emit('refreshNotifications');
    }
    
    /**
     * Fermer la notification sans marquer comme lu
     */
    public function dismissNotifications()
    {
        $this->showNotifications = false;
    }
    
    /**
     * Obtenir le nombre total de nouvelles activités
     */
    public function getNewActivitiesCountProperty()
    {
        return count($this->newActivities);
    }
    
    public function render()
    {
        return view('livewire.offline-notification-manager');
    }
} 