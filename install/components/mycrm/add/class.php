<?

use Bitrix\Main;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Type;
use Bike\Mycrm;
use Bitrix\Sale\Internals;
use Bitrix\Main\Loader;
use Bitrix\Main\Engine\Contract\Controllerable;
	
class addClass extends CBitrixComponent{
	public $tables;
	protected function checkModules(){
		$this->tables = ['TASK'=>'\Bike\Mycrm\TaskcrmTable','USER'=>'\Bike\Mycrm\UsercrmTable','STATUS'=>'\Bike\Mycrm\StatuscrmTable'];
		if(!Loader::includeModule('bike.mycrm')){
			throw new Main\LoaderException('Модуль bike.mycrm не установлен');
		}
	}
	
	public function onPrepareComponentParams($arParams = [])
    {

		foreach($arParams as $key=>$val){
			$arParams[$key]=preg_replace('/([^#0-9a-zA-Zа-яёА-ЯЁ\-\.@_ ]+)/ui','',$val);
		}
		$arParams['UPDATED_AT']=new Type\DateTime();
		$arParams['CREATED_AT']=new Type\DateTime();
		return $arParams;
    }
	public function add()
	{

		if(isset($this->arParams['CREATED_AT'])){
			if(isset($this->tables[$this->arParams['TABLE']])){
				$table = $this->tables[$this->arParams['TABLE']];
				unset($this->arParams['TABLE']);		print_r($this->arParams);				
				$result = $table::add($this->arParams);
			}
		}
	}

    public function executeComponent(){
        $this->includeComponentLang('lang.php');
        $this->checkModules();
		$result = $this->add();
		if($result->isSuccess()){
			$id=$result->getId();
			$this->arResult=array('status'=>true,'text'=>$id,'error'=>false);;
		}
		else{
			$error = $result->getErrorMessages();
			$this->arResult=array('status'=>'system-error','text'=>$error,'error'=>true);
		}
        $this->IncludeComponentTemplate();
    }
}