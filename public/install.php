<?php
/*
 * Fusio
 * A web-application to create dynamically RESTful APIs
 *
 * Copyright (C) 2015-2020 Christoph Kappestein <christoph.kappestein@gmail.com>
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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $method = $_GET['method'] ?? '';

    switch ($method) {
        case 'adjustEnvFile':
            $scheme = parse_url($_POST['url'], PHP_URL_SCHEME);
            $host = parse_url($_POST['url'], PHP_URL_HOST);
            $path = parse_url($_POST['url'], PHP_URL_PATH);

            $env = [
                'FUSIO_PROJECT_KEY' => $_POST['key'] ?? '',
                'FUSIO_HOST'        => $host,
                'FUSIO_URL'         => $scheme . '://' . $host . $path,
                'FUSIO_APPS_URL'    => $scheme . '://' . $host . $path . '/apps',
                'FUSIO_DB_NAME'     => $_POST['db_name'] ?? '',
                'FUSIO_DB_USER'     => $_POST['db_user'] ?? '',
                'FUSIO_DB_PW'       => $_POST['db_pw'] ?? '',
                'FUSIO_DB_HOST'     => $_POST['db_host'] ?? '',
            ];

            $return = adjustEnvFile(__DIR__ . '/../.env', $env, $container->get('config'));
            break;

        case 'executeFusioMigration':
            $return = executeFusioMigration();
            break;

        case 'createAdminUser':
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            $email    = $_POST['email'] ?? '';

            $return = createAdminUser($username, $password, $email);
            break;

        case 'installBackendApp':
            $return = installBackendApp();
            break;

        case 'finishInstall':
            $return = finishInstall($container->get('config'));
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

function checkEnv(string $envFile, array $env, \PSX\Framework\Config\Config $config)
{
    // check folder writable
    $appsDir = $config->get('fusio_apps_dir');
    if (!is_writable($appsDir)) {
        alert('warning', 'It looks like the dir <code>' . $appsDir . '</code> is not writable');
        return false;
    }

    $cacheDir = $config->get('psx_path_cache');
    if (!is_writable($cacheDir)) {
        alert('warning', 'It looks like the dir <code>' . $cacheDir . '</code> is not writable');
        return false;
    }

    // check env file
    if (!is_file($envFile)) {
        alert('warning', 'It looks like the <code>.env</code> file does not exist');
        return false;
    }

    if (!is_writable($envFile)) {
        alert('warning', 'It looks like the <code>.env</code> file is not writable');
        return false;
    }

    if (empty($env['FUSIO_PROJECT_KEY'])) {
        alert('warning', 'Project key must contain a value');
        return false;
    }

    if (empty($env['FUSIO_HOST'])) {
        alert('warning', 'Url must contain a host');
        return false;
    }

    if (empty($env['FUSIO_URL']) || $env['FUSIO_URL'] === '://${FUSIO_HOST}') {
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

function adjustEnvFile(string $envFile, array $env, \PSX\Framework\Config\Config $config)
{
    if (!checkEnv($envFile, $env, $config)) {
        return false;
    }

    $data = (new Symfony\Component\Dotenv\Dotenv())->parse(file_get_contents($envFile));

    $content = '';
    foreach ($data as $key => $value) {
        if (isset($env[$key])) {
            $content.= $key . '="' . escapeQuotedString($env[$key]) . '"' . "\n";
        } else {
            $content.= $key . '="' . escapeQuotedString($value) . '"' . "\n";
        }
    }

    if (md5($content) != md5_file($envFile)) {
        $bytes = file_put_contents($envFile, $content);
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

function escapeQuotedString(string $value)
{
    return str_replace(['"', '$'], ['\\"', '\\$'], $value);
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

function createAdminUser(string $username, string $password, string $email)
{
    if (hasAdmin()) {
        return true;
    }

    try {
        \Fusio\Impl\Service\User\Validator::assertName($username);
        \Fusio\Impl\Service\User\Validator::assertPassword($password, 8);
        \Fusio\Impl\Service\User\Validator::assertEmail($email);
    } catch (\Exception $e) {
        alert('warning', $e->getMessage());
        return false;
    }

    $output = runCommand('adduser', ['--role' => '1', '--username' => $username, '--password' => $password, '--email' => $email], $exitCode);
    if ($exitCode == 0) {
        alert('success', 'Added admin user successful');
        return true;
    } else {
        alert('danger', 'Could not create admin account, you can add a new admin account later on using the command <code>bin/fusio adduser</code><pre>' . htmlspecialchars($output) . '</pre>');
        return false;
    }
}

function installBackendApp()
{
    if (is_dir(__DIR__ . '/apps/fusio')) {
        alert('success', 'Backend app already installed');
        return true;
    }

    $output = runCommand('marketplace:install', ['--disable_ssl_verify', 'name' => 'fusio'], $exitCode);
    if ($exitCode == 0) {
        alert('success', 'Installed backend app');
        return true;
    } else {
        alert('danger', 'Could not install backend app, you can install the backend app later on using the command <code>bin/fusio marketplace:install fusio</code><pre>' . htmlspecialchars($output) . '</pre>');
        return false;
    }
}

function finishInstall(\PSX\Framework\Config\Config $config)
{
    $apiUrl = $config->get('psx_url');
    $appsUrl = $config->get('fusio_apps_url');

    $text = <<<TEXT
<p>Installation successful!</p>
<dl>
  <dt>API-Url</dt>
  <dd><a href="{$apiUrl}">{$apiUrl}</a></dd>
  <dt>Apps-Url</dt>
  <dd><a href="{$appsUrl}">{$appsUrl}</a></dd>
</dl>
TEXT;

    alert('success', $text);

    return true;
}

function runCommand($command, array $params, &$exitCode)
{
    global $container;

    /** @var \Symfony\Component\Console\Application $app */
    $app = $container->get('console');
    $app->setAutoExit(false);

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
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
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
                <div id="progress" class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
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
                <p class="text-muted">Credentials of the admin account.</p>
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
            <input type="submit" value="Install" class="btn btn-primary">
        </div>
    </div>
</form>

<script type="text/javascript">
var methods = [
    "adjustEnvFile",
    "executeFusioMigration",
    "createAdminUser",
    "installBackendApp",
    "finishInstall"
];

var lang = {
    adjustEnvFile: "Adjusting environment file ...",
    executeFusioMigration: "Executing database migration ...",
    createAdminUser: "Creating admin user ...",
    installBackendApp: "Installing backend app ...",
    finishInstall: "Finishing installation ..."
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

    if (method === 'adjustEnvFile') {
        params = {
            key: $("#key").val(),
            url: $("#url").val(),
            db_name: $("#dbName").val(),
            db_user: $("#dbUser").val(),
            db_pw: $("#dbPw").val(),
            db_host: $("#dbHost").val()
        };
    } else if (method === 'createAdminUser') {
        params = {
            username: $("#username").val(),
            password: $("#password").val(),
            email: $("#email").val()
        };
    } else if (method === 'login') {
        params = {
            username: $("#username").val(),
            password: $("#password").val()
        };
    }

    $("#messages").html('<div class="spinner-border text-primary spinner-border-sm" role="status"><span class="sr-only">Loading ...</span></div>&nbsp;' + lang[method]);
    $.post('install.php?method=' + method, params, function(data){
        $("#messages").html('');
        if (data.success) {
            methods.shift();

            var done = complete - methods.length;
            var per = Math.floor(done * 100 / complete);
            $("#progress").css("width", per + "%");
            $("#progress").text(done +  " / " + complete);

            if (done !== complete) {
                runNextAction();
            }
        }

        if (data.messages) {
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
