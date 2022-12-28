<?

use Bitrix\Main;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Type;
use Bitrix\Main\Application;
use Bike\Mycrm;
use Bitrix\Sale\Internals;
use Bitrix\Main\Loader;
use Bitrix\Main\Engine\Contract\Controllerable;

class addClass extends CBitrixComponent{

	protected function checkModules(){
		$this->tables = ['TASK'=>'\Bike\Mycrm\TaskcrmTable','USER'=>'\Bike\Mycrm\UsercrmTable','STATUS'=>'\Bike\Mycrm\StatuscrmTable'];
		$this->field = ['TASK'=>['NAME','USER','STATUS','DESCRIPTION'],'USER'=>['NAME','JOB'],'STATUS'=>['NAME','COLOR']];
		if(!Loader::includeModule('bike.mycrm')){
			throw new Main\LoaderException('Модуль bike.mycrm не установлен');
		}
	}

	public function onPrepareComponentParams($arParams = []): array
    {
		$request = Application::getInstance()->getContext()->getRequest()->toArray();	
		$arParams['TABLE'] = isset($request['TABLE']) ? $request['TABLE'] : 'TASK';
		$arParams['ID'] = isset($request['id']) ? (int)$request['id'] : false;
		if(isset($request['filter'])){
			$arParams['TABLE'] = $request['filter'];
			unset($request['filter']);
			$arParams['FILTER'] = $request;
		}
		foreach($arParams['FILTER'] as $key=>$val){
			if(in_array($key,$this->field[$arParams['TABLE']])){
				$arParams['FILTER'][$key]=preg_replace('/[^0-9a-zA-Zа-яёА-ЯЁ\-\.@_ ]+/ui','',$val);
			}
			else{
				unset($arParams['FILTER'][$key]);
			}
		}
		return $arParams;
    }

    public function executeComponent(){
        $this->includeComponentLang('lang.php');
        $this->checkModules();
        $this->arResult=$this->arParams;
		if(!isset($this->field[$this->arParams['TABLE']])){
			exit();
		}
		foreach($this->tables as $table=>$orm){
			if($table == $this->arResult['TABLE']){ 
				$this->arResult['COLOR'][$table]='bg-success';
			}
			else{
				$this->arResult['COLOR'][$table]='';
			}
		}

        $this->IncludeComponentTemplate();
    }
}