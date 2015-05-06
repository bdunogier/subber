#!/usr/bin/env php
<?php
/**
 * Proxies execution of sab post processing script (exit code & output included)
 */

$arguments = '';

if (count($argv) > 1) {
    $proxyArguments = array_map(
        function ($value) {
            return escapeshellarg($value);
        },
        array_slice($argv, 1)
    );
    $arguments = implode(" ", $proxyArguments);
}

$exitCode = 0;
$originalOutput = [];
runScript($arguments, $exitCode, $originalOutput);

if ($exitCode !== 0) {
    echo "Post Processing script returned an exit code != 0\n";
    exit($exitCode);
}

$output = array_filter(
    $originalOutput,
    function ($value) {
        return strlen(trim($value)) > 0;
    }
);

if (isSuccess($output)) {
    list($originalPath, $newPath) = extractPathData($output);
    $originalName = pathinfo($originalPath, PATHINFO_FILENAME);
    subber_queue(['path' => $newPath, 'original_name' => $originalName]);
}

// get new name
echo join("\n", $originalOutput);
if (!isSuccess($output)) {
    exit(1);
}

function runScript($arguments, &$exitCode, array &$output)
{
    $command = __DIR__ . "/sabToSickBeard.py $arguments";
    if (getenv('BD_TEST') == 1) {
        echo "BD_TEST: Command: $command\n\n";
    }

    exec($command, $output, $exitCode);
}

function isSuccess(array $output)
{
    $lines = array_filter(
        $output,
        function ($value) {
            return strstr($value, 'Processing succeeded for') !== false;
        }
    );

    return count($lines) === 1;
}

function extractPathData(array $output)
{
    $lines = array_filter(
        $output,
        function ($value) {
            return (strstr($value, "Moving file from ") !== false);
        }
    );

    if (count($lines) != 1) {
        return false;
    }

    $line = array_pop($lines);
    if (!preg_match('/^Moving file from (.*) to (.*)$/', $line, $m)) {
        return false;
    }
    return [trim($m[1]), trim($m[2])];
}

function subber_queue(array $fields)
{
    $options = [
        'http' => [
            'header' => "Content-type: application/json\r\n",
            'method' => 'POST',
            'content' => json_encode($fields)
        ]
    ];
    $context = stream_context_create($options);
    file_get_contents(get_config('subber_host') . '/subber/watchlist', false, $context);
}

function get_config($key)
{
    static $configuration;

    if ($configuration === null) {
        $configFile = __DIR__ . '/sabToSickbeard_wrapper.json';
        if (!is_file($configFile)) {
            echo "Missing configuration file\n";
            exit(2);
        }

        $configuration = json_decode(file_get_contents($configFile), true);
    }

    if (!isset($configuration[$key])) {
        throw new \Exception("Unknown configuration key $key");
    }

    return $configuration[$key];
}
