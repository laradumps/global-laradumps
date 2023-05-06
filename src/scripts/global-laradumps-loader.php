<?php

declare(strict_types = 1);

namespace LaraDumps\GlobalLaraDumps;

use Throwable;

try {
    loadLaraDumps();
} catch (Throwable) {
}

/**
 * Tries to load the LaraDumps package, either from a Phar archive or from a Composer dependency.
 *
 * @return void
 * @throws Throwable
 */
function loadLaraDumps(): void
{
    try {
        $unlessDetectedPackages = [
            'laradumps/laradumps',
            'laradumps/laradumps-core',
        ];

        $phpVersion = PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION;

        $pharPath = __DIR__ . "/../../phars/laradumps_{$phpVersion}.phar";

        $composerPath = getComposerPath();

        if (fileExists($composerPath)) {
            $composer = (array) json_decode(strval(file_get_contents($composerPath)), true);

            foreach (['require', 'require-dev'] as $require) {
                /** @var array $required */
                $required = $composer[$require] ?? [];

                foreach ($required as $package => $version) {
                    if (in_array($package, $unlessDetectedPackages)) {
                        return;
                    }
                }
            }
        }

        if (fileExists($pharPath)) {
            include_once $pharPath;
        }

        $GLOBALS['__composer_autoload_files'] = [];
    } catch (Throwable $throwable) {
        throw new $throwable();
    }
}

/**
 * Returns the path to the Composer JSON file, or the default 'composer.json' file if not found.
 *
 * @return string
 */
function getComposerPath(): string
{
    $composerJson = getcwd() . '/composer.json';

    if (str_contains($composerJson, 'valet')) {
        $valetConfigPath = $_SERVER['HOME'] . '/.config/valet/config.json';

        if (fileExists($valetConfigPath)) {
            $valetConfig = (object) json_decode(strval(file_get_contents($valetConfigPath)));

            foreach ($valetConfig->paths as $path) {
                $composerPath = "{$path}/{$valetConfig->tld}/{$_SERVER['HTTP_HOST']}/composer.json";

                if (fileExists($composerPath)) {
                    return $composerPath;
                }
            }
        }
    }

    return $composerJson;
}

/**
 * Checks if a file exists and is readable.
 *
 * @param string $path
 * @return bool
 */
function fileExists(string $path): bool
{
    return file_exists($path) && is_readable($path);
}
