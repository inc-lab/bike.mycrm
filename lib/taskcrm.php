<?php

namespace Bike\Mycrm;

use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\StringField;
use Bitrix\Main\Entity\DatetimeField;
use Bitrix\Main\Entity\Validator;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Type;
Loc::loadMessages(__FILE__);

class TaskcrmTable extends DataManager
{
    // название таблицы
    public static function getTableName()
    {
        return 'taskcrm';
    }
    // создаем поля таблицы
    public static function getMap()
    {
        return array(
            new IntegerField('ID', array(
                'autocomplete' => true,
                'primary' => true
            )),
            new StringField('NAME', array(
                'required' => false,
                'title' => Loc::getMessage('MYMODULE_NAME'),
                'default_value' => function () {
                    return Loc::getMessage('MYMODULE_NAME_DEFAULT_VALUE');
                },
                'validation' => function () {
                    return array(
                        new Validator\Length(null, 255),
                    );
                }              
            )),
            new StringField('USER', array(
                'required' => false,
                'title' => Loc::getMessage('MYMODULE_USER'),
                'default_value' => function () {
                    return Loc::getMessage('MYMODULE_USER_DEFAULT_VALUE');
                },
                'validation' => function () {
                    return array(
                        new Validator\Length(null, 255),
                    );
                }              
            )),
            new StringField('STATUS', array(
                'required' => false,
                'title' => Loc::getMessage('MYMODULE_STATUS'),
                'default_value' => function () {
                    return Loc::getMessage('MYMODULE_STATUS_DEFAULT_VALUE');
                },
                'validation' => function () {
                    return array(
                        new Validator\Length(null, 255),
                    );
                }              
            )),
			new StringField('DESCRIPTION', array(
                'required' => false,
                'title' => Loc::getMessage('MYMODULE_DESCRIPTION'),
                'default_value' => function () {
                    return Loc::getMessage('MYMODULE_DESCRIPTION_DEFAULT_VALUE');
                },
                'validation' => function () {
                    return array(
                        new Validator\Length(null, 255),
                    );
                }              
            )),
            //обязательная строка с default значением  и длиной не более 255 символов
            new DatetimeField('UPDATED_AT',array(
                'required' => true)),//обязательное поле даты
            new DatetimeField('CREATED_AT',array(
                'required' => true)),//обязательное поле даты
        );
    }
}