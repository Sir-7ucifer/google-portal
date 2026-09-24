<?php

function getClientMac($clientIP)
{
    return trim(exec("grep " . escapeshellarg($clientIP) . " /tmp/dhcp.leases | awk '{print $2}'"));
}

function getClientHostName($clientIP)
{
    return trim(exec("grep " . escapeshellarg($clientIP) . " /tmp/dhcp.leases | awk '{print $4}'"));
}

$logDir = '/tmp/evilportal';
if (!is_dir($logDir)) {
    @mkdir($logDir, 0777, true);
}

$logFile = $logDir . '/portal-consent.log';

$data = [
    'time' => date('c'),
    'ip' => $_SERVER['REMOTE_ADDR'] ?? '',
    'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
    'accepted_terms' => true,
    'mode' => 'the score'
];

if (is_dir($logDir)) {
    @file_put_contents(
        $logFile,
        json_encode($data) . PHP_EOL,
        FILE_APPEND | LOCK_EX
    );
}