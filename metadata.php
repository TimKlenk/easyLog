<?php
/**
 * Created by PhpStorm.
 * User: Tim Klenk
 * Date: 19.05.2016
 * Time: 09:37
 */
$sMetadataVersion = '1.0';

/**
 * Module information
 */
$aModule = array(
    'id'          => 'easyLog',
    'title'       => 'easyLog',
    'description' => '',
    'version'     => '1.0',
    'author'      => 'Tim Klenk',
    'templates'   => array(
        'easyLogView.tpl' => 'oe/easyLog/admin/views/easyLogView.tpl'
    ),
    'files'         => array(
        'easyLogView' =>'oe/easyLog/admin/easyLogView.php',
        'ErrorFileReader'    => 'oe/easyLog/ErrorFileReader.php',
        'ErrorLog'     => 'oe/easyLog/ErrorLog.php',
        'ErrorAnalyzer'     => 'oe/easyLog/ErrorAnalyzer.php',
        'ErrorType'     => 'oe/easyLog/ErrorType.php',
        'ajax'     => 'oe/easyLog/ajax.php',
    ),
);