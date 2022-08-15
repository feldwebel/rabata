<?php
declare(strict_types=1);

require_once './functions.php';

if (!$param = $argv[1] ?? null) {
    logIt('Nothing to do', WARNING);
    exit(1);
}

$pdo = getConnection();
$sql = "select email from emails where email = :email and (valid = 1 or confirmed = 1)";
$query = $pdo->prepare($sql);
$query->execute(['email' => $param]);
$email = $query->fetch(PDO::FETCH_ASSOC);

if (!$email){
    logIt("Email $param not found", WARNING);
    exit(1);
}

try {
    logIt("Email $param is about to send");
    setSendPid($param);
    if (send_email($param)) {
        $success = "update users set validts = validts + interval '1 year' where email = :email";
        $pdo->prepare($success)->execute(['email' => $param]);
        logIt("Email $param was successfully sent");
    } else {
        $fail = "update users set validts = validts + interval '1 day' where email = :email";
        $pdo->prepare($fail)->execute(['email' => $param]);
        logIt("Email $param was tried to send and error happens. Next day");
    }
} catch (Exception $ex) {
    $error = "update users set validts = validts + interval '1 day' where email = :email";
    $pdo->prepare($error)->execute(['email' => $param]);
    logIt("An error happens while sending email $param");
} finally {
    clearSendPid($param);
}

/**
 * @throws Exception
 */
function send_email(string $email): int
{
    sleep(random_int(1, 10));
    return match (random_int(1, 5)) {
        1 => 0,
        2 => throw new Exception("External error happens"),
        default => 1
    };
}