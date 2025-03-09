<?php

namespace App\Console\Commands;

use App\Services\NotificationService;
use Illuminate\Console\Command;

class SendDailyDigests extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:send-digests';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send daily digest emails to users who have opted in';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @param NotificationService $notificationService
     * @return int
     */
    public function handle(NotificationService $notificationService)
    {
        $this->info('Sending daily digest emails...');
        
        $notificationService->sendDailyDigests();
        
        $this->info('Daily digest emails sent successfully.');
        
        return 0;
    }
}