<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class BackupController extends Controller
{
    
    public function backupreset()
    {
    $user = Auth::user();
    return view('adminpages.backupreset', [
        'userName' => $user->name,
        'userEmail' => $user->email,
       
    ]);
    }

 

    public function backupDatabase()
    {
        $dbName = env('DB_DATABASE');
        $dbUser = env('DB_USERNAME');
        $dbPass = env('DB_PASSWORD');
        $dbHost = env('DB_HOST');
        $dbPort = env('DB_PORT', 3306);

        $backupFile = "backup_" . date('Y_m_d_H_i_s') . ".sql";

        $mysqldumpPath = '"C:\xampp\mysql\bin\mysqldump.exe"';

        $passwordPart = $dbPass ? "--password={$dbPass}" : '';

        $command = "{$mysqldumpPath} --user={$dbUser} {$passwordPart} --host={$dbHost} --port={$dbPort} {$dbName} > " . storage_path("app/{$backupFile}");
        exec($command . " 2>&1", $output, $result);

        \Log::info('Backup command: ' . $command);
        \Log::info('Backup output: ' . print_r($output, true));
        \Log::info('Backup result: ' . $result);

        if ($result === 0) {
            return response()->download(storage_path("app/{$backupFile}"))->deleteFileAfterSend(true);
        } else {
            return response()->json([
                'error' => 'Backup failed!',
                'details' => $output
            ], 500);
        }
    }


    public function resetDatabase()
{
    $excludedTables = ['users', 'accounts', 'add_accounts'];
    $tables = DB::select('SHOW TABLES');
    $dbName = env('DB_DATABASE');
    $key = 'Tables_in_' . $dbName;

    DB::beginTransaction();
    try {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        foreach ($tables as $table) {
            $tableName = $table->$key;
            if (!in_array($tableName, $excludedTables)) {
                DB::table($tableName)->truncate();
            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        DB::commit();

        return response()->json(['success' => true, 'message' => 'Database reset successfully, except specified tables.']);

    } catch (\Exception $e) {
        DB::rollBack();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        \Log::error('Reset failed: ' . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'Reset failed!',
            'error' => $e->getMessage()
        ], 500);
    }
}

}
