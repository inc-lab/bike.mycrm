<?
use \Bitrix\Main\Localization\Loc;
Loc::loadLanguageFile(__FILE__);
?>
<div class="popap-create modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Добавить запись</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form class="formBike">
			<?foreach($arResult as $item){?>
				<?echo $item;?>
			<?}?>
			<input type="hidden" name="TABLE" value="<?=$arParams['TABLE'];?>">
			<input type="hidden" name="component" value="add">
		</form>
      </div>
      <div class="modal-footer">
		<input type="button" class="addBike btn btn-primary" value="Добавить">
      </div>
    </div>
  </div>
</div>