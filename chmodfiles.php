<?php
$output = shell_exec('chmod -R 0755 /var/www/vhosts/construction.charlotte-russe.com/httpdocs/filespace ');
echo "<pre>$output</pre>";

$output = shell_exec('ls -lart /var/www/vhosts/construction.charlotte-russe.com/httpdocs/filespace/652');
echo "<pre>$output</pre>";

?>