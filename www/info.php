<?php
$con = mysqli_connect("10.0.1.10", "web", "web_pass");
if (!$con)
{
  die('Could not connect: ' . mysqli_error());
}

$result = mysqli_query($con,"SHOW FULL PROCESSLIST");

echo "<big><big><b><font color='blue' face='Courier New'>show full processlist</b></big></big>";

echo "<br/>";
echo "<br/>";


printf("<table border='1' >\n");
printf("<tr><th>Id</th><th>Host</th><th>db</th> <th>Command</th><th>Time</th></tr>\n");
while ($row=mysqli_fetch_array($result)) {
    printf("<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td> %s</td></tr>\n", $row["Id"], $row["Host"], $row["db"],$row["Command"], $row["Time"]);
}
printf("</table>\n");

mysqli_close($con);


echo "<br/>";
echo "<br/>";


echo "<big><big><b><font color='blue'>traceroute  wix.com</big></big>";
echo "<br/>";
echo "<br/>";

//$connection = ssh2_connect('10.0.1.10', 22, array('hostkey'=>'ssh-rsa'));
$connection = ssh2_connect('10.0.1.10', 22);

if (ssh2_auth_pubkey_file($connection, 'ubuntu',
                          '/var/www/.ssh/web_key.openssh',
                          '/var/www/.ssh/web_key', '')) {
    echo "\n";
    echo "<table border='1' >";
//    $command="sudo traceroute -I wix.com |  grep -v 'traceroute to'| awk '{printf \"%-3s %-50s %-20s %-10s %-10s %-10s\\n\", $1,$2,$3,$4,$6,$8}'";
    $command="sudo traceroute -I wix.com |  grep -v 'traceroute to'| awk '{printf \"<tr><td>%-3s</td><td>%-50s</td><td>%-10s</td><td>%-10s</td><td>%-10s</td></tr>\", $1,$2,$4,$6,$8}'";
    $stream =ssh2_exec($connection, $command);
    $errorStream = ssh2_fetch_stream($stream, SSH2_STREAM_STDERR);
    stream_set_blocking($errorStream, true);
    stream_set_blocking($stream, true);

    echo stream_get_contents($stream);
    //echo nl2br(stream_get_contents($errorStream));
    echo "</table>";

    // Close the streams
    fclose($errorStream);
    fclose($stream);

} else {
  die('Public Key Authentication Failed');
}



?>
