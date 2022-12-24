<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
?>
<?$APPLICATION->SetTitle('CRM SYSTEMS');?>
<?php
if(isset($_GET['TABLE'])){
  $table = $_GET['TABLE'];
}
else if(isset($_GET['filter'])){
  $table = $_GET['filter'];
}
else{
  $table = 'TASK';
}
if(isset($_GET['id'])){
  $id = (int)$_GET['id'];
}
else{
  $id = false;
}
$field = ['TASK'=>['NAME','USER','STATUS','DESCRIPTION'],'USER'=>['NAME','JOB'],'STATUS'=>['NAME','COLOR']];
if(isset($field[$table])){
	$APPLICATION->IncludeComponent(
	  "mycrm:crm","",array(
		'LID'=>'s1',
		'FIELDS'=>$field[$table],
		'TABLE'=>$table,
		'ID'=>$id
		)
	  );
}
?>

<div class="ajaxBike"></div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>