<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("1С-Битрикс обучение");
?>

<?$APPLICATION->IncludeComponent('ylab:user.add', '', array("USERS_IBLOCK_CODE" => 'users_iblock'));?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>