<?php
/*
 * Fusio
 * A web-application to create dynamically RESTful APIs
 *
 * Copyright (C) 2015 Christoph Kappestein <k42b3.x@gmail.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * This is a simple control script to start and stop the internal php webserver
 * and selenium server. It writes a lock file to the cache file and as long as
 * the lock file exists the server runs. If we delete the file the server gets
 * shutdown. In this way we can spawn a process in the ant file and kill the
 * process when deleting the lock file.
 */

$service = isset($_SERVER['argv'][1]) ? $_SERVER['argv'][1] : null;
$action  = isset($_SERVER['argv'][2]) ? $_SERVER['argv'][2] : null;

$iniFile = DIRECTORY_SEPARATOR === '\\' ? 'build-win.properties' : 'build-default.properties';
$config  = parse_ini_file($iniFile);

// we are currently in the build dir we must change to the public dir to start
// a service
chdir(__DIR__ . '/../public');


$commands = [
    'webserver' => $config['php'] . ' -S 127.0.0.1:8008 server.php',
    'webdriver' => $config['nodejs'] . ' fusio/node_modules/protractor/bin/webdriver-manager start',
];

if (isset($commands[$service])) {
    if ($action == 'stop') {
        stopProcess($service);
    } else {
        startProcess($service, $commands[$service]);
    }
} else {
    echo 'Unknown service' . "\n";
    exit(1);
}


function startProcess($name, $cmd)
{
    echo 'Start ' . $name . ' (' . $cmd . ')' . "\n";

    $process = proc_open($cmd, array(), $pipes);
    $status  = proc_get_status($process);

    $pid  = $status['pid'];
    $file = __DIR__ . '/../cache/' . $name . '.lock';

    file_put_contents($file, $pid);
}

function stopProcess($name)
{
    $file = __DIR__ . '/../cache/' . $name . '.lock';

    if (is_file($file)) {
        $pid = (int) file_get_contents($file);

        if ($pid > 0) {
            // now we kill the process
            echo 'Try to kill process ' . $pid . "\n";

            if (DIRECTORY_SEPARATOR === '\\') {
                exec(sprintf('taskkill /F /T /PID %d', $pid), $output, $exitCode);
            } else {
                exec(sprintf('kill -9 %d', $pid), $output, $exitCode);
            }
        }
    }
}
