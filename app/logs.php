<?php
$logDir = '/var/log/app';
$logs = glob($logDir . '/*.log');

echo "<h1>Logi aplikacji</h1>";
foreach ($logs as $log) {
    $filename = basename($log);
    $size = filesize($log);
    $modified = date('Y-m-d H:i:s', filemtime($log));

    echo "<h3>$filename</h3>";
    echo "<p>Rozmiar: " . number_format($size) . " bajtów | Ostatnia modyfikacja: $modified</p>";
    echo "<pre style='background: #f5f5f5; padding: 10px; max-height: 300px; overflow: auto;'>";
    echo htmlspecialchars(tail($log, 50));
    echo "</pre><hr>";
}

/**
 * A memory-efficient function to get the last N lines of a file.
 *
 * @param string $filepath The path to the file.
 * @param int $lines The number of lines to return.
 * @param bool $adaptive Use adaptive buffer size.
 * @return string|false The last N lines of the file, or false on failure.
 */
function tail(string $filepath, int $lines = 10, bool $adaptive = true): string|false
{
    $f = @fopen($filepath, "rb");
    if ($f === false) {
        return false;
    }

    if (!$adaptive) {
        $buffer = 4096;
    } else {
        $buffer = ($lines < 2 ? 64 : ($lines < 10 ? 512 : 4096));
    }

    fseek($f, -1, SEEK_END);

    if (fread($f, 1) != "\n") $lines -= 1;

    $output = '';
    while (ftell($f) > 0 && $lines >= 0) {
        $seek = min(ftell($f), $buffer);
        fseek($f, -$seek, SEEK_CUR);
        $chunk = fread($f, $seek);
        $output = $chunk . $output;
        fseek($f, -mb_strlen($chunk, '8bit'), SEEK_CUR);
        $lines -= substr_count($chunk, "\n");
    }

    while ($lines++ < 0) {
        $output = substr($output, strpos($output, "\n") + 1);
    }
    fclose($f);

    return $output;
}
?>
