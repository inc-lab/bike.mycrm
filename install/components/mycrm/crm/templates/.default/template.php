<?
use \Bitrix\Main\Localization\Loc;
Loc::loadLanguageFile(__FILE__);
?>
<div class="container">
  <div class="row  bg-light mb-5">
    <div class="col-sm pt-3 pb-3 text-center <?=$arResult['COLOR']['TASK'];?>">
		<a href="/mycrm/?TABLE=TASK" class="link-info text-dark">Задачи</a>
    </div>
    <div class="col-sm pt-3 pb-3 text-center <?=$arResult['COLOR']['USER'];?>">
      <a href="/mycrm/?TABLE=USER" class="link-info text-dark ">Исполнители</a>
    </div>
    <div class="col-sm pt-3 pb-3 text-center <?=$arResult['COLOR']['STATUS'];?>">
      <a href="/mycrm/?TABLE=STATUS" class="link-info text-dark">Статусы</a>
    </div>
  </div>
</div>
<input type="button" class="popapAdd btn btn-primary" data-toggle="modal" data-target="#exampleModal" value="<?=Loc::getMessage('POPAP_ADD_'.$arResult['TABLE']);?>">

<?
if($arResult['ID']){
	$ct = 'item';
}
else{
	$ct = 'get';
}
$APPLICATION->IncludeComponent(
	"mycrm:".$ct,"",array(
		'FIELDS'=>$arResult['FIELDS'],
		'FILTER'=>$arResult['FILTER'],
		'TABLE'=>$arResult['TABLE'],
		'ID'=>$arResult['ID']
	)
);
?>
<?
$APPLICATION->IncludeComponent(
	"mycrm:popap","",array(
		'TABLE'=>$arResult['TABLE']
	)
);
?>

