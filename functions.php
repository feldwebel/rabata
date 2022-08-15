<?php
declare(strict_types=1);

require_once './config.php';

const CHECK_PID_PATH = __DIR__ . '/pids/check/';
const SEND_PID_PATH = __DIR__ . '/pids/send/';

const INFO = 'info';
const WARNING = 'warning';
const ERROR = 'error';

function getCheckPids(): int
{
    return count((array) glob(CHECK_PID_PATH . "*"));
}

function setCheckPid(string $email): bool
{
    return touch( CHECK_PID_PATH. $email);
}

function clearCheckPid(string $email): bool
{
    if (file_exists(CHECK_PID_PATH . $email)) {
        return unlink(CHECK_PID_PATH . $email);
    }
    return false;
}

function getSendPids(): int
{
    return count((array) glob(SEND_PID_PATH . "*"));
}

function setSendPid(string $email): bool
{
    return touch( SEND_PID_PATH. $email);
}

function clearSendPid(string $email): bool
{
    if (file_exists(SEND_PID_PATH . $email)) {
        return unlink(SEND_PID_PATH . $email);
    }
    return false;
}

function logIt(string $text, string $level = INFO): void
{
    file_put_contents('log.txt', time() . ": $level: $text\n", FILE_APPEND);
}

function getConnection(): PDO
{
    static $pdo;
    global $params;
    $dsn = 'pgsql:host=localhost;dbname='.$params['db_name'];

    if (!$pdo) {
        $pdo = new PDO($dsn, $params['db_user'], $params['db_pass'], [PDO::ATTR_PERSISTENT => true]);
    }

    return $pdo;
}


