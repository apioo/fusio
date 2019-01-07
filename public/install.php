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

require_once(__DIR__ . '/../vendor/autoload.php');

/**
 * NOTE this installer helps to setup Fusio through a browser. It simply 
 * executes the steps of a manual installation. After successful installation
 * you should delete this installer script
 */

ignore_user_abort(true);
set_time_limit(0);

$container = require_once(__DIR__ . '/../container.php');
$messages  = [];

PSX\Framework\Bootstrap::setupEnvironment($container->get('config'));

/** @var \Symfony\Component\Console\Application $app */
$app = $container->get('console');
$app->setAutoExit(false);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $method = $_GET['method'] ?? '';

    switch ($method) {
        case 'adjustEnvFile':
            $env = [
                'FUSIO_PROJECT_KEY' => $_POST['key'] ?? '',
                'FUSIO_URL'         => $_POST['url'] ?? '',
                'FUSIO_DB_NAME'     => $_POST['db_name'] ?? '',
                'FUSIO_DB_USER'     => $_POST['db_user'] ?? '',
                'FUSIO_DB_PW'       => $_POST['db_pw'] ?? '',
                'FUSIO_DB_HOST'     => $_POST['db_host'] ?? '',
            ];

            $return = adjustEnvFile(__DIR__ . '/../.env', $env);
            break;

        case 'executeFusioMigration':
            $return = executeFusioMigration();
            break;

        case 'executeAppMigration':
            $return = executeAppMigration();
            break;

        case 'executeDeploy':
            $return = executeDeploy();
            break;

        case 'createAdminUser':
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            $email    = $_POST['email'] ?? '';

            $return = createAdminUser($username, $password, $email);
            break;

        default:
            $return = false;
            break;
    }

    header('Content-Type: application/json');
    echo \json_encode([
        'success' => $return,
        'messages' => $messages,
    ]);
    exit;
}

function validateInput(array $env, $username, $password, $email)
{
    if (empty($env['FUSIO_PROJECT_KEY'])) {
        alert('warning', 'Project key must contain a value');
        return false;
    }

    if (empty($env['FUSIO_URL'])) {
        alert('warning', 'Url must contain a value');
        return false;
    }

    if (empty($env['FUSIO_DB_NAME'])) {
        alert('warning', 'Database name must contain a value');
        return false;
    }

    if (empty($env['FUSIO_DB_USER'])) {
        alert('warning', 'Database user must contain a value');
        return false;
    }

    if (empty($env['FUSIO_DB_HOST'])) {
        alert('warning', 'Database host must contain a value');
        return false;
    }

    if (!preg_match('/' . \Fusio\Impl\Backend\Schema\User::NAME_PATTERN . '/', $username)) {
        alert('warning', 'Invalid username value');
        return false;
    }

    try {
        \Fusio\Impl\Service\User\PasswordComplexity::assert($password, 8);
    } catch (\Exception $e) {
        alert('warning', $e->getMessage());
        return false;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        alert('warning', 'Invalid email format');
        return false;
    }

    return true;
}

function checkEnv($envFile, array $env)
{
    // check env file
    if (!is_file($envFile)) {
        alert('warning', 'It looks like the <code>.env</code> file does not exist');
        return false;
    }

    if (!is_writable($envFile)) {
        alert('warning', 'It looks like the <code>.env</code> file is not writable');
        return false;
    }

    // check whether we can connect to the db with these credentials
    $params = [
        'dbname'   => $env['FUSIO_DB_NAME'],
        'user'     => $env['FUSIO_DB_USER'],
        'password' => $env['FUSIO_DB_PW'],
        'host'     => $env['FUSIO_DB_HOST'],
        'driver'   => 'pdo_mysql',
    ];

    try {
        $connection = \Doctrine\DBAL\DriverManager::getConnection($params);

        if (!$connection->ping()) {
            alert('warning', 'Could not connect to database');
            return false;
        }
    } catch (\Doctrine\DBAL\DBALException $e) {
        alert('warning', 'Could not connect to database');
        return false;
    }

    return true;
}

function hasAdmin()
{
    runCommand('system:check', ['name' => 'user'], $exitCode);
    return $exitCode === 0;
}

function adjustEnvFile($envFile, array $env)
{
    if (!checkEnv($envFile, $env)) {
        return false;
    }

    $content  = file_get_contents($envFile);
    $modified = $content;

    foreach ($env as $envKey => $envValue) {
        $envValue = addslashes($envValue);
        $modified = preg_replace('/' . $envKey . '="(.*)"/imsU', $envKey . '="' . $envValue . '"', $modified, 1);
    }

    if ($modified != $content) {
        $bytes = file_put_contents($envFile, $modified);
        if ($bytes) {
            alert('success', 'Adjusted <code>.env</code> file successful');
            return true;
        } else {
            alert('danger', 'Could not write <code>.env</code> file');
            return false;
        }
    }

    return true;
}

function executeFusioMigration()
{
    $output = runCommand('migration:migrate', [], $exitCode);
    if ($exitCode === 0) {
        alert('success', 'Installation successful');
        return true;
    } else {
        alert('danger', 'An error occurred on installation:<pre>' . htmlspecialchars($output) . '</pre>');
        return false;
    }
}

function executeAppMigration()
{
    $output = runCommand('migration:migrate', ['--connection' => 'System'], $exitCode);
    if ($exitCode === 0) {
        alert('success', 'App migration successful');
        return true;
    } else {
        alert('danger', 'An error occurred on app migration:<pre>' . htmlspecialchars($output) . '</pre>');
        return false;
    }
}

function executeDeploy()
{
    $output = runCommand('deploy', [], $exitCode);
    if ($exitCode === 0) {
        alert('success', 'Deploy successful');
        return true;
    } else {
        alert('danger', 'An error occurred on deploy:<pre>' . htmlspecialchars($output) . '</pre>');
        return false;
    }
}

function createAdminUser($username, $password, $email)
{
    if (hasAdmin()) {
        return true;
    }

    runCommand('adduser', ['--status' => '1', '--username' => $username, '--password' => $password, '--email' => $email], $exitCode);
    if ($exitCode == 0) {
        alert('success', 'Added admin user successful');
        return true;
    } else {
        alert('danger', 'Could not create admin account, you can add a new admin account later on using the command <code>bin/fusio adduser</code>');
        return false;
    }
}

function runCommand($command, array $params = [], &$exitCode)
{
    global $app;

    $input = new \Symfony\Component\Console\Input\ArrayInput(array_merge(['command' => $command], $params));
    $input->setInteractive(false);

    $output = new \Symfony\Component\Console\Output\BufferedOutput();

    try {
        $exitCode = $app->run($input, $output);

        return $output->fetch();
    } catch (\Throwable $e) {
        $exitCode = 1;

        return $e->getMessage();
    }
}

function alert($level, $message)
{
    global $messages;

    if (!isset($messages[$level])) {
        $messages[$level] = [];
    }

    $messages[$level][] = $message;
}

?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
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
        
        #messages {
            margin-top:8px;
        }
    </style>
</head>
<body>

<form method="POST" id="installer">
<div class="container fusio-installer">
    <div class="row">
        <div class="col">
            <div class="alert alert-primary">
                <b>Welcome</b>, this installer helps to setup <a href="https://www.fusio-project.org">Fusio</a>.
                It simply executes the steps of a <a href="https://www.fusio-project.org/bootstrap">manual
                installation</a>. <b>After successful installation your should
                delete this installer script.</b>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="progress">
                <div id="progress" class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
            </div>
            <div id="messages"></div>
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
                    <input type="text" name="db_name" id="dbName" value="<?php echo htmlspecialchars($_POST['db_name'] ?? ''); ?>" placeholder="Database name" required class="form-control">
                </div>
                <div class="form-group">
                    <label for="db_user">User:</label>
                    <input type="text" name="db_user" id="dbUser" value="<?php echo htmlspecialchars($_POST['db_user'] ?? ''); ?>" placeholder="Database user" required class="form-control">
                </div>
                <div class="form-group">
                    <label for="db_pw">Password:</label>
                    <input type="password" name="db_pw" id="dbPw" value="" placeholder="Database password" class="form-control">
                </div>
                <div class="form-group">
                    <label for="db_host">Host:</label>
                    <input type="text" name="db_host" id="dbHost" value="<?php echo htmlspecialchars($_POST['db_host'] ?? ''); ?>" placeholder="Database host" required class="form-control">
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
                    <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" placeholder="Username" required minlength="3" class="form-control">
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" name="password" id="password" placeholder="Password" minlength="8" required class="form-control">
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" placeholder="Email" required class="form-control">
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
                    <input type="text" name="key" id="key" placeholder="Project key" value="<?php echo htmlspecialchars($_POST['key'] ?? md5(uniqid())); ?>" required aria-describedby="keyHelp" class="form-control">
                </div>
                <div class="form-group">
                    <label for="url">Url:</label>
                    <input type="url" name="url" id="url" placeholder="Url" value="<?php echo htmlspecialchars($_POST['url'] ?? ''); ?>" required aria-describedby="urlHelp" class="form-control">
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
var methods = [
    "adjustEnvFile",
    "executeFusioMigration",
    "executeAppMigration",
    "executeDeploy",
    "createAdminUser"
];

var lang = {
    adjustEnvFile: "Adjusting environment file ...",
    executeFusioMigration: "Executing database migration ...",
    executeAppMigration: "Executing app migration ...",
    executeDeploy: "Executing deploy ...",
    createAdminUser: "Creating admin user ..."
};

var complete = methods.length;

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

function runNextAction() {
    var method = methods[0];
    var params = {};

    if (method === "checkEnv" || method === "adjustEnvFile") {
        params = {
            key: $("#key").val(),
            url: $("#url").val(),
            db_name: $("#dbName").val(),
            db_user: $("#dbUser").val(),
            db_pw: $("#dbPw").val(),
            db_host: $("#dbHost").val()
        };
    } else if (method === "createAdminUser") {
        params = {
            username: $("#username").val(),
            password: $("#password").val(),
            email: $("#email").val()
        };
    }

    $("#messages").html(lang[method]);
    $.post('install.php?method=' + method, params, function(data){
        $("#messages").html('');
        if (data.success) {
            methods.shift();

            var done = complete - methods.length;
            var per = Math.floor(done * 100 / complete);
            $("#progress").css("width", per + "%");
            $("#progress").text(done +  " / " + complete);

            if (done === complete) {
                $(".fusio-installer").html("<div class='alert alert-success' role='alert'>Installation successful! You can now login to the <a href='./fusio'>backend</a> to build your <a href='./'>API</a>.</div>");
            } else {
                runNextAction();
            }
        } else if (data.messages) {
            for (var type in data.messages) {
                var msgs = data.messages[type];
                for (var i = 0; i < msgs.length; i++) {
                    $("#messages").append("<div class='alert alert-" + type + "' role='alert'>" + msgs[i] + "</div>");
                }
            }
        }
    });
}

function runInstallation(e) {
    e.preventDefault();
    runNextAction();
    $('body,html').animate({
        scrollTop: 0
    }, 400);
}

$(document).ready(function(){
    if (!$("#url").val()) {
        $("#url").val(guessEndpointUrl(false));
    }

    $("#installer").submit(runInstallation);
    $("#progress").text("0 / " + complete);
});

</script>

</body>
</html>
