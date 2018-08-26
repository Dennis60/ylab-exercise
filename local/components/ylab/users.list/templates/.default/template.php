<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
?>
<a href="/">Добавить пользователя</a>
<? if (count($arResult['USERS_LIST'])): ?>
    <ul>
        <? foreach ($arResult['USERS_LIST'] as $aUser): ?>
            <li>
                <div><?= $aUser['NAME'] ?></div>
                <ul>
                    <li>День рождения: <?= $aUser['DATEBIRTH'] ?></li>
                    <li>Телефон: <?= $aUser['PHONE'] ?></li>
                    <li>Город: <?= $aUser['CITY'] ?></li>
                </ul>
            </li>
        <? endforeach; ?>

    </ul>
<? else: ?>
    <p>Список пользователей пуст</p>
<? endif; ?>