<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("1С-Битрикс обучение");
?>

<?$APPLICATION->IncludeComponent('ylab:users.list', '', array("USERS_IBLOCK_ID" => 1));?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>