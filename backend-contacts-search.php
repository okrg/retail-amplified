<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
include("include/access.php");

if(isset($_REQUEST["term"])){
    $sql = "SELECT * FROM contacts WHERE fname LIKE ? OR lname LIKE ? OR company LIKE ?";
    if($stmt = mysqli_prepare($dbcnx, $sql)){
        mysqli_stmt_bind_param($stmt, "sss", $param_term, $param_term, $param_term);
        $param_term = $_REQUEST["term"] . '%';        
        if(mysqli_stmt_execute($stmt)){

            $result = mysqli_stmt_get_result($stmt);

            // Check number of rows in the result set
            if(mysqli_num_rows($result) > 0){
                // Fetch result rows as an associative array
                while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                  echo "<div ";
                  echo "data-contact-id=\"{$row['id']}\" ";
                  echo "data-contact-fname=\"{$row['fname']}\"  ";
                  echo "data-contact-lname=\"{$row['lname']}\"  ";
                  echo "data-contact-company=\"{$row['company']}\"  ";
                  echo "data-contact-email=\"{$row['email']}\"  ";
                  echo "data-contact-phone=\"{$row['phone']}\">";
                  echo "{$row['fname']} {$row['lname']} - {$row['company']}";
                  echo "</div>";
                }
            } else{
                echo "<p>No matches found</p>";
            }
        } else{
            echo "ERROR: Could not able to execute $sql. " . mysqli_error($dbcnx);
        }
    }

    // Close statement
    mysqli_stmt_close($stmt);
}

// close connection
mysqli_close($dbcnx);