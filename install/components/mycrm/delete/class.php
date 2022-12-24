<?
use Bitrix\Main;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Type;
use Bike\Mycrm;

class deleteClass extends CBitrixComponent{
    public $tables;
    protected function checkModules(){
		$this->tables = ['TASK'=>'\Bike\Mycrm\TaskcrmTable','USER'=>'\Bike\Mycrm\UsercrmTable','STATUS'=>'\Bike\Mycrm\StatuscrmTable'];
        if(!Main\Loader::includeModule('bike.mycrm')){
            throw new Main\LoaderException('Модуль bike.mycrm не установлен');
        }
    }

    public function onPrepareComponentParams($arParams = []): array
    {
        if (isset($arParams['ID'])) {
            $arParams['ID']=(int)$arParams['ID'];
        }
        return $arParams;
    }
    public function delete(){
        if(isset($this->arParams['ID'])){ 
			if($this->arParams['TABLE']=='USER'){
				$arr_task = \Bike\Mycrm\TaskcrmTable::getList(array('filter'=>['USER'=>$this->arParams['ID']]))->fetchAll();
				if(isset($arr_task[0]['ID'])){
					return false;
				}
			}		           
			else if(isset($this->tables[$this->arParams['TABLE']])){
				$table = $this->tables[$this->arParams['TABLE']];
                $result = $table::delete($this->arParams['ID']);
            }
			else{
				return false;
			}
        }
        else{
            $result=false;
        }
        return $result;
    }
    public function executeComponent(){
        $this->includeComponentLang('lang.php');
        $this->checkModules();
        $result = $this->delete();
        if($result){
			if($result->isSuccess()){
				$this->arResult=array('status'=>true,'text'=>'Элемент удален','error'=>false);
			}
			else{
				$error = $result->getErrorMessages();
				$this->arResult=array('status'=>'system-error','text'=>$error,'error'=>true);
			}
        }else{
            $this->arResult=array('status'=>'warning-error','text'=>'не задан параметр ID','error'=>true);
        }
        $this->IncludeComponentTemplate();
    }
}