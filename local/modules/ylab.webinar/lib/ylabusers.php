<?php

namespace YLab\Webinar;

use Bitrix\Main\Entity;
use Bitrix\Main\Entity\DataManager;

/**
 * Class YlabUsersTable
 *
 * @package YLab\Webinar
 */
class YlabUsersTable extends DataManager
{
    /**
     * @return string
     */
    public static function getFilePath()
    {
        return __FILE__;
    }

    /**
     * @return string
     */
    public static function getTableName()
    {
        return 'b_ylab_users';
    }

    /**
     * @return array
     * @throws \Bitrix\Main\SystemException
     */
    public static function getMap()
    {
        return array(
            new Entity\IntegerField('ID', array(
                'primary' => true,
                'autocomplete' => true
            )),
            new Entity\StringField('NAME', array(
                'required' => true
            )),
            new Entity\EnumField('CITY', array(
                'values' => array('Москва', 'Санкт-Петербург', 'Казань'),
                'required' => true
            )),
            new Entity\DateField('DATEBIRTH', array(
                'required' => true
            )),
            new Entity\StringField('PHONE', array(
                'required' => true
            ))
        );
    }
}
