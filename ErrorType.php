<?php

/**
 * Created by PhpStorm.
 * User: Tim Klenk
 * Date: 20.05.2016
 * Time: 12:21
 */
class ErrorType
{
    public static function getSolutionHintByErrorType($errorType)
    {
        switch ($errorType){
            case 'oxFileException':
                return '<br>Eine Datei enthält Fehler (evtl. nach Änderungen oder Modul-Aktivierung)';
                break;
            case 'oxSystemComponentException':
                return '<br>- Klasse aus einem neu Aktivierten Modul kann nicht gefunden werden<br>- Nach einem Shop-Update existiert eine Klasse nicht mehr, wird aber von einem Modul benötigt<br>-Templatedatei kann nicht eingebunden werden.';
                break;
            case 'oxAdoDbException':
                return '<br>-Datenbank nicht erreichbar<br>-Falsche Datenbank Zugangsdaten in der config.inc.php(Shopumzug?)<br>-Fehlerhafte Abfragen in einem Modul';
                break;
            case 'EXCEPTION_CONNECTION_NODB':
                return '<br>-Datenbank nicht erreichbar<br>-Falsche Datenbank Zugangsdaten in der config.inc.php(Shopumzug?)';
                break;
            case 'oxConnectionException':
                return '<br>Ein externer Server kann nicht erreicht werden. Dies betrifft unteranderem den Datenbankserver oder den UST-ID check.';
            default:
                return '<br>Keine Informationen';
        }
    }
}