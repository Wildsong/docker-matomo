<?php
//
// Shows request headers for testing proxy
//
$real_ip = $_SERVER['real_ip'];
echo "real_ip = $real_ip<br><p>";
foreach (getallheaders() as $name => $value) {
    $cur_hd = "$name: $value <br />\n";
    echo $cur_hd;
}
echo "</p>";
?>