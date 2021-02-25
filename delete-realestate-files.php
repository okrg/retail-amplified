
<?php

$pid = $_GET['pid'];

if (isset($pid) && $pid != "")
{
	$directory_path = "./realestate_files/" . $pid;
	if (file_exists($directory_path))
	{
		$cmd = "rm -r {$directory_path}";
		system($cmd);
	}
}

?>