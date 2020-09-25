<?php

namespace Outlawplz\Duplicity\Commands;

use Illuminate\Console\Command;
use Outlawplz\Duplicity\Duplicity;

class DuplicityRestore extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'duplicity:restore';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Restore previous backup';

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
            ->noEncryption()
            ->restore(
                config('duplicity.restore_url'),
                config('duplicity.restore_to_directory')
            );

        $duplicity->mustRun(function ($type, $buffer) {
            echo $buffer;
        });
    }
}