<?php

namespace Matks\GitSpam;

use Github\Client;

/**
 * GitSpam Script launcher
 *
 * Create and configure GitSpam before running it
 */
class Launcher
{
    /**
	 * Main function
	 */
    public static function main()
    {
        $gitSpam = static::setup();

        $gitSpam->run();
    }

    /**
	 * Construct GitSpam instance
	 * @return GitSpam
	 */
    public static function setup(array $configuration)
    {
        $client = new Client();
        $gitSpam = new GitSpam($client);

        return $gitSpam;
    }
}
