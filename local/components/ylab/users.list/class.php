<?php

/**
 * Class UsersListComponent
 */
class UsersListComponent extends \CBitrixComponent
{
    /**
     * @return mixed|void
     * @throws \Bitrix\Main\LoaderException
     */
    public function executeComponent()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();
        $this->arResult['USERS_LIST'] = $this->getUsersList();

        $this->includeComponentTemplate();
    }

    /**
     * Переопределяем для подключения модуля
     *
     * @param array[string]mixed $arParams
     * @return array[string]mixed
     *
     * @throws Exception
     */
    public function onPrepareComponentParams($arParams)
    {
        try {
            if (!\Bitrix\Main\Loader::includeModule('ylab.webinar')) {
                throw new \Exception("Необходимо подключить модуль 'YLab Webinar'!");
            };
        } catch (\Bitrix\Main\LoaderException $e) {
            ShowError($e->getMessage());
        }

        return $arParams;
    }

    /**
     * Возвращает массив пользователей
     *
     * @return array
     * @throws \Bitrix\Main\LoaderException
     */
    protected function getUsersList()
    {
        try {
            return \YLab\Webinar\YlabUsersTable::getList([
                'select' => ['*']
            ])->fetchAll();
        } catch (\Bitrix\Main\ObjectPropertyException $e) {
            ShowError($e->getMessage());
        } catch (\Bitrix\Main\ArgumentException $e) {
            ShowError($e->getMessage());
        } catch (\Bitrix\Main\SystemException $e) {
            ShowError($e->getMessage());
        }
    }

}
