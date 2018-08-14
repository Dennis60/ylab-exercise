<?php

use Phinx\Migration\AbstractMigration;

/**
 * Class Init
 * Миграция по созданию инфоблока "Пользователи"
 */
class Init extends AbstractMigration
{
    /**
     * Массив, в котором хранятся все данные миграции.
     * Обязательные элементы:
     * - IBLOCK_NAME - имя инфоблока
     * - IBLOCK_CODE - код инфоблока
     * - IBLOCK_TYPE_ID - ID типа инфоблока
     * - IBLOCK_TYPE_NAME_RU - Имя типа инфоблока на русском языке
     * - IBLOCK_TYPE_NAME_EN - Имя типа инфоблока на английском языке
     * - SITE_ID - ID сайта
     * - IBLOCK_PROPERTIES - свойства инфоблока
     *
     * @var array
     */
    protected $arMigrationData;

    /**
     * Запуск миграции
     *
     * @throws \Bitrix\Main\LoaderException
     */
    public function up()
    {
        $this->initData();

        if (\Bitrix\Main\Loader::includeModule("iblock")) {
            $this->addIBlockType();
            $this->addIBlock();
            $this->addIBlockProperties();
        }
    }

    /**
     * Откат миграции
     *
     * @throws \Bitrix\Main\LoaderException
     */
    public function down()
    {
        $this->initData();

        if (\Bitrix\Main\Loader::includeModule("iblock")) {
            $this->deleteIBlock();
            $this->deleteIBlockType();
        }

    }

    /**
     * Инициализация массива данных миграции
     */
    protected function initData()
    {
        $this->arMigrationData = array(
            'IBLOCK_NAME' => 'Пользователи',
            'IBLOCK_CODE' => 'users_iblock',
            'IBLOCK_TYPE_ID' => 'users_iblock_type',
            'IBLOCK_TYPE_NAME_RU' => 'Пользователи',
            'IBLOCK_TYPE_NAME_EN' => 'Users',
            'SITE_ID' => array('s1'),
            'IBLOCK_PROPERTIES' => array(
                'BIRTHDAY' => array(
                    'NAME' => 'Дата рождения',
                    'PROPERTY_TYPE' => 'S',
                    'USER_TYPE' => 'DateTime',
                    'IS_REQUIRED' => 'Y'
                ),
                'PHONE' => array(
                    'NAME' => 'Телефон',
                    'PROPERTY_TYPE' => 'S',
                    'USER_TYPE' => '',
                    'IS_REQUIRED' => 'Y'
                ),
                'CITY' => array(
                    'NAME' => 'Город',
                    'PROPERTY_TYPE' => 'L',
                    'USER_TYPE' => '',
                    'IS_REQUIRED' => 'Y',
                    'LIST' => array(
                        'Москва',
                        'Санкт-Петербург',
                        'Казань'
                    )
                )
            )
        );
    }

    /**
     * Добавление инфоблока
     *
     * @throws Exception
     */
    protected function addIBlock()
    {
        $arFields = array(
            'ACTIVE' => 'Y',
            'NAME' => $this->arMigrationData['IBLOCK_NAME'],
            'CODE' => $this->arMigrationData['IBLOCK_CODE'],
            'IBLOCK_TYPE_ID' => $this->arMigrationData['IBLOCK_TYPE_ID'],
            'SITE_ID' => $this->arMigrationData['SITE_ID'],
            'SORT' => 500
        );

        $oBlock = new \CIBlock;
        if (!($this->arMigrationData['IBLOCK_ID'] = $oBlock->Add($arFields))) {
            $this->deleteIBlockType();
            $this->throwException(__METHOD__, "инфоблок не создан");
        }

    }

    /**
     * Удаление инфоблока
     *
     * @throws Exception
     */
    protected function deleteIBlock()
    {
        $arIBlock = $this->getIBlock();

        if (!\CIBlock::Delete($arIBlock['ID'])) {
            $this->throwException(__METHOD__, "инфоблок не удален");
        }

    }

    /**
     * Получение инфоблока по коду
     *
     * @return array
     * @throws Exception
     */
    protected function getIBlock()
    {
        $arFilter = array(
            'CODE' => $this->arMigrationData['IBLOCK_CODE'],
            'CHECK_PERMISSIONS' => 'N'
        );
        if ($arIBlock = \CIBlock::GetList(array('SORT' => 'ASC'), $arFilter)->Fetch()) {
            return $arIBlock;
        } else {
            $this->throwException(__METHOD__, "блок не найден");
        }
    }

    /**
     * Добавление типа инфоблока
     *
     * @throws Exception
     */
    protected function addIBlockType()
    {
        $arFilterBlockType = array(
            'ID' => $this->arMigrationData['IBLOCK_TYPE_ID'],
            'CHECK_PERMISSIONS' => 'N'
        );

        if (\CIBlockType::GetList(array('SORT' => 'ASC'), $arFilterBlockType)->Fetch()) {

            $this->throwException(__METHOD__, "тип инфоблока существует");

        } else {

            $arFields = Array(
                'ID' => $this->arMigrationData['IBLOCK_TYPE_ID'],
                'SECTIONS' => 'Y',
                'IN_RSS' => 'N',
                'SORT' => 100,
                'LANG' => Array(
                    'ru' => Array(
                        'NAME' => $this->arMigrationData['IBLOCK_TYPE_NAME_RU']
                    ),
                    'en' => Array(
                        'NAME' => $this->arMigrationData['IBLOCK_TYPE_NAME_EN']
                    ),
                )
            );

            $oIBlockType = new \CIBlockType;
            if (!$oIBlockType->Add($arFields)) {
                $this->throwException(__METHOD__, "тип инфоблока не создан");
            }
        }
    }

    /**
     * Удаление инфоблока
     *
     * @throws Exception
     */
    protected function deleteIBlockType()
    {
        if (!\CIBlockType::Delete($this->arMigrationData['IBLOCK_TYPE_ID'])) {
            $this->throwException(__METHOD__, "тип инфоблока не удален");
        }
    }

    /**
     * Добавление свойств инфоблока
     *
     * @throws Exception
     */
    protected function addIBlockProperties()
    {
        foreach ($this->arMigrationData['IBLOCK_PROPERTIES'] as $sProperty => $arValues) {
            $arAllValues = array(
                'IBLOCK_ID' => $this->arMigrationData['IBLOCK_ID'],
                'NAME' => $arValues['NAME'],
                'ACTIVE' => 'Y',
                'SORT' => '500',
                'CODE' => $sProperty,
                'PROPERTY_TYPE' => $arValues['PROPERTY_TYPE'],
                'USER_TYPE' => $arValues['USER_TYPE'],
                'ROW_COUNT' => '1',
                'COL_COUNT' => '30',
                'LIST_TYPE' => 'L',
                'MULTIPLE' => 'N',
                'IS_REQUIRED' => $arValues['IS_REQUIRED'],
                'FILTRABLE' => 'Y',
                'LINK_IBLOCK_ID' => 0
            );

            $oIBlockProperty = new \CIBlockProperty;
            if (!($iIBlockProperty = $oIBlockProperty->Add($arAllValues))) {
                $this->throwException(__METHOD__, "свойство инфоблока не создано");
            }
            if ($arValues['PROPERTY_TYPE'] == 'L') {
                foreach ($arValues['LIST'] as $sItem) {
                    \CIBlockPropertyEnum::Add(array('VALUE' => $sItem, 'PROPERTY_ID' => $iIBlockProperty));
                }
            }
        }
    }

    /**
     * Формирование исключения
     *
     * @param $sMethod
     * @param $sMessage
     * @throws Exception
     */
    protected function throwException($sMethod, $sMessage)
    {
        throw new Exception($sMethod . ': ' . $sMessage);
    }
}
