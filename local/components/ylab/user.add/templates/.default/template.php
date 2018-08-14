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

<form action="" method="post" class="form form-block">
    <?= bitrix_sessid_post() ?>
    <? if (count($arResult['ERRORS'])): ?>
        <p><?= implode('<br/>', $arResult['ERRORS']) ?></p>
    <?elseif ($arResult['SUCCESS']):?>
        <p>Пользователь создан</p>
    <? endif; ?>
    <div>
        <label>
            Имя<br>
            <input type="text" name="name"<?= isset($arResult['REQUEST']['name']) ? ' value="' . $arResult['REQUEST']['name'] . '"' : '' ?>/>
        </label>
    </div>
    <div>
        <label>
            Дата рождения<br>
            <input type="text" name="birthday"<?= isset($arResult['REQUEST']['birthday']) ? ' value="' . $arResult['REQUEST']['birthday'] . '"' : '' ?>/>
        </label>
    </div>
    <div>
        <label>
            Телефон<br>
            <input type="text" name="phone"<?= isset($arResult['REQUEST']['phone']) ? ' value="' . $arResult['REQUEST']['phone'] . '"' : '' ?>/>
        </label>
    </div>
    <div>
        <label>
            Город<br>
            <select name="city"/>
                <option value="">Выбрать</option>
                <? foreach ($arResult['CITIES'] as $iCityID => $sCityName): ?>
                    <option value="<?= $iCityID ?>"<?= (isset($arResult['REQUEST']['city']) && $arResult['REQUEST']['city'] == $iCityID) ? ' selected="selected"' : '' ?>>
                        <?= $sCityName ?></option>
                <? endforeach; ?>
            </select>
        </label>
    </div>
    <div class="btn green">
        <button type="submit" name="submit">Отправить</button>
    </div>
</form>