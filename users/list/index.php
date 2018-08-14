<?php

/** @global \CMain $APPLICATION */
global $APPLICATION;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Список пользователей");
?>

<?$APPLICATION->IncludeComponent('ylab:users.list', '', array("USERS_IBLOCK_CODE" => 'users_iblock'));?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
