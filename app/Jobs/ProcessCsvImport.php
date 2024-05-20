<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Imports\ContactsImport;
use Maatwebsite\Excel\Facades\Excel;

class ProcessCsvImport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $chunkPath;
    /**
     * Create a new job instance.
     */
    public function __construct($chunkPath)
    {
        $this->chunkPath = $chunkPath;
    }

    /**
     * Execute the job.
     */
    public function handle() //: void
    {
        // $user=User::where('email','admin@admin.com')->first();
        // Create an instance of CsvImportService
        // $csvImportService = new CsvImportService();
        // // Call the import method to process the chunk
        // $csvImportService->import($this->chunkPath);
        Excel::import(new ContactsImport, $this->chunkPath);
        $directory = storage_path('app/public/temp/');

        // Get all chunk files in the directory
        $chunkFiles = glob($directory . 'blob_chunk_*.csv');
        // Remove all chunk files
        foreach ($chunkFiles as $chunkFile) {
            if (file_exists($chunkFile)) {
                unlink($chunkFile);
            }
        }
        return true;
    }
}
