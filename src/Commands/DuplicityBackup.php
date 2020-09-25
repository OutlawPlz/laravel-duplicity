<?php

namespace Outlawplz\Duplicity\Commands;

use Illuminate\Console\Command;
use Outlawplz\Duplicity\Duplicity;

class DuplicityBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'duplicity:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup the application';

    /**
     * Duplicity backup.
     *
     * @var Duplicity
     */
    protected $duplicity;

    /**
     * Create a new command instance.
     *
     * @param Duplicity $duplicity
     * @return void
     */
    public function __construct(Duplicity $duplicity)
    {
        parent::__construct();

        $this->duplicity = $duplicity;
    }

    /**
     * Execute the console command.
     *
     * @throws \Symfony\Component\Process\Exception\ProcessFailedException
     */
    public function handle()
    {
        $duplicity = $this->duplicity
            ->progressBar()
            ->noEncryption()
            ->exclude(
                config('duplicity.excludes')
            )
            ->backup(
                config('duplicity.backup_directory'),
                config('duplicity.backup_to_url')
            );

        $duplicity->mustRun(function ($type, $buffer) {
            echo $buffer;
        });
    }
}
