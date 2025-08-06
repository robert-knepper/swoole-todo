<?php
$projectDir = __DIR__;
$artisanPath = $projectDir . '/artisan';

$scriptContent = "#!/usr/bin/env bash\nphp \"$artisanPath\" \"\$@\"\n";

$targetPath = '/usr/local/bin/todo';

file_put_contents($targetPath, $scriptContent);

if (!chmod($targetPath, 0755)) {
    echo "chmod error $targetPath\n";
    exit(1);
}