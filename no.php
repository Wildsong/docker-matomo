<?php
//
// Shows request headers for testing proxy
//
$time = $_SERVER['REQUEST_TIME'];
echo "$time.<br>";
foreach (getallheaders() as $name => $value) {
    $cur_hd = "$name: $value <br />\n";
    echo $cur_hd;
}
?>