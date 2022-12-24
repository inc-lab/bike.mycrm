<?
use Bitrix\Main;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Type;
use Bike\Mycrm;
use Bitrix\Sale\Internals;
use Bitrix\Main\Loader;

class upClass extends CBitrixComponent{
    public $tables;
    protected function checkModules(){
		$this->tables = ['TASK'=>'\Bike\Mycrm\TaskcrmTable','USER'=>'\Bike\Mycrm\UsercrmTable','STATUS'=>'\Bike\Mycrm\StatuscrmTable'];
        if(!Loader::includeModule('bike.mycrm')){
            throw new Main\LoaderException('Модуль bike.mycrm не установлен');
        }
    }
    
    public function onPrepareComponentParams($arParams = []): array
    {	
		mb_parse_str($arParams['UP'], $up);
		$arParams['UP'] = $up; 
		foreach($arParams['UP'] as $key=>$val){
			if(is_array($val)){
				$val = implode(',',$val);
			}
			$arParams['UP'][$key]=preg_replace('/[^#0-9a-zA-Zа-яёА-ЯЁ\-\.@_, ]+/ui','',$val);

		}
		$arParams['UP']['UPDATED_AT']=new Type\DateTime();
        return $arParams;
    }

    public function update($arParams=[]){
		if(isset($arParams['UP']['ID'])){
			$id = $arParams['UP']['ID'];
			unset($arParams['UP']['ID']);
			$arParams['UP']['UPDATED_AT']=new Type\DateTime();
            if(isset($this->tables[$this->arParams['TABLE']])){
				$table = $this->tables[$this->arParams['TABLE']];
                $result = $table::update((int)$id,$arParams['UP']);
                return $result;
            }
		}
    }

    public function executeComponent(){
        $this->includeComponentLang('lang.php');
        $this->checkModules();
        $result = $this->update($this->arParams);
  		if($result){
            if($result->isSuccess()){
                $id=$result->getId();
                $this->arResult=array('status'=>true,'text'=>$id,'error'=>false);;
            }
            else{
                $error = $result->getErrorMessages();
                $this->arResult=array('status'=>'system-error','text'=>$error,'error'=>true);
            }
        }else{
            $this->arResult=array('status'=>'warning-error','text'=>'не задан параметр ID  или UP, либо превышена длина в 255 символов','error'=>true);
        }
        $this->IncludeComponentTemplate();
    }
}