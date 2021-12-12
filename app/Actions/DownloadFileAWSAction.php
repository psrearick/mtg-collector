<?php

namespace App\Actions;

use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class DownloadFileAWSAction
{
    public function execute(array $file, string $date_format = '', int $clean = 0) : string
    {
        // get file name
        $file_name = $this->getFileName($file, $date_format);

        // get download directory
        $dir = $this->getFileDirectory($file['storage_path']);

        // Save file into file location
        $filePath = $dir . '/' . $file_name;
        $this->saveFile($filePath, $file['url']);

        // remove old files
        $this->clean($clean, $dir);

        return Storage::url($filePath);
    }

    /**
     * get storage directory, make it if needed
     *
     * @param string $path
     * @return string
     */
    private function getFileDirectory(string $path) : string
    {
        Storage::makeDirectory($path);

        return Storage::path($path);
    }

    /**
     * Build the name of the file
     *
     * @param array $file
     * @param string $date_format
     * @return string
     */
    private function getFileName(array $file, string $date_format) : string
    {
        $date = '';
        if ($date_format) {
            $date = '_' . Carbon::now()->format($date_format);
        }

        if (array_key_exists('name', $file)) {
            return $file['name'] . $date . '.' . $file['format'];
        }

        // return the base name of file
        return basename($file['url'], '.' . $file['format']) . $date . '.' . $file['format'];
    }

    public function saveFile(string $filePath, string $url) : void
    {
        // Only create the file if it doesn't exist
        if (Storage::disk('s3')->exists($filePath)) {
            return;
        }

        // Initialize the cURL session and open file
        $tmp = tmpfile();
        $ch = curl_init($url);
        // $fp = fopen($path, 'c');

        // set an option for a cURL transfer
        curl_setopt($ch, CURLOPT_FILE, $tmp);
        curl_setopt($ch, CURLOPT_HEADER, 0);

        // Perform a cURL session
        curl_exec($ch);

        // Closes a cURL session and file and free all resources
        curl_close($ch);

        $tmpfile_path = stream_get_meta_data($tmp)['uri'];
        Storage::put($filePath, file_get_contents($tmpfile_path));

        fclose($tmp);
    }

    /**
     * Remove old files
     *
     * @param int $max
     * @param string $dir
     */
    private function clean(int $max, string $dir) : void
    {
        if (!($dir && $max) || $max < 1) {
            return;
        }

        // get files
        $files = $this->getFilesInDirectory($dir);

        // remove old files
        $count = 1;
        foreach ($files as $file => $time) {
            if ($count > $max) {
                Storage::delete($file);
            }
            $count++;
        }
    }

    private function getFilesInDirectory(string $dir) : array
    {
        //get all the files
        $files = [];
        foreach (Storage::files($dir) as $file) {
            $files[$file] = Storage::lastModified($file);
        }

        //sort descending by date created
        arsort($files);

        return $files;
    }
}