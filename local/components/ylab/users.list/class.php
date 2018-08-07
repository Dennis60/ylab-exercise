<?php

/**
 * Class UsersListComponent
 */
class UsersListComponent extends \CBitrixComponent
{
    /**
     * Содержит номер инфоблока "Пользователи"
     *
     * @var int
     */
    const USERS_IBLOCK_ID = 1;

    /**
     * @return mixed|void
     */
    public function executeComponent()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();
        $this->arResult = $this->getUsersList();

        $this->includeComponentTemplate();
    }

    /**
     * Возвращает массив активных записей из инфоблока "Пользователи"
     *
     * @return array
     * @throws \Bitrix\Main\LoaderException
     */
    protected function getUsersList()
    {
        $arUsers = array();

        if (\Bitrix\Main\Loader::includeModule("iblock")) {
            $arSelect = Array("ID", "NAME");
            $arFilter = Array("IBLOCK_ID" => self::USERS_IBLOCK_ID, "ACTIVE" => "Y");

            try {
                $oUsers = \Bitrix\Iblock\ElementTable::getList(array('select' => $arSelect, 'filter' => $arFilter));
            } catch (\Bitrix\Main\ObjectPropertyException $e) {
                $e->getMessage();
            } catch (\Bitrix\Main\ArgumentException $e) {
                $e->getMessage();
            } catch (\Bitrix\Main\SystemException $e) {
                $e->getMessage();
            }

            foreach ($oUsers->fetchAll() as $arUser) {
                $arUsers[] = $arUser;
            }
        }

        return $arUsers;
    }
}
