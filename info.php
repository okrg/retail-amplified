<?php
phpinfo();
exit();
error_reporting(E_ALL);
//chdir('filespace');
//$output = shell_exec('du -m . | perl -ne \'@l = split();print "@l\n" if $l[0]>=20\' | sort -n');
//$output = shell_exec('du -ck . | sort -n');
//echo "<pre>$output</pre>";
//phpinfo();
$df = disk_free_space("/var/www/vhosts/construction.charlotte-russe.com");
print formatSize($df);

function formatSize( $bytes )
{
        $types = array( 'B', 'KB', 'MB', 'GB', 'TB' );
        for( $i = 0; $bytes >= 1024 && $i < ( count( $types ) -1 ); $bytes /= 1024, $i++ );
                return( round( $bytes, 2 ) . " " . $types[$i] );
}

?>
