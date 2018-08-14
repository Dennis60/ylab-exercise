<?php

/**
 * Class UsersListComponent
 */
class UsersListComponent extends \CBitrixComponent
{
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
            $arFilter = Array("IBLOCK_ID" => $this->getIBlockID($this->arParams["USERS_IBLOCK_CODE"]), "ACTIVE" => "Y");

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

    /**
     * Получение ID инфоблока по его коду
     *
     * @param $sBlockCode
     * @return mixed
     */
    protected function getIBlockID($sBlockCode)
    {
        try {
            if (\Bitrix\Main\Loader::includeModule("iblock")) {
                $arFilter = array(
                    'CODE' => $sBlockCode,
                    'CHECK_PERMISSIONS' => 'N'
                );
                if ($oIBlock = \CIBlock::GetList(array('SORT' => 'ASC'), $arFilter)->Fetch()) {
                    return $oIBlock['ID'];
                }
            }
        } catch (\Bitrix\Main\LoaderException $e) {
            $e->getMessage();
        }
    }
}
