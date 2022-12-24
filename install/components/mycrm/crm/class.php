<?

use Bitrix\Main;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Type;
use Bike\Mycrm;
use Bitrix\Sale\Internals;
use Bitrix\Main\Loader;
use Bitrix\Main\Engine\Contract\Controllerable;

class addClass extends CBitrixComponent{

	protected function checkModules(){
		$this->tables = ['TASK'=>'\Bike\Mycrm\TaskcrmTable','USER'=>'\Bike\Mycrm\UsercrmTable','STATUS'=>'\Bike\Mycrm\StatuscrmTable'];
		if(!Loader::includeModule('bike.mycrm')){
			throw new Main\LoaderException('Модуль bike.mycrm не установлен');
		}
	}

	public function onPrepareComponentParams($arParams = []): array
    {
		if(isset($_GET['filter'])){
			$arParams['TABLE'] = $_GET['filter'];
			unset($_GET['filter']);
			$arParams['FILTER'] = $_GET;
		}
		foreach($arParams['FILTER'] as $key=>$val){
			$arParams['FILTER'][$key]=preg_replace('/[^0-9a-zA-Zа-яёА-ЯЁ\-\.@_ ]+/ui','',$val);
		}
		return $arParams;
    }

    public function executeComponent(){
        $this->includeComponentLang('lang.php');
        $this->checkModules();
        $this->arResult=$this->arParams;
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