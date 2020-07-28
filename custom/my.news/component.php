<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

CModule::IncludeModule("iblock");

# Обработка запросов
if ($_POST && array_key_exists("action", $_POST)) {
	$arResult["isEditAction"] = $_POST["action"] == 'edit';
	$element = new CIBlockElement;

	switch ($_POST["action"]) {
		case 'add':
			$element->Add(
				Array(
					"IBLOCK_ID" 	=> $arParams["IBLOCK_ID"],
					"NAME"			=> $_POST["NAME"],
					"ACTIVE"		=> "Y",
					"PREVIEW_TEXT"	=> $_POST["PREVIEW_TEXT"],
					"DETAIL_TEXT"	=> $_POST["DETAIL_TEXT"],
					"ACTIVE_FROM"	=> ConvertDateTime(date("d.m.Y H:i:s"), "d.m.Y H:i:s")
				)
			);

			break;
	
		case 'delete':
			if (CIBlockElement::GetByID($_POST["ID"])->GetNext()) CIBlockElement::Delete($_POST["ID"]);

			break;

		case 'edit':
			$arResult["EDITED_ITEM"] = CIBlockElement::GetByID($_POST["ID"])->GetNext();

			break;

		case 'commit_edits':
			$element->Update(
				$_POST["ID"], 
				Array(
					"NAME"			=> $_POST["NAME"],
					"PREVIEW_TEXT"	=> $_POST["PREVIEW_TEXT"],
					"DETAIL_TEXT"	=> $_POST["DETAIL_TEXT"]
				)
			);

			break;
	}
}

# Логика получения нововстей из БД
$arIBlockResult = CIBlockElement::GetList(
	Array("ACTIVE_FROM" => "DESC"), 
	Array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "ACTIVE" => "Y"), 
	false, 
	false, 
	Array("ID", "NAME", "ACTIVE_FROM", "PREVIEW_TEXT")
);

# Навигация
$arIBlockResult->NavStart($arParams['NEWS_COUNT']);
$arResult["SelectedRowsCount"] = $arIBlockResult->SelectedRowsCount();
$arResult["NavPrint"] = $arIBlockResult->NavPrint('Новости');

$arResult["ITEMS"] = Array();
while($arItem = $arIBlockResult->GetNext()) {

    # Получаю кнопки режима правки(добавления, редактирования и удаления)
    $arButtons = CIBlock::GetPanelButtons($arParams["IBLOCK_ID"], $arItem["ID"]);
    $arItem["ADD_BUTTON"] = $arButtons["edit"]["add_element"];
    $arItem["EDIT_LINK"] = $arButtons["edit"]["edit_element"];
    $arItem["DELETE_LINK"] = $arButtons["edit"]["delete_element"];

    $arResult["ITEMS"][] = $arItem;
}

$this->IncludeComponentTemplate();
?>