<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

?>
<?$APPLICATION->SetTitle('CRM SYSTEMS');?>
<?php
	$APPLICATION->IncludeComponent(
	  "mycrm:crm","",array()
	  );
?>
<div class="ajaxBike"></div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>