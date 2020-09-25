<?php

namespace Outlawplz\Duplicity\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method \Symfony\Component\Process\Process backup(string $fromDirectory, string $toUrl, string $backup = null)
 * @method \Symfony\Component\Process\Process restore(string $fromUrl, string $toDirectory)
 * @method \Outlawplz\Duplicity\Duplicity dryRun()
 * @method \Outlawplz\Duplicity\Duplicity noEncryption()
 * @method \Outlawplz\Duplicity\Duplicity progressBar()
 * @method \Outlawplz\Duplicity\Duplicity exclude(array $excludes = [])
 *
 * @see \Outlawplz\Duplicity\Duplicity
 */
class Duplicity extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     * @throws \RuntimeException
     */
    protected static function getFacadeAccessor(): string
    {
        return 'duplicity';
    }
}
