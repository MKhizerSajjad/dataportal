<?php

namespace App\Jobs;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Imports\ContactsImport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;

class ProcessContactsImport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filePath;

    /**
     * Create a new job instance.
     */
    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $file = new UploadedFile(
            Storage::path($this->filePath), // Get the full path to the file
            basename($this->filePath)
        );

        Excel::import(new ContactsImport, $file);
    }
}
