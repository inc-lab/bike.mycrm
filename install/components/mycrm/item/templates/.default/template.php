<div class="list-get">
	<?foreach($arResult['res'] as $item){?>
	<div class="listUp ">
		<form action="" class="mt-3 mb-3 pt-2 container border">
			<div class="pb-3">
				<?foreach($item as $key=>$field){?>
					<?=$field;?>
				<?}?>
			</div>
		</form>
      <input type="hidden" class="table" value="<?=$arParams['TABLE'];?>">
    <div class="up-but col-md-6 col-sm-6">
      <input type="button" class="btn btn-warning delete" value="Удалить">
      <input type="button" class="btn btn-success update" value="Обновить">
    </div>
		<div class="ajaxStatus"></div>
	</div>
	<?}?>
</div>


