<?php

if(empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest'
|| !isset($_POST['thread']) || !isset($_POST['content'])) {
    echo 'Unknown error. Please try again.';
    exit;
}

$content = $_POST['content'];
$thread = $_POST['thread'];

$dataFile = 'data.' .$thread. '.js';
if(!file_exists($dataFile)) {
    $newFile = fopen($dataFile, 'x');
    fwrite($newFile, 'var chat=[];');
    fclose($newFile);
}

function injectData($file, $data, $position) {
    $fpFile = fopen($file, "rw+");
    $fpTemp = fopen('php://temp', "rw+");
    stream_copy_to_stream($fpFile, $fpTemp, -1, $position);
    fseek($fpFile, $position);
    fwrite($fpFile, $data);
    rewind($fpTemp);
    stream_copy_to_stream($fpTemp, $fpFile);

    fclose($fpFile);
    fclose($fpTemp);
}

injectData($dataFile, $content, 10);

echo 'Database successfully updated';

?>