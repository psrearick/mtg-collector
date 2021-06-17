<?php

namespace App\Actions;

use Carbon\Carbon;

class DownloadFileAction
{
    public function __construct()
    {
    }

    public function execute(array $file, string $date_format = '', int $clean = 0) : string
    {
        // get file name
        $file_name = array_key_exists('name', $file) ? $file['name'] : $this->getFileName($file, $date_format);

        // get download directory
        $dir = $this->getFileDirectory($file['storage_path']);

        // Save file into file location
        $save_file_loc = $dir . '/' . $file_name;
        $this->saveFile($save_file_loc, $file['url']);

        // remove old files
        $this->clean($clean, $dir);

        return $save_file_loc;
    }

    public function saveFile(string $path, string $url) : void
    {
        // Only create the file if it doesn't exist
        if (file_exists($path)) {
            return;
        }

        // Initialize the cURL session and open file
        $ch = curl_init($url);
        $fp = fopen($path, 'c');

        // set an option for a cURL transfer
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_HEADER, 0);

        // Perform a cURL session
        curl_exec($ch);

        // Closes a cURL session and file and free all resources
        curl_close($ch);
        fclose($fp);
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
                unlink($file);
            }
            $count++;
        }
    }

    /**
     * get storage directory, make it if needed
     *
     * @param string $path
     * @return string
     */
    private function getFileDirectory(string $path) : string
    {
        $dir = storage_path($path);

        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        return $dir;
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

        // return the base name of file
        return basename($file['url'], '.' . $file['format']) . $date . '.' . $file['format'];
    }

    private function getFilesInDirectory(string $dir) : array
    {
        //get all the files
        $files = [];
        foreach (scandir($dir) as $file) {
            if (is_file($file)) {
                $files[$file] = filemtime($file);
            }
        }

        //sort descending by date created
        arsort($files);

        return $files;
    }
}
