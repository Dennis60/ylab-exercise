<?php

namespace YLab\Webinar;

/**
 * Class Helper
 * @package YLab\Webinar
 */
class Helper
{
    /**
     * Возврашает список городов из класса YlabUsersTable
     *
     * @return array
     */
    public static function getCities()
    {
        try {
            return YlabUsersTable::getEntity()->getField('CITY')->getValues();
        } catch (\Exception $e) {
            ShowError($e->getMessage());
        }

        return array();
    }
}
