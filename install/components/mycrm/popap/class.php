<?

use Bitrix\Main;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Type;
use Bike\Mycrm;
use Bitrix\Sale\Internals;
use Bitrix\Main\Loader;
use Bitrix\Main\Engine\Contract\Controllerable;
	
class popapClass extends CBitrixComponent{
	private $tables;
	private $fields;
	protected function checkModules(){
		$this->tables = ['TASK'=>'TaskcrmTable','USER'=>'UsercrmTable','STATUS'=>'StatuscrmTable'];
		$this->fields = ['TASK'=>['NAME','USER','STATUS','DESCRIPTION'],'USER'=>['NAME','JOB'],'STATUS'=>['NAME','COLOR']];
		if(!Loader::includeModule('bike.mycrm')){
			throw new Main\LoaderException('Модуль bike.mycrm не установлен');
		}
	}

	private function tpl($name){ 
		$path = __DIR__.'/files/'.$name.'.php';
		$def =  __DIR__.'/files/all.php';
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
		if($this->arParams['TABLE']=='TASK'){
			if($name=='USER'){
				$arr_user = \Bike\Mycrm\UsercrmTable::getList()->fetchAll();
				$userfield='<select class="form-select" name="'.$name.'" aria-label="Default select example">';
				foreach($arr_user as $user){
					$userfield.='<option value="'.$user['ID'].'">'.$user['NAME'].'</option>';
				}
				$userfield.='</select>';
				$file = str_replace("[[user]]",$userfield,$file);
			}
			if($name=='STATUS'){
				$arr_status = \Bike\Mycrm\StatuscrmTable::getList()->fetchAll();
				$statusfield='<select class="form-select" name="'.$name.'" aria-label="Default select example">';
				foreach($arr_status as $status){
					$statusfield.='<option value="'.$status['ID'].'" >'.$status['NAME'].'</option>';
				}
				$statusfield.='</select>';
				$file = str_replace("[[status]]",$statusfield,$file);
			}
		   }
		return $file;
	}
    public function executeComponent(){
        $this->includeComponentLang('lang.php');
        $this->checkModules();
 		$arrtype = $this->fields[$this->arParams['TABLE']];
		foreach($arrtype as $key=>$item){
			$result[$item] = $this->tpl($item);
		}
        $this->arResult=$result;
        $this->IncludeComponentTemplate();
    }
}