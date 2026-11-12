<?php

namespace App\Controllers;

class Backup extends BaseController
{
    public function index()
    {
        return view('backup/index', ['title' => 'Backup & Restore Database']);
    }

    public function download()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak!');
        }

        $mysqldump = 'C:\\xampp\\mysql\\bin\\mysqldump.exe';
        if (!file_exists($mysqldump)) {
            $mysqldump = 'mysqldump';
        }

        $filename = 'backup_sistem_aset_' . date('Ymd_His') . '.sql';
        $tmpPath  = WRITEPATH . 'uploads/' . $filename;

        if (!is_dir(WRITEPATH . 'uploads')) {
            mkdir(WRITEPATH . 'uploads', 0777, true);
        }

        $db = \Config\Database::connect();
        $dbName = $db->getDatabase();
        $dbUser = 'root';
        $dbPass = '';
        $dbHost = 'localhost';

        $command = '"' . $mysqldump . '" -h ' . escapeshellarg($dbHost) . ' -u ' . escapeshellarg($dbUser)
            . ' ' . escapeshellarg($dbName) . ' > ' . escapeshellarg($tmpPath);

        exec($command, $output, $returnVar);

        if ($returnVar !== 0 || !file_exists($tmpPath)) {
            return redirect()->to('/backup')->with('error', 'Gagal membuat backup database.');
        }

        return $this->response->download($tmpPath, null)->setFileName($filename);
    }

    public function restore()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak!');
        }

        $file = $this->request->getFile('sql_file');
        if (!$file || !$file->isValid()) {
            return redirect()->to('/backup')->with('error', 'File SQL tidak valid!');
        }

        $content = file_get_contents($file->getTempName());
        if (empty($content)) {
            return redirect()->to('/backup')->with('error', 'File SQL kosong!');
        }

        $db = \Config\Database::connect();

        // Hapus komentar dan pecah statement
        $lines = explode("\n", $content);
        $clean = '';
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || strpos($line, '--') === 0 || strpos($line, '#') === 0) {
                continue;
            }
            $clean .= $line . "\n";
        }

        $statements = array_filter(array_map('trim', explode(';', $clean)));
        foreach ($statements as $stmt) {
            if ($stmt === '') {
                continue;
            }
            try {
                $db->query($stmt);
            } catch (\Throwable $e) {
                // Lanjutkan; catat error
            }
        }

        log_activity('restore_db', 'system', null, 'Restore database dari file SQL');

        return redirect()->to('/backup')->with('message', 'Proses restore selesai. Periksa data secara manual.');
    }
}