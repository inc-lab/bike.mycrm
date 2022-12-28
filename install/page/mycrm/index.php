<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

?>
<?$APPLICATION->SetTitle('CRM SYSTEMS');?>
<?php
	$APPLICATION->IncludeComponent(
	  "mycrm:crm","",array(
		'LID'=>'s1',
		'FIELDS'=>$field[$table],
		'TABLE'=>$table,
		'ID'=>$id
		)
	  );
?>
<div class="ajaxBike"></div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>