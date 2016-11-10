<?php

/**
 * Created by PhpStorm.
 * User: Tim Klenk
 * Date: 19.05.2016
 * Time: 11:24
 */
class ErrorAnalyzer
{
    private $lastEdit;
    private $solutionHint;

    public function __construct($file)
    {
        $this->setLastEditDate($file);
    }

    public function setLastEditDate($file)
    {
        $filePath = $_SERVER['DOCUMENT_ROOT'].$file;
        $this->lastEdit=filemtime($filePath);
    }

    public function getLastEditDate()
    {
        return $this->lastEdit;
    }
}