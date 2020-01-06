<?php
$mysqli = new mysqli("61b1ed95616f65903ff311cc9decad51aa4cac3d.rackspaceclouddb.com","vastdb","VastPlus#01","ShowSeeker");
$text = $mysqli->real_escape_string($_GET['term']);

$query = "SELECT firstname, lastname, corporations.name FROM users INNER JOIN corporations on users.corporationid = corporations.id WHERE users.firstname LIKE '%$text%' ORDER BY users.firstname, users.lastname ASC";
$result = $mysqli->query($query);
$json = '[';
$first = true;
while($row = $result->fetch_assoc())
{
    if (!$first) { $json .=  ','; } else { $first = false; }
    $json .= '{"value":"'.$row['firstname'].' '.$row['lastname'].' - '.$row['name'].'"}';
}
$json .= ']';
echo $json;
?>