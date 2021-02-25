<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>
</head>

<body>
<?php

$arr = get_defined_vars();
print_r($arr);
?>
<form method="post" action="<?=$_SERVER['PHP_SELF']?>">
<select>
<option value="1">ha</option>
<option value="2">ha2</option>
</select>
<input type="submit" />
</form>
</body>
</html>
