<?php

function getClientMac($clientIP)
{
    return trim(exec("grep " . escapeshellarg($clientIP) . " /tmp/dhcp.leases | awk '{print $2}'"));
}

function getClientSSID($clientIP)
{
    $mac = strtoupper(getClientMac($clientIP));

    if (file_exists("/tmp/log.db")) {
        $db = new SQLite3("/tmp/log.db");
        $results = $db->query("select ssid from log WHERE mac = '{$mac}' AND log_type = 0 ORDER BY updated_at DESC LIMIT 1;");
        $ssid = '';
        while ($row = $results->fetchArray()) {
            $ssid = $row['ssid'];
            break;
        }
        $db->close();
        if (!empty($ssid)) {
            return $ssid;
        }
    }

    $pineAPLogPath = trim(@file_get_contents('/etc/pineapple/pineap_log_location') ?: @file_get_contents('/root/logs/pineaplogs'));
    if (empty($pineAPLogPath)) {
        return '';
    }

    $pineAPLogPath = rtrim($pineAPLogPath, '/');
    $logFile = $pineAPLogPath . '/pineap.log';
    if (!file_exists($logFile)) {
        return '';
    }

    $ssid = trim(exec("grep " . escapeshellarg($mac) . " " . escapeshellarg($logFile) . " | grep 'Association' | awk -F ',' '{print $4}' | tail -n 1"));
    return $ssid;
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
    'mode' => 'demo'
];

if (is_dir($logDir)) {
    @file_put_contents(
        $logFile,
        json_encode($data) . PHP_EOL,
        FILE_APPEND | LOCK_EX
    );
}