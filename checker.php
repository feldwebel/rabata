<?php
declare(strict_types=1);

require_once './functions.php';

if (!$param = $argv[1] ?? null) {
    logIt('Nothing to do', WARNING);
    exit(1);
}

$pdo = getConnection();
$sql = "select email from emails where email = :email";
$query = $pdo->prepare($sql);
$query->execute(['email' => $param]);
$email = $query->fetch(PDO::FETCH_ASSOC);

if (!$email){
    logIt("Email $param not found", WARNING);
    exit(1);
}

try {
    logIt("Email $param is about to check");
    setCheckPid($param);
    if (check_email($param)) {
        $success = 'update emails set checked = 1, valid = 1 where email = :email';
        $pdo->prepare($success)->execute(['email' => $param]);
        logIt("Email $param was successfully verified");
    } else {
        $fail = 'update emails set checked = 1, valid = 0 where email = :email';
        $pdo->prepare($fail)->execute(['email' => $param]);
        logIt("Email $param was verified and found invalid");
    }
} catch (Exception $ex) {
    $error = 'update emails set checked = 1, valid = 0 where email = :email';
    $pdo->prepare($error)->execute(['email' => $param]);
    logIt("An error happens while checking email $param");
} finally {
    clearCheckPid($param);
}

/**
 * @throws Exception
 */
function check_email(string $email): int
{
    sleep(random_int(1, 10));
    return match (random_int(1, 5)) {
        1 => 0,
        2 => throw new Exception("External error happens"),
        default => 1
    };
}