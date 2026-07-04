<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;

class BackupDatabase extends Command
{
    protected $signature = 'inventaris:backup {--keep=14 : Jumlah file backup yang disimpan}';

    protected $description = 'Backup database aplikasi inventaris ke storage/app/backups (mysqldump).';

    public function handle(): int
    {
        $dir = storage_path('app/backups');
        File::ensureDirectoryExists($dir);

        $db = config('database.connections.'.config('database.default'));
        $filename = $dir.'/backup-'.now()->format('Ymd-His').'.sql';

        $this->info("Membuat backup: {$filename}");

        $process = Process::fromShellCommandline(
            sprintf(
                'mysqldump --host=%s --port=%s --user=%s %s %s > %s',
                escapeshellarg($db['host']),
                escapeshellarg((string) $db['port']),
                escapeshellarg($db['username']),
                $db['password'] ? '--password='.escapeshellarg($db['password']) : '',
                escapeshellarg($db['database']),
                escapeshellarg($filename)
            )
        );
        $process->setTimeout(600);
        $process->run();

        if (! $process->isSuccessful()) {
            $this->error('Backup gagal: '.$process->getErrorOutput());

            return self::FAILURE;
        }

        $this->rotateBackups($dir, (int) $this->option('keep'));

        $this->info('Backup selesai.');

        return self::SUCCESS;
    }

    /** Hapus backup lama, simpan N terbaru. */
    private function rotateBackups(string $dir, int $keep): void
    {
        $files = collect(File::files($dir))
            ->filter(fn ($f) => str_ends_with($f->getFilename(), '.sql'))
            ->sortByDesc(fn ($f) => $f->getMTime())
            ->values();

        $files->slice($keep)->each(fn ($f) => File::delete($f->getPathname()));
    }
}
