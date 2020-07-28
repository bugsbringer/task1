<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>


<div>
<?if (!$arResult["SelectedRowsCount"]):?>
	<h3>Новостей пока нет</h3>
<?else:?>

	<!-- Навигация -->
	<?if ($arParams['NEWS_COUNT'] && $arResult["SelectedRowsCount"] > $arParams['NEWS_COUNT']):?>
		<?=$arResult["NavPrint"];?><br>
	<?endif;?>
	
	<!-- Верстка элементов -->
	<?foreach ($arResult["ITEMS"] as $arItem) {
		
		$this->AddEditAction($arItem['ID'], $arItem['ADD_BUTTON']["ACTION_URL"], $arItem['ADD_BUTTON']["TEXT"]);
		$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK']["ACTION_URL"], $arItem['EDIT_LINK']["TEXT"]);
		$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK']["ACTION_URL"], $arItem['DELETE_LINK']["TEXT"], array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
		?>
		<p id="<?=$this->GetEditAreaId($arItem['ID']);?>">
			<a><b><?echo $arItem["NAME"]?></b></a><br>
			<span><?echo $arItem["ACTIVE_FROM"]?></span><br>
			<?echo $arItem["PREVIEW_TEXT"];?>

			<!-- Кнопки действий -->
			<div style="display: flex;">

				<!-- Форма перехода к редактированию статьи -->
				<form method="post" action="#bottom-form">
					<input name="action" value="edit" hidden>
					<input name="ID" value="<?echo $arItem["ID"]?>" hidden>
					<input type="submit" value="Редактировать">
				</form>

				<!-- Форма удаления статьи -->
				<form method="post">
					<input name="action" value="delete" hidden>
					<input name="ID" value="<?echo $arItem["ID"]?>" hidden>
					<input type="submit" value="Удалить">
				</form>

			</div>
		</p>
	<?} ?>

<?endif;?>
</div>

<!-- Форма добавления/редактирования статьи -->
<h2><?if ($arResult["isEditAction"]):?>Изменить<?else:?>Добавить<?endif;?> новость</h2>
<form method="post" id="bottom-form" action="#">

	<input name="action" value=<?if ($arResult["isEditAction"]):?>"commit_edits"<?else:?>"add"<?endif;?> hidden>
	<?if ($arResult["isEditAction"]):?><input name="ID" value="<?=$arResult["EDITED_ITEM"]['ID']?>" hidden><?endif;?>

    <table style="border-collapse: separate; border-spacing: 10px;">
        <tr>
            <td>Название</td>
            <td><input type="text" name="NAME" size="61"
			<?if($arResult["isEditAction"]):?>value="<?=$arResult["EDITED_ITEM"]["NAME"]?>"<?endif;?>required></td>
        </tr>
        <tr>
            <td>Анонс</td>
            <td><textarea name="PREVIEW_TEXT" id="" cols="60" rows="4" required><?if($arResult["isEditAction"])echo $arResult["EDITED_ITEM"]["PREVIEW_TEXT"];?></textarea></td>
        </tr>
        <tr>
            <td>Текст</td>
            <td><textarea name="DETAIL_TEXT" id="" cols="60" rows="4" required><?if($arResult["isEditAction"])echo $arResult["EDITED_ITEM"]["DETAIL_TEXT"];?></textarea></td>
        </tr>
        <tr>
            <td><button type="reset">Отмена</button></td>
            <td><input type="submit" value=<?if ($arResult["isEditAction"]):?>"Cохранить"<?else:?>"Добавить"<?endif;?>></td>
        </tr>
    </table>
</form>
