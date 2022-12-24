<form class="filters mt-3">
 <div class="form-row align-items-center">
	<?foreach($arResult['filter'] as $key=>$field){?>
		<?=$field;?>
	<?}?>
	<input type="hidden" name="filter" value="<?=$arParams['TABLE'];?>">
    <div class="col-auto">
      <button type="submit" class="btn btn-primary mb-2">Найти</button>
    </div>
  </div>
</form>
<div class="list-get">
	<?foreach($arResult['res'] as $item){?>
	<div class="listUp ">
		<form action="" class="mt-3 mb-3 pt-2 container border">
			<div class="row">
				<?foreach($item as $key=>$field){?>
					<?=$field;?>
				<?}?>
			</div>
		</form>

	</div>
	<?}?>
</div>


