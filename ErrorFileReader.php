<?php

/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 16.05.2016
 * Time: 13:10
 */
class ErrorFileReader
{
   private $errorLogs = array();
   private $logFile;
    private $errors;

    function __construct($logFileName)
    {
        $this->logFile = file_get_contents($logFileName);
        $this->splitErrorLog();
        $this->setErrorLog();
    }
    
    public function splitErrorLog()
    {
        $this->errors = explode("---------------------------------------------", $this->logFile );
    }

    public function setErrorLog()
    {
        foreach ($this->errors as $error)
        {
        	$errorLog = new ErrorLog();
			$errorLog->setTime($this->get_string_between($error, '(time: ', '):' ))
					 ->setAffectedClasses($this->getAffectedClasses($error))
                     ->setAffectedFunctions($this->getAffectedFunctions($error))
                    ->setAffectedTemplates($this->getAffectedTemplates($error))
			;
			array_push($this->errorLogs,$errorLog);
        }
    }

    public function getErrorLogs()
    {
    	return $this->errorLogs;
    }
    
    public function getAffectedClasses($errors)
    {
        $classes= array();
    	$affectedClasses = $this->get_string_between($errors,'#1' , '{main}');
    	$affectedClasses = explode('#',$affectedClasses);
        foreach($affectedClasses as $classPath)
        {
            if(!preg_match("/tpl.php/",$classPath)) {
                array_push($classes, str_replace('(', ' <b>Zeile:</b> ', $this->get_string_between($classPath, dirname(__DIR__), ')')));
            }
        }
		return $classes;
    }

    public function getAffectedFunctions($errors)
    {
        $functions= array();
        $affectedFunctions = $this->get_string_between($errors,'#1' , '{main}');
        $affectedFunctions = explode('#',$affectedFunctions);
        foreach($affectedFunctions as $functionPath)
        {

                array_push($functions, $this->get_string_between($functionPath, ': ', '('));

        }
        return $functions;
    }

    public function getAffectedTemplates($errors)
    {
        $templates= array();
        $affectedTemplates = $this->get_string_between($errors,'#1' , '{main}');
        $affectedTemplates = explode('#',$affectedTemplates);
        foreach($affectedTemplates as $templatePath)
        {
            if(preg_match("/tpl.php/",$templatePath)) {
                $tpl= explode('%',$this->get_string_between($templatePath, dirname(__DIR__), ')'));
                $tpl = trim(end($tpl));
                $tpl = str_replace('.php','',$tpl);
                $tpl = preg_replace('/[0-9]+/', '',$tpl);
                $tpl = str_replace('(','',$tpl);
                array_push($templates, $tpl);
            }
        }
        return $templates;
    }


    /**
     * @param $string
     * @param $start
     * @param $end
     * @return string
     */
    private function get_string_between($string, $start, $end){
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }
}