<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die(); 
$arComponentDescription = array(
    "NAME" => GetMessage("Новости"),
    "DESCRIPTION" => GetMessage("Вывод, добавление, редактирование, удаление новостей"),
    "PATH" => array(
        "ID" => "my_news",
        "CHILD" => array(
            "ID" => "IBLOCK_ID",
            "NAME" => "ID Инфоблока"
        )
    ),
);
?>