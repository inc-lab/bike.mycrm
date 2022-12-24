<?php

use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Bitrix\Main\Entity\Base;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Bike\Mycrm\UsercrmTable;
use Bike\Mycrm\StatuscrmTable;
use Bike\Mycrm\TaskcrmTable;
Loc::loadMessages(__FILE__);

class bike_mycrm extends CModule
{
    public function __construct()
    {
        $arModuleVersion = array();
        include __DIR__ . '/version.php';
        if (is_array($arModuleVersion) && array_key_exists('VERSION', $arModuleVersion)) {
            $this->MODULE_VERSION = $arModuleVersion['VERSION'];
            $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        }
        $this->MODULE_ID = 'bike.mycrm';
        $this->MODULE_NAME = Loc::getMessage('MYMODULE_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('MYMODULE_MODULE_DESCRIPTION');
        $this->MODULE_GROUP_RIGHTS = 'N';
        $this->PARTNER_NAME = Loc::getMessage('MYMODULE_MODULE_PARTNER_NAME');
        $this->PARTNER_URI = 'https://foton.name';

    }
    public function getRoot()
    {
        return $_SERVER['DOCUMENT_ROOT'] .'/bitrix';
    }    
    public function doInstall()
    {
        CopyDirFiles(__DIR__ . '/components', $this->getRoot() . "/components", true, true);
        CopyDirFiles(__DIR__ . '/page', $_SERVER['DOCUMENT_ROOT'], true, true);
		copy(__DIR__ . '/admin/mycrm_index.php', $_SERVER['DOCUMENT_ROOT'].'/bitrix/admin/mycrm_index.php');
        ModuleManager::registerModule($this->MODULE_ID);
        $this->installDB();
    }

    public function doUninstall()
    {
		 DeleteDirFilesEx($this->getRoot(). '/components/mycrm');
		 DeleteDirFilesEx($_SERVER['DOCUMENT_ROOT']. '/mycrm');
		 unlink($_SERVER['DOCUMENT_ROOT'].'/bitrix/admin/mycrm_index.php');
		 $this->uninstallDB();
        ModuleManager::unRegisterModule($this->MODULE_ID);
    }
    public function installDB()
    {
        if (Loader::includeModule($this->MODULE_ID)) {
            Bike\Mycrm\UsercrmTable::getEntity()->createDbTable();
            Bike\Mycrm\TaskcrmTable::getEntity()->createDbTable();
            Bike\Mycrm\StatuscrmTable::getEntity()->createDbTable();
            
        }
    }
    public function uninstallDB()
    {
        if (Loader::includeModule($this->MODULE_ID)) {
            if (Application::getConnection()->isTableExists(Base::getInstance('\Bike\Mycrm\UsercrmTable')->getDBTableName())) {
                $connection = Application::getInstance()->getConnection();
                $connection->dropTable(Bike\Mycrm\UsercrmTable::getTableName());
            }
            if (Application::getConnection()->isTableExists(Base::getInstance('\Bike\Mycrm\TaskcrmTable')->getDBTableName())) {
                $connection = Application::getInstance()->getConnection();
                $connection->dropTable(Bike\Mycrm\TaskcrmTable::getTableName());
            }
            if (Application::getConnection()->isTableExists(Base::getInstance('\Bike\Mycrm\StatuscrmTable')->getDBTableName())) {
                $connection = Application::getInstance()->getConnection();
                $connection->dropTable(Bike\Mycrm\StatuscrmTable::getTableName());
            }
        }
    }
}