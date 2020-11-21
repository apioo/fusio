<?php

const FORMAT = 'php';

$schemas = [
    [
        'source' => 'gen/typeschema.json',
        'target' => 'src/Model',
        'config' => 'namespace=App\Model',
    ],
];

$cwd = __DIR__;
$baseDir = __DIR__ . '/..';

foreach ($schemas as $row) {
    $folder = $baseDir . '/' . $row['target'];
    if (!is_dir($folder)) {
        continue;
    }

    deleteFilesInFolder($folder);

    $args = array_map('escapeshellarg', [
        $row['source'],
        $row['target'],
        $row['config'],
        FORMAT,
    ]);

    $cmd = sprintf('php vendor/psx/schema/bin/schema schema:parse %s %s --config=%s --format=%s', ...$args);

    echo 'Generate ' . $row['source'] . "\n";
    echo '> ' . $cmd . "\n";

    chdir($baseDir);
    shell_exec($cmd);
    chdir($cwd);
}

function deleteFilesInFolder(string $folder): void
{
    $files = scandir($folder);
    foreach ($files as $file) {
        if ($file[0] === '.') {
            continue;
        }

        $path = $folder . '/' . $file;
        if (!is_file($path)) {
            continue;
        }

        unlink($path);
    }
}
