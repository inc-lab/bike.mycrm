<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

if(isset($_REQUEST['component'])){
	$component = $_REQUEST['component'];
	unset($_REQUEST['component']);
	$APPLICATION->IncludeComponent(
		"mycrm:".$component,"",$_REQUEST
	);
}

