<?php
declare(strict_types=1);

require_once './config.php';
require_once './functions.php';

const CHECKER = '/usr/bin/php ' . __DIR__ . '/checker.php ';

$dbh = getConnection();

$sql = 'select * from emails where confirmed = 0 and checked = 0';

$emails = $dbh->query($sql)->fetchAll();

array_map( 'unlink', array_filter((array) glob(CHECK_PID_PATH . "*")));

while (count($emails)) {
    if (getCheckPids() < $params['check_threads']) {
        $email = array_pop($emails);
        echo getCheckPids() . " RUN " . CHECKER . $email['email'] . "\n";
        system(CHECKER . $email['email'] . ' > /dev/null &');
        sleep(1);
    } else {
        sleep(3);
    }
}
