<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<? CModule::IncludeModule("iblock") ?>
<?$isEditAction = false;?>

<!-- Обработка запросов -->
<?if ($_POST && array_key_exists("action", $_POST)) {
	switch ($_POST["action"]) {
		case 'add':
			$element = new CIBlockElement;

			$new_artical_array = Array(
				"IBLOCK_ID" 	=> $arParams["IBLOCK_ID"],
				"NAME"			=> $_POST["NAME"],
				"ACTIVE"		=> "Y",
				"PREVIEW_TEXT"	=> $_POST["PREVIEW_TEXT"],
				"DETAIL_TEXT"	=> $_POST["DETAIL_TEXT"],
				"ACTIVE_FROM"	=> ConvertDateTime(date("d.m.Y H:i:s"), "d.m.Y H:i:s")
			);
		
			if (!$element->Add($new_artical_array)) echo "Ошибка: ".$element->LAST_ERROR;

			break;
	
		case 'delete':
			if (CIBlockElement::GetByID($_POST["ID"])->GetNext())
				CIBlockElement::Delete($_POST["ID"]);

			break;

		case 'edit':
			$isEditAction = true;
			$edited_article = CIBlockElement::GetByID($_POST["ID"])->GetNext();

			break;

		case 'commit_edits':
			$element = new CIBlockElement;
			$arFields = Array(
				"NAME"			=> $_POST["NAME"],
				"PREVIEW_TEXT"	=> $_POST["PREVIEW_TEXT"],
				"DETAIL_TEXT"	=> $_POST["DETAIL_TEXT"]
			);

			$element->Update($_POST["ID"], $arFields);

			break;
	}
}?>

<!-- Логика вывода новостей -->
<?
$arOrder = Array("ACTIVE_FROM" => "DESC"); # Сортировка по дате, по убыванию
$arSelect = Array("ID", "NAME", "ACTIVE_FROM", "PREVIEW_TEXT");
$arFilter = Array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "ACTIVE" => "Y");

$articles = CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);
?>

<!-- Предсталвение вывода элементов -->
<div>
<?if (!$articles->SelectedRowsCount()):?>
	<h3>Новостей пока нет</h3>
<?else:?>

	<!-- Навигация -->
	<?$articles->NavStart($arParams['NEWS_COUNT']);?>
	<?if ($arParams['NEWS_COUNT'] && $articles->SelectedRowsCount() > $arParams['NEWS_COUNT']):?>
		<?=$articles->NavPrint('Новости');?><br><br>
	<?endif;?>
	
	<!-- Верстка элементов -->
	<?while($article = $articles->GetNextElement()) {
		$arFields = $article->GetFields();

		# Получаю кнопки режима правки: редактирования и удаления
		$arButtons = CIBlock::GetPanelButtons($arParams["IBLOCK_ID"], $arFields["ID"]);
		$arFields["ADD_BUTTON"] = $arButtons["edit"]["add_element"];
		$arFields["EDIT_LINK"] = $arButtons["edit"]["edit_element"]["ACTION_URL"];
		$arFields["DELETE_LINK"] = $arButtons["edit"]["delete_element"]["ACTION_URL"];
		
		$this->AddEditAction($arFields['ID'], $arFields['ADD_BUTTON']["ACTION_URL"], $arFields['ADD_BUTTON']["TEXT"]);
		$this->AddEditAction($arFields['ID'], $arFields['EDIT_LINK'], CIBlock::GetArrayByID($arFields["IBLOCK_ID"], "ELEMENT_EDIT"));
		$this->AddDeleteAction($arFields['ID'], $arFields['DELETE_LINK'], CIBlock::GetArrayByID($arFields["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
		?>
		<p id="<?=$this->GetEditAreaId($arFields['ID']);?>">
			<a><b><?echo $arFields["NAME"]?></b></a><br>
			<span><?echo $arFields["ACTIVE_FROM"]?></span><br>
			<?echo $arFields["PREVIEW_TEXT"];?>

			<!-- Кнопки действий -->
			<div style="display: flex;">

				<!-- Форма перехода к редактированию статьи -->
				<form method="post" action="#bottom-form">
					<input name="action" value="edit" hidden>
					<input name="ID" value="<?echo $arFields["ID"]?>" hidden>
					<input type="submit" value="Редактировать">
				</form>

				<!-- Форма удаления статьи -->
				<form method="post">
					<input name="action" value="delete" hidden>
					<input name="ID" value="<?echo $arFields["ID"]?>" hidden>
					<input type="submit" value="Удалить">
				</form>

			</div>
		</p>
	<?} ?>

<?endif;?>
</div>

<!-- Форма добавления/редактирования статьи -->
<h2><?if ($isEditAction):?>Изменить<?else:?>Добавить<?endif;?> новость</h2>
<form method="post" id="bottom-form" action="#">
    <table style="border-collapse: separate; border-spacing: 10px;">
		<input name="action" value=<?if ($isEditAction):?>"commit_edits"<?else:?>"add"<?endif;?> hidden>
			<?if ($isEditAction):?><input name="ID" value="<?=$edited_article['ID']?>" hidden><?endif;?>
        <tr>
            <td>Название</td>
            <td><input type="text" name="NAME" size="61"
			<?if($isEditAction):?>value="<?=$edited_article["NAME"]?>"<?endif;?>
			required></td>
        </tr>
        <tr>
            <td>Анонс</td>
            <td><textarea name="PREVIEW_TEXT" id="" cols="60" rows="4" required><?if($isEditAction)echo $edited_article["PREVIEW_TEXT"];?></textarea></td>
        </tr>
        <tr>
            <td>Текст</td>
            <td><textarea name="DETAIL_TEXT" id="" cols="60" rows="4" required><?if($isEditAction)echo $edited_article["DETAIL_TEXT"];?></textarea></td>
        </tr>
        <tr>
            <td><button type="reset">Отмена</button></td>
            <td><input type="submit" value=<?if ($isEditAction):?>"Cохранить"<?else:?>"Добавить"<?endif;?>></td>
        </tr>
    </table>
</form>
