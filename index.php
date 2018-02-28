<?php 
include('class.database.php');
$Database=new Database;
$Resource=$Database->query("Select * from sample_table");
$Result=$Database->fetch($Resource);
print_r($Result);
?>