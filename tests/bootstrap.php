<?php

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

ini_set('memory_limit', '512M');
putenv(sprintf('DATABASE_NAME=%s', md5((string) rand())));

