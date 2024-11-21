<?php
    // Function to download file with progress tracking
    function downloadFile($url, $localFile) {
        $fp = fopen($localFile, 'w+');
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_NOPROGRESS, false);
        curl_setopt($ch, CURLOPT_PROGRESSFUNCTION, 'progressCallback');
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);
    }

    function progressCallback($resource, $download_size, $downloaded, $upload_size, $uploaded) {
        if ($download_size > 0) {
            $progress = round(($downloaded / $download_size) * 100);
            $_SESSION['download_progress'] = $progress;
            echo "<script>updateProgress($progress);</script>";
            flush();
        }
    }

    // Function to extract ZIP file with progress tracking
    function extractZip($zipFile, $extractPath) {
        $zip = new ZipArchive;
        if ($zip->open($zipFile) === TRUE) {
            $totalFiles = $zip->numFiles;
            $_SESSION['extract_progress'] = ['total' => $totalFiles, 'current' => 0];

            for ($i = 0; $i < $totalFiles; $i++) {
                $filename = $zip->getNameIndex($i);
                $zip->extractTo($extractPath, $filename);
                $_SESSION['extract_progress']['current'] = $i + 1;
                $progress = round((($i + 1) / $totalFiles) * 100);
                echo "<script>updateExtractProgress($progress);</script>";
                flush();
            }
            $zip->close();
        }
    }

    // Function to upload extracted files to FTP server
    function uploadToFTP($ftpServer, $ftpUsername, $ftpPassword, $localPath, $ftpDestinationPath) {
        $ftpConn = ftp_connect($ftpServer);
        $login = ftp_login($ftpConn, $ftpUsername, $ftpPassword);
        ftp_pasv($ftpConn, true);

        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($localPath), RecursiveIteratorIterator::LEAVES_ONLY);
        foreach ($files as $file) {
            if ($file->isFile()) {
                $localFilePath = $file->getRealPath();
                $relativePath = substr($localFilePath, strlen($localPath));
                $ftpFilePath = $ftpDestinationPath . '/' . $relativePath;

                ftp_mkdir($ftpConn, dirname($ftpFilePath));
                ftp_put($ftpConn, $ftpFilePath, $localFilePath, FTP_BINARY);
            }
        }
        ftp_close($ftpConn);
    }
?>