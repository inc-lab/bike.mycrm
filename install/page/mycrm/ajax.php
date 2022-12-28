<?
use Bitrix\Main\Application;
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
$request = Application::getInstance()->getContext()->getRequest()->toArray();
if(isset($request['component'])){
	$component = $request['component'];
	unset($request['component']);
	$APPLICATION->IncludeComponent(
		"mycrm:".$component,"",$request
	);
}