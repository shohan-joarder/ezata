<?php
require 'vendor/autoload.php';

use Parse\ParseQuery;
use Parse\ParseException;
use Parse\ParseObject;
use Parse\ParseACL;
use Parse\ParsePush;
use Parse\ParseUser;
use Parse\ParseInstallation;
use Parse\ParseAnalytics;
use Parse\ParseFile;
use Parse\ParseCloud;
use Parse\ParseClient;
use Parse\ParseGeoPoint;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

ParseClient::initialize("9ONzVfqhJ8XvsDSthjVg6cf86Gg1I1HD4RIue3VB", "yg51KKzO3QMgw8brdP1FETmTerNDB4MKTEH9HneI", "I82wQlOUEAXSlG5EspgatZvJfWJlqnnusfvB0tI8");
ParseClient::setServerURL('https://parseapi.back4app.com', '/');

$ItemId = $_POST['id'];
$categoryObject = $_POST["catobj"];
$categoryTitle = $_POST["cattitle"];
$storeId = $_POST["storeid"];
$storeName = $_POST["storename"];
$price = $_POST["price"];

$menuQuery = new ParseQuery("Menu");
$menuQuery->equalTo("objectId", $ItemId);

$menuExtraGroupQuery = new ParseQuery("MenuExtraGroup");
$menuExtraGroupQuery->matchesQuery("parentMenu", $menuQuery);
try {
    $menuExtraGroupItems = $menuExtraGroupQuery->find();
} catch (ParseException $ex) {
    echo $ex->getMessage();
    die;
}
$data = [];
$data["sauchitem"] ='';
$sauchitem = '';
$sauch='';
$myscript = [];
foreach ($menuExtraGroupItems as $k => $menuGroupItemValue):
$menuGroupItemID = $menuGroupItemValue->getObjectId();
$maxSelectableItems = $menuGroupItemValue->get('maxSelectableItems');

    if ($maxSelectableItems == 1) {
        $selectableType = "radio";
    } else {
        $selectableType = "checkbox";
    }
    $sauch .= '<ul style="list-style:none;padding-inline-start: 0px; margin-bottom:0;">
    <li style=" border-bottom: solid 1px #f5f5f5; padding-bottom: 5px; margin-bottom: 8px;margin-top: 8px ">
        <span><b>'. ucwords($menuGroupItemValue->get('title')).'</b> - '. $menuGroupItemValue->get('groupText') .'<span><span style="float: right;color: #8c8787f7;">REQUIRED</span>
    </li>
    </ul>';

    $menuExtraGroupQuery = new ParseQuery("MenuExtraGroup");
    $menuExtraGroupQuery->equalTo("objectId", $menuGroupItemID);
    $menuExtraItemQuery = new ParseQuery("MenuExtraItem");
    $menuExtraItemQuery->matchesQuery("parentGroup", $menuExtraGroupQuery);
    try {
        $menuExtraItems = $menuExtraItemQuery->find();
    } catch (ParseException $ex) {
        echo $ex->getMessage();
    }
    $sauch .=' <div class="container"><div class="row">';
    foreach ($menuExtraItems as $menuExtraItemsIndex => $menuExtraItemsValue):
        $menuExtraGroupItemID = $menuExtraItemsValue->getObjectId();
        $id = "'" . $menuExtraGroupItemID . "'";
        $idd = "'" . $ItemId . "'";
        $extra = "'" . $menuExtraItemsValue->get('name') . "'";
        $name = "'" . ucwords($menuExtraItemsValue->get('name')) . "'";
        $sauch .= '<div class="col-12" style="margin-top:8px;margin-bottm:8px"><span style="font-size:14px">' . ucwords($menuExtraItemsValue->get('name')) . '&nbsp;|&nbsp;Price $' . $menuExtraItemsValue->get('price') . '</span>
            <input onChange="get_total(' . $idd . ',' . $name . ')"  data-in="num_' . $ItemId . '" value="' . ucwords($menuExtraItemsValue->get('name')) . '|' . $menuExtraItemsValue->get('price') . '"  id="check_id_' . $menuExtraGroupItemID . '" type="' . $selectableType . '" name="' . $menuGroupItemID . '" 
            style="float:right;height:18px;width:18px" class="switch menu-check checkbox-' . $menuGroupItemID . '" data-id="'.$menuGroupItemID.'" data-maxtableitem="'.$maxSelectableItems.'"></div>';
    endforeach;
    $sauch .='</div></div>';
endforeach;

//$data["addtocart"] = '<div class="col-5"><div class="input-group"><input type="number" style="border-radius: 25px;border: 1px solid black;" class="form-control qty" id="qty_'.$ItemId.'" value="1" min="0"></div></div><div class="col-7"><b href="javascript:void(0)" id="cart_btn2_'.$ItemId.'" onclick="console.log(1);return false;">add</b></div>';


$data["addtocart"] =<<<EOD
<div class="col-5"><div class="input-group"><input type="number" style="border-radius: 25px;border: 1px solid black;" class="form-control qty" id="qty_$ItemId" value="1" min="0"></div></div>

<div class="col-7">
    <button type="button" class="add_to_cart" onClick="add_to_cart('$ItemId','$categoryObject','$categoryTitle',0,'$storeId','$storeName')" style="border:none !important">
                                    <span style="float: left"> <i
                                            id="cart_btn_$ItemId"
                                            class=""></i> ADD TO CART</span>
        <span style="float: right"
                id="item_price_html_$ItemId">$$price</span>
    </button>
</div>
EOD;


$data["sauchitem"] = $sauch;
echo json_encode($data);die;
?>
