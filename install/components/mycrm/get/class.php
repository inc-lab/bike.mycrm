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
			$arParams['FIELDS'][$key]=preg_replace('/[^0-9a-zA-Zа-яёА-ЯЁ\-\.@_ ]+/ui','',$val);
		}
		foreach($arParams['FILTER'] as $key=>$val){
			$arParams['FILTER'][$key]=preg_replace('/[^0-9a-zA-Zа-яёА-ЯЁ\-\.@_ ]+/ui','',$val);
		}
		return $arParams;
    }

    public function select($arParams=[]){
		if(isset($this->tables[$this->arParams['TABLE']])){
			$table = $this->tables[$this->arParams['TABLE']];
			if(isset($_GET['page'])){
				$offset = $_GET['page']*10;
			}
			else{
				$offset=0;
			}
			if(isset($arParams['FILTER'])){
				foreach($arParams['FILTER'] as $key=>$item){
					if($item!=''){
						$filter['%'.$key] = $item;
					}
				}
				if(isset($filter)){
					$result = $table::getList(array('filter'=>$filter,'limit'=>10,'offset'=>$offset))->fetchAll();
				}
				else{
					$result = $table::getList(array('limit'=>10,'offset'=>$offset))->fetchAll();
				}
			}
			else{
				$result = $table::getList(array('limit'=>10,'offset'=>$offset))->fetchAll();
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
		$file = str_replace("[[key]]",$name,$file);
		if(isset($this->arParams['TABLE'])){
			$file = str_replace("[[name]]",GetMessage($this->arParams['TABLE'].'_'.$name),$file);
		}
		if($name=='STATUS'){
			$arr_status = \Bike\Mycrm\StatuscrmTable::getList(array('filter'=>['ID'=>$value]))->fetchAll();
			$file = str_replace("[[color]]",$arr_status[0]['COLOR'],$file);
			$file = str_replace("[[value]]",$arr_status[0]['NAME'],$file);
		}
		if($name=='USER'){
			$value=explode(',',$value);
			$arr_user = \Bike\Mycrm\UsercrmTable::getList(array('filter'=>['ID'=>$value]))->fetchAll();
			$users=[]; 
			foreach($arr_user as $user){ 
				$users[]=$user['NAME'];
			}
			$file = str_replace("[[value]]",implode(',',$users),$file);
		}
		else{
			$file = str_replace("[[value]]",$value,$file);
		}
		$file = str_replace("[[table]]",$this->arParams['TABLE'],$file);
		return $file;
	}
	private function tplfilter($name){
		$path = __DIR__.'/filter/'.$name.'.php';
		$def = __DIR__.'/filter/all.php';
		if(file_exists($path)){
			$file = file_get_contents($path);
		}
		else{
			$file = file_get_contents($def);
		}
		if(isset($_POST[$name])){
			$file = str_replace('[[value]]',$_POST[$name],$file);
		}
		else{
			$file = str_replace('[[value]]','',$file);
		}
		if(isset($this->arParams['TABLE'])){ 
			$file = str_replace("[[name]]",GetMessage($this->arParams['TABLE'].'_'.$name),$file);
		}
		$file = str_replace("[[key]]",$name,$file);
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
		$arrtype = $this->arParams['FIELDS'];
		foreach($arrtype as $key=>$item){
			$resultfiler[$item] = $this->tplfilter($item);
		}
        $this->arResult = ['res'=>$result,'filter'=>$resultfiler];
        $this->IncludeComponentTemplate(); 
    }
}