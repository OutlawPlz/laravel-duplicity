<?php

namespace Outlawplz\Duplicity;

use Symfony\Component\Process\Process;

/**
 * @property-read string[] $command
 */
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
     * @param string|null $force Accepted values are "full" and "incremental".
     * @param callable|null $callback
     * @return string Process output.
     */
    public function backup(string $fromDirectory, string $toUrl, string $force = null, callable $callback = null): string
    {
        $commands = ['duplicity'];

        if (! empty($force)) $commands[] = $force;

        array_push($commands, $fromDirectory, $toUrl);

        array_unshift($this->command, ...$commands);

        return $this->runProcess($callback);
    }

    /**
     * @param string $fromUrl
     * @param string $toDirectory
     * @param callable|null $callback
     * @return string Process output.
     */
    public function restore(string $fromUrl, string $toDirectory, callable $callback = null): string
    {
        array_unshift($this->command, 'duplicity', 'restore', $fromUrl, $toDirectory);

        return $this->runProcess($callback);
    }

    /**
     * Calculate what would be done, but do not perform any backend actions.
     *
     * @return $this
     */
    public function dryRun(): Duplicity
    {
        if (! in_array('--dry-run', $this->command))
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
        if (! in_array('--no-encryption', $this->command))
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
        if (! in_array('--progress', $this->command))
            $this->command[] = '--progress';

        return $this;
    }

    /**
     * Exclude the file or files matched by shell_pattern.
     *
     * @param string|string[] $excludes
     * @return $this
     */
    public function exclude(string ...$excludes): Duplicity
    {
        foreach ($excludes as $path)
            array_push($this->command, '--exclude', $path);

        return $this;
    }

    /**
     * Instantiate a process and run it.
     *
     * @param callable|null $callback
     * @return string
     */
    protected function runProcess(callable $callback = null): string
    {
        $process = new Process($this->command, $this->cwd, $this->env, $this->input, $this->timeout);

        // Reset command status.
        $this->command = [];

        $process->setIdleTimeout(60);

        $process->mustRun($callback);

        return $process->getOutput();
    }

    /**
     * @param string $property
     * @return string[]|null
     */
    public function __get(string $property)
    {
        if ($property !== 'command') return null;

        return $this->command;
    }
}
