<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
CModule::IncludeModule("iblock");

$dbIBlock = CIBlock::GetList(
    array("SORT"=>"ASC"),
    array("ACTIVE" => "Y")
 );

 $arIBlockResult = array();
 while ($arIBlock = $dbIBlock->Fetch())
 {
    $arIBlockResult[$arIBlock["ID"]] = "[".$arIBlock["ID"]."] ".$arIBlock["NAME"];
 }
 
 $arComponentParameters = array(
    "GROUPS" => array(),
    "PARAMETERS" => array(
        "IBLOCK_ID" => array(
            "PARENT" => "BASE",
            "NAME" => "Инфоблок",
            "TYPE" => "LIST",
            "ADDITIONAL_VALUES" => "N",
            "VALUES" => $arIBlockResult,
        ),
        "NEWS_COUNT" => array(
            "PARENT" => "BASE",
            "NAME" => "Кол-во на странице",
            "TYPE" => "STRING",
        )
    )
 );

?>