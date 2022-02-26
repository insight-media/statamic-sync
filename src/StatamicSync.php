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

        $ssh->addExtraOption('-r');
        $process = null;

        self::backup($paths);

        $paths->each(function($dir) use (&$ssh, &$process) {
            if (!file_exists(base_path($dir))) mkdir(base_path($dir));
            $process = $ssh->download(self::$remotePath.DIRECTORY_SEPARATOR.$dir, base_path($dir));
        });

        if ($process->isSuccessful())
        {
            self::deleteBackup();
        }
        else
        {
            throw new \Exception(!empty($process->getOutput()) ? $process->getOutput() : "Something went wrong!");
        }
    }

    private static function connection(): Ssh
    {
        if (!isset(self::$ssh))
        {

            $user = config('statamic-sync.ssh.user');
            $host = config('statamic-sync.ssh.host');
            $port = config('statamic-sync.ssh.port');
            $path = config('statamic-sync.ssh.path');

            if (empty($user) || empty($host) || empty($path)) throw new \Exception('Missing required config settings (user, host or path)');

            self::$ssh = Ssh::create(
                $user,
                $host,
                $port
            )->onOutput(function($type, $line) { echo $line; });

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

    private static function deleteBackup(): void
    {
        rmdir(self::backupPath());
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
