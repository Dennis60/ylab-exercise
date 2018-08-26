<?php

/** @global \CMain $APPLICATION */
global $APPLICATION;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("1С-Битрикс обучение");
?>

<?$APPLICATION->IncludeComponent('ylab:user.add', '');?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>