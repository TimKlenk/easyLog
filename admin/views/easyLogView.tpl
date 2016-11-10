<!DOCTYPE HTML>
<html>
<head>
    <link rel="stylesheet" href="[{$oViewConf->getModuleUrl('easyLog','css/easylog.css')}]">
    <link rel="stylesheet" href="[{$oViewConf->getModuleUrl('easyLog','css/bootstrap.min.css')}]">
    <link rel="stylesheet" href="[{$oViewConf->getModuleUrl('easyLog','font-awesome-4.6.3/css/font-awesome.min.css')}]">
    <script type="text/javascript" src="[{$oViewConf->getModuleUrl('easyLog','js/jquery.min.js')}]"></script>
    <script type="text/javascript" src="[{$oViewConf->getModuleUrl('easyLog','js/bootstrap.min.js')}]"></script>
</head>
<body>
[{assign var="logs" value=$oView->getErrorLogs()}]
[{assign var="counter" value=0}]
[{assign var="counterT" value=0}]
<div class="load"></div>
<table class="table table-hover " style="min-width: 1200px;">
    <thead>
    <tr class="bg-success">
        <td >Datum/Uhrzeit</td>
        <td>Dateien die vor Auftreten des Fehlers aufgerufen werden</td>
        <td>Aufgerufene Funktionen vor Auftreten des Fehlers</td>
        <td>Betroffene Templates</td>
        <td>Lösungsvorschlag</td>
    </tr>
    </thead>
[{foreach from=$logs item=log}]
    <tr>
        <td>[{$log->getTime()}]</td>
        <td>
			[{foreach from=$log->getAffectedClasses() item= affectedClasses}]
            [{$affectedClasses}]
            [{assign var ="file" value=$affectedClasses}]
            <button class="fa fa-eye showFile" aria-hidden="true" data-toggle="modal" data-code="[{$file}]" data-target="#viewFile[{$counterT}]"></button>
            <div class="modal showcode" id="viewFile[{$counterT}]" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Code der betroffenen Klasse</h4>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Die betroffene Zeile ist <i class='bg-primary'>markiert</i>!
                            </div>
                            <div class="panel-body">
                                <div class="modal-body">
                                    <div class="responseCode"></div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
             <span title="Zuletzt geändert am:[{$log->getLastEditDate($affectedClasses)}]"
                              class="resetPrice pull-right"
                              data-placement='bottom'>
                <span class="badge"><i class="fa fa-info" aria-hidden="true"></i></span>
            </span>
                <br>
            [{/foreach}]
        </td>
        <td>
			[{foreach from=$log->getAffectedFunctions() item=affectedFunctions}]
             [{$affectedFunctions}]<br>
            [{/foreach}]
        </td>
        <td>
			[{foreach from=$log->getAffectedTemplates() item=affectedTemplates}]
           [{$affectedTemplates}]
            <span title="Zuletzt geändert am: [{$log->getLastEditDate($affectedTemplates)}]"
                    class="resetPrice pull-right"
                    data-placement='bottom'>
                <span class="badge"><i class="fa fa-info" aria-hidden="true"></i></span>
            </span>
        <br>
            [{/foreach}]
        </td>
        <td>
            <button type="button" class="btn btn-primary" data-toggle="modal"  data-whatever="[{$log->getTime()}]" data-target="#modal_[{$counter}]">
                Hilfe anzeigen
            </button>
            <div class="modal superm" id="modal_[{$counter}]" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-success">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Analyse</h4>
                        </div>
                        <div class="modal-body">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <h4>Mögliche Ursachen</h4>
                                </div>
                                <div class="panel-body">
                                    [{$log->getSolutionHint()}]
                                </div>
                            </div>
                            <div class="panel panel-warning">
                            <div class="panel-heading">
                                <h4>Zuletzt bearbeitete Dateien
                                <small>(nur Änderungen in Modulen werden berücksichtigt)</small></h4>
                            </div>
                                    <div class="panel-body">
                                    <p class="bg-info">Hinweis: Hierbei handelt es sich um die Dateien, die am Tag des Auftreten des Fehlers geändert wurden.</p>
                                        <div class="load"></div>
                                        <div class="response"></div>
                                        </div>
                                </div>
                            <div class="panel panel-danger">
                                <div class="panel-heading">
                                     <h4>Ausgegebene Fehlermeldung</h4>
                                </div>
                                <div class="panel-body">
                                    [{$log->getErrorMessage()}]
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </td>
    </tr>
    [{assign var="counter" value=$counter+1}]
   [{/foreach}]
</table>

<script>
    $(document).ready(function(){
        $('.showcode').on('show.bs.modal', function (event){
            $('.load').css('display','block');
            $(".responseCode").html('');
            var button = $(event.relatedTarget); // Button that triggered the modal
            var recipient = button.data('code') ;// Extract info from data-* attributes
            var sid = "[{ $oViewConf->getSessionId() }]";
            var tkn = "[{ $oViewConf->getSessionChallengeToken() }]";
            $.ajax({
                url: "[{$oViewConf->getModuleUrl('easyLog','ajax.php')}]",
                data:{
                    force_admin_sid : sid,
                    stoken : tkn,
                    code:recipient
                },
                type:"POST",
                dataType :'html',
                success: function (response) {
                    $(".load").css('display','none');
                    $(".responseCode").html(response);
                }
            })

        });

        $('.resetPrice').tooltip();
        $('.superm').on('show.bs.modal', function (event) {
            $('.load').css('display','block');
            $(".response").html('');
            var button = $(event.relatedTarget);
            var recipient = button.data('whatever')
            var sid = "[{ $oViewConf->getSessionId() }]";
            var tkn = "[{ $oViewConf->getSessionChallengeToken() }]";
            $.ajax({
                url: "[{$oViewConf->getModuleUrl('easyLog','ajax.php')}]",
                data:{
                    force_admin_sid : sid,
                    stoken : tkn,
                    time:recipient
                },
                type:"POST",
                dataType :'html',
                success: function (response) {
                    $(".response").html(response);
                    $(".load").css('display','none');
                }
            })
        });

    });

</script>
</body>
</html>