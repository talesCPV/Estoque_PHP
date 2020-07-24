
<?php

echo "-> BACKUP";

$output = shell_exec('./backup.sh');
echo "<pre>$output</pre>";
?>
