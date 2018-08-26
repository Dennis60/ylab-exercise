<?php

use Bitrix\Main\Type;
use YLab\Validation\ComponentValidation;
use YLab\Validation\ValidatorHelper;

/**
 * Class AddUserComponent
 * Компонент, предназначенный для валидации и добавления данных пользователя
 */
class AddUserComponent extends ComponentValidation
{
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
        try {
            if (!\Bitrix\Main\Loader::includeModule('ylab.webinar')) {
                throw new \Exception("Необходимо подключить модуль 'YLab Webinar'!");
            };
        } catch (\Bitrix\Main\LoaderException $e) {
            ShowError($e->getMessage());
        }

        $this->arCities = \YLab\Webinar\Helper::getCities();

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
     * Формирование массива правил валидации
     *
     * @return array
     */
    protected function rules()
    {
        return [
            'name' => 'required',
            'datebirth' => 'required|date_format:d.m.Y',
            'phone' => 'required|regex:/^\+7\d{10}$/',
            'city' => 'required|numeric|in:' . implode(',', array_keys($this->arCities))
        ];
    }

    /**
     * Добавление пользователя
     *
     * @param $arRequest
     * @return bool
     * @throws \Exception
     */
    protected function userAdd($arRequest)
    {
        try {
            return \YLab\Webinar\YlabUsersTable::add([
                'NAME' => $arRequest['name'],
                'CITY' => $this->arCities[$arRequest['city']],
                'DATEBIRTH' => new Type\Date($arRequest['datebirth'], 'd.m.Y'),
                'PHONE' => $arRequest['phone']
            ])->isSuccess();
        } catch (\Bitrix\Main\SystemException $e) {
            ShowError($e->getMessage());
        }
    }
}
