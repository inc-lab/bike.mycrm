<?
use Bitrix\Main;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Type;
use Bike\Mycrm;
use Bitrix\Sale\Internals;
use Bitrix\Main\Loader;

class getClass extends CBitrixComponent{
	public $tables;
    protected function checkModules(){
		$this->tables = ['TASK'=>'\Bike\Mycrm\TaskcrmTable','USER'=>'\Bike\Mycrm\UsercrmTable','STATUS'=>'\Bike\Mycrm\StatuscrmTable'];
        if(!Loader::includeModule('bike.mycrm')){
            throw new Main\LoaderException('Модуль bike.mycrm не установлен');
        }
    }
    public function onPrepareComponentParams($arParams = []): array
    {
		foreach($arParams['FIELDS'] as $key=>$val){
			$arParams['FIELDS'][$key]=preg_replace('/[^#0-9a-zA-Zа-яёА-ЯЁ\-\.@_ ]+/ui','',$val);
		}
		return $arParams;
    }

    public function select($arParams=[]){
		if(isset($this->tables[$this->arParams['TABLE']])){
			$table = $this->tables[$this->arParams['TABLE']];
			if(isset($arParams['ID']) && $arParams['ID']>0){
				$result = $table::getList(array('filter'=>['ID'=>(int)$arParams['ID']],'limit'=>1))->fetchAll();
			}
			else{
				$result = $table::getList(array('limit'=>1))->fetchAll();
			}
			return $result;
		}
	}
	private function tpl($name,$value){
		$path = __DIR__.'/files/'.$name.'.php';
		$def = __DIR__.'/files/all.php';
		if(file_exists($path)){
			$file = file_get_contents($path);
		}
		else{
			$file = file_get_contents($def);
		}
		$file = str_replace("[[value]]",$value,$file);
		$file = str_replace("[[key]]",$name,$file);
		if(isset($this->arParams['TABLE'])){
			$file = str_replace("[[name]]",GetMessage($this->arParams['TABLE'].'_'.$name),$file);
		}
		if(isset($this->arParams['TABLE']) && $this->arParams['TABLE']=='TASK'){
			if($name=='USER'){
				$arr_user = \Bike\Mycrm\UsercrmTable::getList()->fetchAll();
				$userfield='<ul class="list-group">';
				$value = explode(',',$value);
				foreach($arr_user as $user){
					if(in_array((int)$user['ID'],$value)){ 
						$selected='checked';
					}
					else{
						$selected='';
					}
					$userfield.='<li class="list-group-item border-0"><input class="form-check-input me-1" type="checkbox" name="'.$name.'[]" value="'.$user['ID'].'" '.$selected.'>'.$user['NAME'].'</li>';
				}
				$userfield.='</ul>';
				$file = str_replace("[[user]]",$userfield,$file);
			}
			if($name=='STATUS'){
				$arr_status = \Bike\Mycrm\StatuscrmTable::getList()->fetchAll();
				$statusfield='<select class="form-select" name="'.$name.'" aria-label="Default select example">';
				foreach($arr_status as $status){
					if($status['ID']==$value){
						$selected='selected';
					}
					else{
						$selected='';
					}
					$statusfield.='<option value="'.$status['ID'].'" '.$selected.'>'.$status['NAME'].'</option>';
				}
				$statusfield.='</select>';
				$file = str_replace("[[status]]",$statusfield,$file);
			}
		}
		$file = str_replace("[[table]]",$this->arParams['TABLE'],$file);
		return $file;
	}


    public function executeComponent(){
        $this->includeComponentLang('lang.php');
        $this->checkModules();
        $result = $this->select($this->arParams);
		foreach($result as $key0=>$item){
			foreach($item as $key=>$field){ 
				$result[$key0][$key] = $this->tpl($key,$field);
			}
		}
	    $this->arResult = ['res'=>$result];
        $this->IncludeComponentTemplate(); 
    }
}