<?php

namespace InsightMedia\StatamicSync;

use Spatie\Ssh\Ssh;
use Illuminate\Support\Collection;

class StatamicSync
{
    private static Ssh $ssh;
    private static String $remotePath;
    private static String $backupPath;

    public static function sync(): void
    {
        $ssh = self::connection();

        $paths = collect(config('statamic-sync.paths'));

        self::backup($paths);

        $paths->each(function($dir) use (&$ssh) {
            $sourcePath = self::$remotePath.DIRECTORY_SEPARATOR.$dir;
            $destinationPath = dirname(base_path($dir));
            $process = $ssh->download($sourcePath, $destinationPath);

            echo "\n".$ssh->getDownloadCommand($sourcePath, $destinationPath);

            if ($process->isSuccessful())
            {
                echo " --> OK";
            }
            else
            {
                throw new \Exception(!empty($process->getOutput()) ? $process->getOutput() : " --> Something went wrong!");
            }
        });

    }

    private static function connection(): Ssh
    {
        if (!isset(self::$ssh))
        {

            $user = config('statamic-sync.ssh.user');
            $host = config('statamic-sync.ssh.host');
            $path = config('statamic-sync.ssh.path');

            if (empty($user) || empty($host) || empty($path)) throw new \Exception('Missing required config settings (user, host or path)');

            self::$ssh = Ssh::create(
                $user,
                $host
            )->onOutput(function($type, $line) { echo "\n".$line; })->enableQuietMode();

            self::$remotePath = $path;
        }

        return self::$ssh;
    }

    private static function backup(Collection $paths): void
    {
        $backupPath = self::backupPath();

        $paths->each(function($path) use ($backupPath) {
            $path = self::normalizeDirectorySeparators($path);
            @mkdir($backupPath.$path, null, true);
            @rename(base_path($path), $backupPath.$path);
        });
    }

    private static function backupPath(): string
    {
        if (!isset(self::$backupPath))
        {
            self::$backupPath = storage_path(
                sprintf("statamic-sync-backups%s%s%s",
                    DIRECTORY_SEPARATOR,
                    date("Y-m-d-H-i-s"),
                    DIRECTORY_SEPARATOR)
            );

            @mkdir(self::$backupPath, null, true);
        }

        return self::$backupPath;
    }

    private static function normalizeDirectorySeparators($path): string
    {
        if (DIRECTORY_SEPARATOR === '/') {
            // unix, linux, mac
            return str_replace('\\', '/', $path);
        }

        if (DIRECTORY_SEPARATOR === '\\') {
            // windows
            return str_replace('/', '\\', $path);
        }

        return $path;
    }

}
