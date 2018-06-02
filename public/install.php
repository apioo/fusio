<?php
/*
 * Fusio
 * A web-application to create dynamically RESTful APIs
 *
 * Copyright (C) 2015-2018 Christoph Kappestein <christoph.kappestein@gmail.com>
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
 * NOTE this installer helps to setup Fusio through a browser. It simply executes the steps of a manual installation. 
 * After successful installation your should delete this installer script. Since it only executes PHP commands you also
 * need to have the PHP CLI installed
 */

ignore_user_abort(true);
set_time_limit(0);

$success  = [];
$warnings = [];
$errors   = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $key      = $_POST['key'] ?? null;
    $url      = $_POST['url'] ?? null;
    $dbName   = $_POST['db_name'] ?? null;
    $dbUser   = $_POST['db_user'] ?? null;
    $dbPw     = $_POST['db_pw'] ?? null;
    $dbHost   = $_POST['db_host'] ?? null;

    $username = $_POST['username'] ?? null;
    $password = $_POST['password'] ?? null;
    $email    = $_POST['email'] ?? null;

    $env = [
        'FUSIO_PROJECT_KEY' => $key,
        'FUSIO_URL'         => $url,
        'FUSIO_DB_NAME'     => $dbName,
        'FUSIO_DB_USER'     => $dbUser,
        'FUSIO_DB_PW'       => $dbPw,
        'FUSIO_DB_HOST'     => $dbHost,
    ];

    $envFile = __DIR__ . '/../.env';

    $output = [];
    exec('php -v', $output, $exitCode);
    if ($exitCode > 0) {
        $warnings[] = 'It looks like the PHP CLI is not available';
    }

    $output = [];
    exec('php ../bin/fusio --version', $output, $exitCode);
    if ($exitCode > 0) {
        $warnings[] = 'It looks like bin/fusio is not available, you may need to run <code>composer install</code>';
    }

    if (!is_file($envFile)) {
        $warnings[] = 'It looks like the <code>.env</code> file does not exist';
    }

    if (!is_writable($envFile)) {
        $warnings[] = 'It looks like the <code>.env</code> file is not writable';
    }

    // we only proceed if we have no warnings
    if (count($warnings) == 0) {
        $isInstalled = false;
        $output = [];
        exec('php ../bin/fusio system:check install', $output, $exitCode);
        if ($exitCode === 0) {
            $isInstalled = true;
        }

        $hasAdmin = false;
        $output = [];
        exec('php ../bin/fusio system:check user', $output, $exitCode);
        if ($exitCode === 0) {
            $hasAdmin = true;
        }

        if (!$isInstalled) {
            // adjust env
            $content  = file_get_contents($envFile);
            $modified = $content;

            foreach ($env as $envKey => $envValue) {
                $modified = preg_replace('/' . $envKey . '="(.*)"/imsU', $envKey . '="' . $envValue . '"', $modified, 1);
            }

            if ($modified != $content) {
                $bytes = file_put_contents($envFile, $modified);
                if ($bytes) {
                    $success[] = 'Adjusted <code>.env</code> file successful';
                } else {
                    $errors[] = 'Could not write <code>.env</code> file';
                }
            }

            // execute install
            $output = [];
            exec('php ../bin/fusio install 2>&1', $output, $exitCode);
            if ($exitCode == 0) {
                $success[] = 'Installation successful';
            } else {
                $errors[] = 'An error occurred on installation:<pre>' . implode("\n", $output). '</pre>';
            }

            if (!$hasAdmin) {
                // create admin user
                $output = [];
                exec('php ../bin/fusio adduser --status="1" --username="' . escapeshellarg($username) . '" --password="' . escapeshellarg($password) . '" --email="' . escapeshellarg($email) . '"', $output, $exitCode);
                if ($exitCode == 0) {
                    $success[] = 'Added admin user successful';
                } else {
                    $errors[] = 'Created admin account successful';
                }
            } else {
                $warnings[] = 'Admin user already available';
            }
        } else {
            $warnings[] = 'It looks like Fusio is already <a href="./">installed</a>';
        }
    }
} else {
    $key      = md5(uniqid());
    $url      = null;
    $dbName   = null;
    $dbUser   = null;
    $dbPw     = null;
    $dbHost   = null;

    $username = null;
    $password = null;
    $email    = null;

}

$messages = [
    'success' => $success,
    'warning' => $warnings,
    'danger'  => $errors,
];

?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
    <style type="text/css">
        .fusio-installer {
            max-width:600px;
            margin-top:32px;
            margin-bottom:128px;
        }

        .fusio-installer legend {
            border-bottom:1px solid #ccc;
        }

        .fusio-installer fieldset {
            margin-bottom:16px;
        }
    </style>
</head>
<body>

<form method="POST">
<div class="container fusio-installer">
    <div class="row">
        <div class="col">
            <div class="alert alert-primary">
                <b>Welcome</b>, this installer helps to setup <a href="https://www.fusio-project.org">Fusio</a>.
                It simply executes the steps of a <a href="https://www.fusio-project.org/bootstrap">manual
                installation</a>. <b>After successful installation your should
                delete this installer script.</b>
            </div>
            <?php foreach ($messages as $type => $list): ?>
            <?php if(!empty($list)): ?>
            <div class="alert alert-<?php echo $type; ?>">
                <ul>
                <?php foreach($list as $message): ?>
                    <li><?php echo $message; ?></li>
                <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <fieldset>
                <legend>Database</legend>
                <p class="text-muted">Connection credentials to the database. <b>Please be aware that Fusio 
                needs a dedicated database</b>, it will delete any table on the database which does not belong
                to the Fusio schema.</p>
                <div class="form-group">
                    <label for="db_name">Name:</label>
                    <input type="text" name="db_name" id="dbName" value="<?php echo $dbName; ?>" placeholder="Database name" required class="form-control">
                </div>
                <div class="form-group">
                    <label for="db_user">User:</label>
                    <input type="text" name="db_user" id="dbUser" value="<?php echo $dbUser; ?>" placeholder="Database user" required class="form-control">
                </div>
                <div class="form-group">
                    <label for="db_pw">Password:</label>
                    <input type="password" name="db_pw" id="dbPw" value="" placeholder="Database password" class="form-control">
                </div>
                <div class="form-group">
                    <label for="db_host">Host:</label>
                    <input type="text" name="db_host" id="dbHost" value="<?php echo $dbHost; ?>" placeholder="Database host" required class="form-control">
                </div>
            </fieldset>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <fieldset>
                <legend>User</legend>
                <p class="text-muted">Credentials of the admin account. After installation you can login to the
                backend at <a href="./fusio">/fusio</a>.</p>
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" name="username" id="username" value="<?php echo $username; ?>" placeholder="Username" required class="form-control">
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" name="password" id="password" placeholder="Password" minlength="8" required class="form-control">
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" value="<?php echo $email; ?>" placeholder="Email" required class="form-control">
                </div>
            </fieldset>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <fieldset>
                <legend>Settings</legend>
                <p class="text-muted">General project settings which contain already
                useful default values.</p>
                <div class="form-group">
                    <label for="key">Project-Key:</label>
                    <input type="text" name="key" id="key" placeholder="Project key" value="<?php echo $key; ?>" required aria-describedby="keyHelp" class="form-control">
                </div>
                <div class="form-group">
                    <label for="url">Url:</label>
                    <input type="url" name="url" id="url" placeholder="Url" value="<?php echo $url; ?>" required aria-describedby="urlHelp" class="form-control">
                </div>
            </fieldset>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <hr>
            <input type="submit" value="Install" class="btn btn-default">
        </div>
    </div>
</form>

<script type="text/javascript">
function guessEndpointUrl() {
    var removePart = function(url, sign) {
        var count = (url.match(/\//g) || []).length;
        var pos = url.lastIndexOf(sign);
        if (count > 2 && pos !== -1) {
            return url.substring(0, pos);
        }
        return url;
    };

    var url = window.location.href;
    url = url.replace('/<?php echo basename(__FILE__); ?>', '');

    var parts = ['#', '?'];
    for (var i = 0; i < parts.length; i++) {
        url = removePart(url, parts[i]);
    }

    return url;
}

if (!document.getElementById("url").getAttribute("value")) {
    document.getElementById("url").setAttribute("value", guessEndpointUrl(false));
}
</script>

</body>
</html>





