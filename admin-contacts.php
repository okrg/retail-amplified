<?php //admin-contacts.php
if ($usergroup != 0) {
  exit('You do not have sufficient privledges to view this page');
} else {
  $query = "SELECT * FROM contacts";
  $result = mysqli_query($dbcnx, $query) or die(mysqli_error($dbcnx));  
}
?>

<h1>Contacts</h1>
<p>This admin screen will contain a filtered/searchable table of contacts with links to edit them.</p>