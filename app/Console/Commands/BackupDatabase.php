<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class BackupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup-database';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup Database';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        include('app/Helper/Dumper.php');
        $data = backupDatabase();
        if($data['status'] == 'success'){
            $filename = $data['filename'];
            \App\Backup::create(['file' => $filename]);
        }
    }
}