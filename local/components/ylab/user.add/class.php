<?php

namespace YLab\Validation\Components;

use Bitrix\Main\LoaderException;
use YLab\Validation\ComponentValidation;
use YLab\Validation\ValidatorHelper;

/**
 * Class AddUserComponent
 * Компонент, предназначенный для валидации и добавления данных пользователя
 */
class AddUserComponent extends ComponentValidation
{
    /**
     * Хранится ID инфоблока
     *
     * @var int
     */
    protected $iIBlockID;

    /**
     * Список городов
     *
     * @var array
     */
    protected $arCities;

    /**
     * AddUserComponent constructor.
     * @param \CBitrixComponent|null $component
     * @param string $sFile
     * @throws \Bitrix\Main\IO\InvalidPathException
     * @throws \Bitrix\Main\SystemException
     * @throws \Exception
     */
    public function __construct(\CBitrixComponent $component = null, $sFile = __FILE__)
    {
        $this->iIBlockID = $this->getIBlockID($this->arParams['USERS_IBLOCK_CODE']);
        $this->arCities = $this->getCities();

        parent::__construct($component, $sFile);
    }

    /**
     * @return mixed|void
     * @throws \Exception
     */
    public function executeComponent()
    {
        if ($this->oRequest->isPost() && check_bitrix_sessid()) {
            $arRequest = $this->oRequest->toArray();
            $this->oValidator->setData($arRequest);

            if ($this->oValidator->passes()) {
                if ($this->userAdd($arRequest)) {
                    $this->arResult['SUCCESS'] = true;
                } else {
                    $this->arResult['ERRORS'] = array('Пользователь не создан');
                }
            } else {
                $this->arResult['ERRORS'] = ValidatorHelper::errorsToArray($this->oValidator);
                $this->arResult['REQUEST'] = $arRequest;
            }
        }

        $this->arResult['CITIES'] = $this->arCities;

        $this->includeComponentTemplate();
    }

    /**
     *  Формирование массива правил валидации
     *
     * @return array
     */
    protected function rules()
    {
        return [
            'name' => 'required',
            'birthday' => 'required|date_format:d.m.Y',
            'phone' => 'required|regex:/\+7\d{10}/',
            'city' => 'required|numeric|in:' . implode(',', array_keys($this->arCities))
        ];
    }

    /**
     * Добавление пользователя
     *
     * @param $arRequest
     * @return bool
     */
    protected function userAdd($arRequest)
    {
        if (\CModule::IncludeModule('iblock')) {

            $PROP = array(
                'PHONE' => $arRequest['phone'],
                'BIRTHDAY' => $arRequest['birthday'],
                'CITY' => array('VALUE' => $arRequest['city'])
            );

            $oIBlockElement = new \CIBlockElement;

            $arFields = array(
                'IBLOCK_SECTION_ID' => false,
                'IBLOCK_ID' => $this->iIBlockID,
                'PROPERTY_VALUES' => $PROP,
                'NAME' => $arRequest['name'],
                'ACTIVE' => 'Y'
            );

            return $oIBlockElement->Add($arFields);

        } else {
            return false;
        }
    }

    /**
     * Получение ID инфоблока
     *
     * @param $sBlockCode
     * @return int|bool
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
        } catch (LoaderException $e) {
            $e->getMessage();
        }

        return false;
    }

    /**
     * Получение списка городов
     *
     * @return array
     */
    protected function getCities()
    {
        $arCities = array();

        try {
            if (\Bitrix\Main\Loader::includeModule("iblock")) {

                $oCities = \CIBlockPropertyEnum::GetList(Array("ID" => "ASC"),
                    Array("IBLOCK_ID" => $this->iIBlockID, "CODE" => "CITY"));

                while ($arCity = $oCities->Fetch()) {
                    $arCities[$arCity["ID"]] = $arCity["VALUE"];
                }

            }
        } catch (LoaderException $e) {
            $e->getMessage();
        }

        return $arCities;
    }
}