<?php

declare(strict_types=1);

require(__DIR__ . '/../vendor/autoload.php');

use UUP\Application\Console\Terminal;

$terminal = new Terminal();

$password = $terminal->password("password> ");
printf("Password: %s\n", $password);

$readline = $terminal->readline("readline> ");
printf("Readline: %s\n", $readline);
