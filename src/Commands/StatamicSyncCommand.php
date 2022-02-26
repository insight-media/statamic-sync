<?php

namespace InsightMedia\StatamicSync\Commands;

use Illuminate\Console\Command;
use InsightMedia\StatamicSync\StatamicSync;

class StatamicSyncCommand extends Command
{
    public $signature = 'statamic:sync';

    public $description = 'Download Statamic content from server and replace with local files';

    public function handle(): int
    {

        try {
            StatamicSync::sync();

            $this->comment('All done');

            return self::SUCCESS;
        }
        catch (\Exception $e)
        {
            $this->error($e->getMessage());

            return self::FAILURE;
        }

    }
}
