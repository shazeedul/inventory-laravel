<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class PermissionResetCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permission:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset all permissions and roles and seed them again';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            Artisan::call('db:seed', [
                '--class' => 'RolePermissionTableSeeder',
                '--force' => true,
            ]);
            $this->info('Permission reset successfully');
        } catch (\Throwable $th) {
            $this->error($th->getMessage());
        }

        return 0;

    }
}
