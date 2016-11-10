<?php

/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 16.05.2016
 * Time: 13:05
 */
class ErrorLog
{
    private $time;
    private $affectedClasses;
    private $affectedFunctions;
    private $affectedTemplates;
    private $affectedRows;
    private $errorMessage;
    private $solutionHint;
    private $affectedModul;
    private $lastEditFile;

    /**
     * @return mixed
     */
    public function getLastEditFile()
    {
        return $this->lastEditFile;
    }

    /**
     * @param mixed $lastEditFile
     */
    public function setLastEditFile($lastEditFile)
    {
        $this->lastEditFile = $lastEditFile;
        return $this;
    }
    /**
     * @return mixed
     */
    public function getAffectedModul()
    {
        return $this->affectedModul;
    }


    /**
     * @param mixed $affectedModul
     */
    public function setAffectedModul($affectedModul)
    {
        $this->affectedModul = $affectedModul;
        return $this;
    }
    /**
     * @return mixed
     */
    public function getSolutionHint()
    {
        return $this->solutionHint;
    }

    /**
     * @param mixed $solutionHint
     */
    public function setSolutionHint($solutionHint)
    {
        $this->solutionHint = $solutionHint;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * @param mixed $errorMessage
     */
    public function setErrorMessage($errorMessage)
    {
        $this->errorMessage = $errorMessage;
        return $this;
    }
    /**
     * @return mixed
     */
    public function getAffectedRows()
    {
        return $this->affectedRows;
    }

    /**
     * @param mixed $affectedRows
     */
    public function setAffectedRows($affectedRows)
    {
        $this->affectedRows = $affectedRows;
    }

    /**
     * @return mixed
     */
    public function getAffectedClasses()
    {
        return array_filter($this->affectedClasses);
    }

    /**
     * @param mixed $affectedClasses
     */
    public function setAffectedClasses($affectedClasses)
    {
        $this->affectedClasses = $affectedClasses;
        return $this;
      
    }

    public function getLastEditDate($file)
    {
        $filePath = oxRegistry::getConfig()->getConfigParam('sShopDir').strstr($file,' ', true);
        if(file_exists($filePath))
        {
            return date ("Y-m-d H:i:s.",filemtime($filePath));
        }
    }
    /**
     * @return mixed
     */
    public function getAffectedFunctions()
    {
        return array_filter($this->affectedFunctions);
    }

    /**
     * @param mixed $affectedFunctions
     */
    public function setAffectedFunctions($affectedFunctions)
    {
        $this->affectedFunctions = $affectedFunctions;
        return $this;
        
    }

    /**
     * @return mixed
     */
    public function getAffectedTemplates()
    {
        return $this->affectedTemplates;
    }

    /**
     * @param mixed $affectedTemplates
     */
    public function setAffectedTemplates($affectedTemplates)
    {
        $this->affectedTemplates = $affectedTemplates;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @param mixed $time
     */
    public function setTime($time)
    {
        $this->time = $time;
        return $this;
        
    }

}