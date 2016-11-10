<?php
/**
 * Created by PhpStorm.
 * User: Tim Klenk
 * Date: 09.06.2016
 * Time: 10:36
 */
$json_response = array();
if(isset($_POST['time']))
{
    array_push($json_response,getLastModifiedFile($_POST['time']));
}
else{
    array_push($json_response, 'falsche');
}

if(isset($_POST['code'])){
    showFile($_POST['code']);
    exit;
}


function showFile($pathToFile)
{
    $fileRow = implode(getNumbers($pathToFile));
    $file = (dirname(dirname(dirname(dirname(__FILE__)))))."/".$pathToFile;
    $file = str_replace('/', '\\', $file);
    $file = new SplFileObject(strstr($file,' ',true));
    foreach ($file as $line) {
        $row = $file->key();
        $row = $row +1;
        if($row == $fileRow) {
            echo "<i class='bg-primary'>".$row . "." . $line . "<br></i>";
        }else{
            echo $row . "." . $line . "<br>";
        }
    }
}

function getNumbers($str) {
    $result = array();

    // Check each character.
    for($i = 0, $len = strlen($str); $i < $len; $i++) {
        if(is_numeric($str[$i])) {
            $result[] = $str[$i];
        }
    }

    return $result;
}

function getLastModifiedFile($time)
{
    $dir = (dirname(dirname(dirname(__FILE__))));
    $date = array();
    $errorDate = new DateTime($time);
    $errorDate = $errorDate->format('Y-m-d');
    $date = array();
    $rootDir = $_SERVER['DOCUMENT_ROOT'];
    $rootDir =  str_replace('/', '\\', $rootDir);
    $dirContent = getDirContents($dir);
    foreach($dirContent as $file)
    {
        if(is_file($file)){
            $tmpDate = date ("Y-m-d H:i:s",filemtime($file));
            if(strtotime(date('Y-m-d',filemtime($file))) == strtotime($errorDate))
            {
                array_push($date,"<strong>Am </strong><i class=\"bg-danger modifiedFile\">".$tmpDate."</i><br><strong>wurde die Datei </strong><i class=\"bg-danger modifiedFile\">".str_replace($rootDir,"",$file)."</i> <strong>geändert.</strong><br>");
            }
        }
    }
    return $date;
}

function getDirContents($dir, &$results = array()){
    $files = scandir($dir);
    foreach($files as $key => $value){
        $path = realpath($dir.DIRECTORY_SEPARATOR.$value);
        if(!is_dir($path)) {
            $results[] = $path;
        } else if($value != "." && $value != "..") {
            getDirContents($path, $results);
            $results[] = $path;
        }
    }
    return $results;
}

foreach ($json_response as $file) {
    echo implode($file);
}