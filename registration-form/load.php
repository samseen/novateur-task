<?php
include("connection.php");
mysqli_select_db($con,"registrationdb");
$result=mysqli_query($con, "select * from registration_table order by ID DESC LIMIT 1");

echo "<table border='1'>
<tr>
<th align=center>ID</th>
<th align=center>Name</th>
<th align=center>Address</th>
<th align=center>DOB</th>
<th align=center>Position</th>
</tr>";

while($row = mysqli_fetch_row($result))
{   
  echo "<tr><th>$row[0]</th><th>$row[1]</th><th>$row[2]</th><th>$row[3]</th><th>$row[4]</th></tr>";
}
echo "</table>";
?>