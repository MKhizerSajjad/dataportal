<?php

namespace App\Jobs;

// namespace App\Notifications;

use App\Exports\ContactsExport;
use Maatwebsite\Excel\Facades\Excel;
use ZipArchive;
use App\Notifications\ExportCompleted;


use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ExtractContactsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filters;
    protected $userID;
    /**
     * Create a new job instance.
     */
    public function __construct($filters, $userID)
    {
        $this->filters = $filters;
        $this->userID = $userID;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        logger('in job');
        $chunkSize = 1000;
        $contactsExport = new ContactsExport($this->filters);
        $totalContacts = $contactsExport->queryCount();
        $numChunks = ceil($totalContacts / $chunkSize);

        $path = public_path("storage/exports/{$this->userID}");
        exec("rm -rf {$path}/*"); // Clear old exports

        $filePaths = [];

        for ($i = 0; $i < $numChunks; $i++) {
            $fileName = "contacts-" . now()->timestamp . "-" . ($i + 1) . ".csv";
            $filePath = "exports/{$this->userID}/$fileName";

            $chunkedContacts = new ContactsExport($this->filters, $i * $chunkSize, $chunkSize);
            Excel::store($chunkedContacts, $filePath);
            $filePaths[] = "storage/$filePath";
        }

        // Create a ZIP file
        $zipFileName = "downloads.zip";
        $zipFilePath = storage_path("app/public/exports/{$this->userID}/$zipFileName");
        $zip = new ZipArchive;

        if ($zip->open($zipFilePath, ZipArchive::CREATE) === TRUE) {
            foreach ($filePaths as $file) {
                $zip->addFile(public_path($file), basename($file));
            }
            $zip->close();
        }

        // Optionally, you can notify the user via a notification or email that the export is done
        // For example:
        // Notification::send(User::find($this->userID), new ExportCompleted($zipFilePath));

        $user = User::find($this->userID);
        logger("storage/exports/{$this->userID}/downloads.zip");

        $user->notify(new ExportCompleted("storage/exports/{$this->userID}/downloads.zip"));
    }
}
