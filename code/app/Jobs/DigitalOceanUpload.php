<?php

namespace App\Jobs;

use App\Models\File;
use App\Services\ThumbnailService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class DigitalOceanUpload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private string $path;

    private string $filename;

    private string $full_path;

    private $file;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $path, string $filename)
    {
        $this->path = $path;
        $this->filename = $filename;

        $this->full_path = "{$this->path}/{$this->filename}";
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Storage::putFileAs($this->path, Storage::disk('local')->path($this->full_path), $this->filename);
        Storage::setVisibility($this->full_path, 'public');

        //thumbnails width
        $widths = [200, 800];

        $fileModel = File::whereName($this->path)->first();
        if ($fileModel) {
            foreach ($widths as $width) {
                $thumb_name = "{$width}w_{$this->filename}";
                $thumb_path = Storage::disk('local')->path("{$this->path}/{$thumb_name}");
                $thumb_local_path = "{$this->path}/{$thumb_name}";

                if ((new ThumbnailService())->createThumbnail(Storage::disk('local')->path($this->full_path), $thumb_path, $width) !== null) {
                    Storage::putFileAs($this->path, $thumb_path, $thumb_name);
                    Storage::setVisibility($thumb_local_path, 'public');

                    $fileModel->thumbnails()->create([
                        'name' => $this->path,
                        'path' => $thumb_local_path,
                    ]);
                }
            }
        }

        $fileModel->update(['uploaded' => true]);

        Storage::disk('local')->deleteDirectory($this->path);
    }
}
