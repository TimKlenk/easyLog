<?php
/**
 * Created by PhpStorm.
 * User: Tim Klenk
 * Date: 19.05.2016
 * Time: 09:40
 */

class easyLogView extends oxAdminView
{
    protected $_sThisTemplate = "easyLogView.tpl";
    private $errorLogs = array();
    private $logPath='EXCEPTION_LOG.txt';
    private $logFile;
    private $errors;

    public function render()
    {
        return $this->_sThisTemplate;
    }

    function __construct()
    {
        parent::__construct();
        $this->logFile = file_get_contents(oxRegistry::getConfig()->getLogsDir().$this->logPath);
        $this->splitErrorLog();
        $this->setErrorLog();
    }


    public function getAffectedRowOfFile($file)
    {
        $row = str_replace('Zeile:',' ', $file);
       return trim($row);
    }

    public function getDirContents($dir, &$results = array()){
        $files = scandir($dir);

        foreach($files as $key => $value){
            $path = realpath($dir.DIRECTORY_SEPARATOR.$value);
            if(!is_dir($path)) {
                $results[] = $path;
            } else if($value != "." && $value != "..") {
                $this->getDirContents($path, $results);
                $results[] = $path;
            }
        }
        return $results;
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
                ->setErrorMessage($this->getErrorMessage($error))
                ->setSolutionHint($this->getSolutionHint($error))
            ;
            array_push($this->errorLogs,$errorLog);
        }
    }

    public function getSolutionHint($errors)
    {
        return ErrorType::getSolutionHintByErrorType(trim(explode('-',$errors)[0]));
    }

    /**
     * @return array
     */
    public function getErrorLogs()
    {
        return array_reverse(array_filter($this->errorLogs));
    }

    public function getErrorMessage($errors)
    {
        $message = explode('{main}',$errors);
        return end($message);
    }

    public function getAffectedClasses($errors)
    {
        $classes= array();
        $affectedClasses = $this->get_string_between($errors,'#1' , '{main}');
        $affectedClasses = explode('#',$affectedClasses);
        $dir = oxRegistry::getConfig()->getConfigParam('sShopDir');
        $dir = str_replace('/', '\\', $dir);
        foreach($affectedClasses as $classPath)
        {
            if(!preg_match("/tpl.php/",$classPath)) {
                array_push($classes, str_replace('(', ' <b>Zeile:</b> ', $this->get_string_between($classPath, $dir, ')')));
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
        $dir = $_SERVER['DOCUMENT_ROOT'];
        $dir = str_replace('/', '\\', $dir);
        foreach($affectedTemplates as $templatePath)
        {
            if(preg_match("/tpl.php/",$templatePath)) {
                $tpl= explode('%',$this->get_string_between($templatePath, $dir, ')'));
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