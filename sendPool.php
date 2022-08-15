<?php
declare(strict_types=1);

require_once './config.php';
require_once './functions.php';

const SENDER = '/usr/bin/php ' . __DIR__ . '/sender.php ';


$dbh = getConnection();

$sql = <<<SQL
select u.email from users u
left join emails e on u.email = e.email         
where u.validts < NOW() + interval '3 days'
and (e.valid = 1 or e.confirmed = 1)
SQL;

$emails = $dbh->query($sql)->fetchAll();

array_map( 'unlink', array_filter((array) glob(SEND_PID_PATH . "*")));

while (count($emails)) {
    if (getSendPids() < $params['send_threads']) {
        $email = array_pop($emails);
        echo getSendPids() . " RUN " . SENDER . $email['email'] . "\n";
        system(SENDER . $email['email'] . ' > /dev/null &');
        sleep(1);
    } else {
        sleep(3);
    }
}