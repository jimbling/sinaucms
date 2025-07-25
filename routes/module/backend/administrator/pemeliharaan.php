<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\BackupController;

// PEMELIHARAAN
Route::middleware(['permission:edit_pemeliharaan'])->group(function () {
    Route::get('/pemeliharaan', [BackupController::class, 'index'])->name('pemeliharaan.index');
    Route::post('/backup', [BackupController::class, 'createBackup'])->name('backup.create');
    Route::get('/backup-progress', function () {
        $logFile = storage_path('logs/backup_progress.log');
        $content = file_exists($logFile) ? file_get_contents($logFile) : 'No progress yet.';
        return response()->json(['progress' => $content]);
    });

    Route::get('/backups', [BackupController::class, 'listBackups'])->name('backups');
    Route::get('/backups/download/{filename}', [BackupController::class, 'downloadBackup'])->name('backup.download');
    Route::get('/backups/sql', [BackupController::class, 'backupDatabase'])->name('backups.sql');
    Route::delete('/backup/delete/{filename}', [BackupController::class, 'deleteBackup'])->name('backup.delete');
});
