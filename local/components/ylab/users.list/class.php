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
     */
    protected function getUsersList()
    {
        $arUsers = array();

        if (CModule::IncludeModule("iblock")) {
            $arOrder = Array("ID" => "ASC");
            $arFilter = Array("IBLOCK_ID" => self::USERS_IBLOCK_ID, "ACTIVE" => "Y");
            $arSelect = Array("ID", "NAME");
            $oUsers = CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);

            while ($arUser = $oUsers->GetNext(false, false)) {
                $arUsers[] = $arUser;
            }
        }
        return $arUsers;
    }
}
