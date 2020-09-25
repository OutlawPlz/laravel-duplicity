<?php

namespace Outlawplz\Duplicity;

use Symfony\Component\Process\Process;

class Duplicity
{
    /** @var string[] */
    protected $command = [];

    /** @var string|null */
    protected $cwd;

    /** @var string[]|null */
    protected $env;

    /** @var mixed|null */
    protected $input;

    /** @var float|int|null */
    protected $timeout;

    /**
     * @param string|null $cwd
     * @param array|null $env
     * @param mixed|null $input
     * @param float|int|null $timeout
     */
    public function __construct(string $cwd = null, array $env = null, $input = null, ?float $timeout = 3600)
    {
        $this->cwd = $cwd;
        $this->env = $env;
        $this->input = $input;
        $this->timeout = $timeout;
    }

    /**
     * @param string $fromDirectory
     * @param string $toUrl
     * @param string|null $backup Valid values are "full" and "incremental".
     * @return Process
     */
    public function backup(string $fromDirectory, string $toUrl, string $backup = null): Process
    {
        $commands = ['duplicity'];

        if (! is_null($backup)) $commands[] = $backup;

        array_push($commands, $fromDirectory, $toUrl);

        array_unshift($this->command, ...$commands);

        return $this->process();
    }

    /**
     * @param string $fromUrl
     * @param string $toDirectory
     * @return Process
     */
    public function restore(string $fromUrl, string $toDirectory): Process
    {
        array_unshift($this->command, 'duplicity', 'restore', $fromUrl, $toDirectory);

        return $this->process();
    }

    /**
     * Calculate what would be done, but do not perform any backend actions.
     *
     * @return $this
     */
    public function dryRun(): Duplicity
    {
        $this->command[] = '--dry-run';

        return $this;
    }

    /**
     * Do not use GnuPG to encrypt files on remote system.
     *
     * @return $this
     */
    public function noEncryption(): Duplicity
    {
        $this->command[] = '--no-encryption';

        return $this;
    }

    /**
     * Duplicity will output the current upload progress and estimated upload time.
     *
     * @return $this
     */
    public function progressBar(): Duplicity
    {
        $this->command[] = '--progress';

        return $this;
    }

    /**
     * Exclude the file or files matched by shell_pattern.
     *
     * @param string[] $excludes
     * @return $this
     */
    public function exclude(array $excludes = []): Duplicity
    {
        if (empty($excludes)) return $this;

        foreach ($excludes as $path)
            array_push($this->command, '--exclude', $path);

        return $this;
    }

    /**
     * Reset command status.
     */
    protected function clearCommand()
    {
        $this->command = [];
    }

    /**
     * Build the process.
     *
     * @return Process
     */
    protected function process(): Process
    {
        $process = new Process($this->command, $this->cwd, $this->env, $this->input, $this->timeout);

        $process->setIdleTimeout(60);

        $this->clearCommand();

        return $process;
    }
}
