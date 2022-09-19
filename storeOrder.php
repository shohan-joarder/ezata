<?php

require 'vendor/autoload.php';
// check post request

use Parse\ParseObject;
use Parse\ParseClient;
use Parse\ParseQuery;

ParseClient::initialize("9ONzVfqhJ8XvsDSthjVg6cf86Gg1I1HD4RIue3VB", "yg51KKzO3QMgw8brdP1FETmTerNDB4MKTEH9HneI", "I82wQlOUEAXSlG5EspgatZvJfWJlqnnusfvB0tI8");

ParseClient::setServerURL('https://parseapi.back4app.com', '/');
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $response = [
        "success" => false,
        "message" => "Something went wrong"
    ];

    // echo($_SESSION["userObjId"]); die;

    $billingTime = $_POST['billingTime'];
    $deliveryType = $_POST['deliveryType'];
    $address = $_POST['address'];
    $cartData =$_SESSION['cart'];
    $orderId = '';
    $order = new ParseObject("Orders");
    $order->set("deliveryOption", $deliveryType);
    $order->set("orderStatus", "ACTIVE");
    $order->set("source", "Web");
    $store = new ParseObject("Store", current($cartData)["store_id"]);
    $order->set("store", $store);

    $user = new ParseObject("_User", $_SESSION["userObjId"]);
    $order->set("userOrdered", $user);
    // die;
    $qty = 0;
    $total = 0;
    foreach ($cartData as $key => $value) {
        $qty += intval($value['qty']);
        $total += intval($value['price']);
    }
    $order->set("quantity", $qty);
    $order->set("amount", $total);
    $order->set("orderNumber", "W".mt_rand(1000,9999));
    try {
        $order->save();
        $orderId = $order->getObjectId();
        $response = [
            "success" => true,
            "message" => "Data insert into order table",
            "orderId" => $order->getObjectId()
        ];
        $orderId = $order->getObjectId();
        if($orderId){
            if(count($cartData)>0){
                $i = 0;
                foreach ($cartData as $key => $item) {
                    $extraItems = $item["extra_item"];

                    $orderMenu = new ParseObject("OrdersMenu");
                    $parentCategoryInfo = new ParseObject("Categories", $item["category_id"]);
                    $orderMenu->set("ParentCategory", $parentCategoryInfo);
                    // echo $orderId;die;
                    $orderInfo = new ParseObject("Orders", $orderId);
                    $orderMenu->set("orderId", $orderInfo);
                    $orderMenu->set("price", intval($item["price"]));
                    $orderMenu->set("quantity", intval($item["qty"]));
                    $orderMenu->set("cartItemNo", $i+1);

                    $orderMenuInfo = new ParseObject("Menu", $item["id"]);
                    $orderMenu->set("menuItemId", $orderMenuInfo);
                    // try {
                    // if(
                        $orderMenu->save();

                        if($extraItems){
                            $extraItemArr = explode(",", $extraItems);
                            // die(print_r($extraItemArr));
                            foreach ($extraItemArr as $key => $extraItem) {
                                // get data from MenuExtraItem table using name
                                $menuExtraItem = new ParseQuery("MenuExtraItem");
                                $menuExtraItem->equalTo("name", $extraItem);
                                $menuExtraItem = $menuExtraItem->first();


                                $extraItemObj = new ParseObject("OrderMenuExtraItem");

                                if($menuExtraItem):
                                $menuExtraItemId = $menuExtraItem->getObjectId();
                                $menuExtraItemGroup = new ParseObject("MenuExtraGroup", $menuExtraItem->get("parentGroup")->getObjectId());
                                $extraItemObj->set("ParentMenuExtraGroup", $menuExtraItemGroup);    
                                else:
                                    $menuExtraItemId = '';
                                endif;

                                

                                $orderMenuInfo = new ParseObject("OrdersMenu", $orderMenu->getObjectId());
                                $extraItemObj->set("ParentOrderMenu", $orderMenuInfo);

                                $parentMenuExtraItem = new ParseObject("MenuExtraItem", $menuExtraItemId);
                                $extraItemObj->set("ParentMenuExtra", $parentMenuExtraItem);

                                $extraItemObj->set("price", intval($menuExtraItem->get("price")));

                                $extraItemObj->save();
                                $response = 
                                [
                                    "success" => true,
                                    "message" => "Order menu extra item added successfully",
                                    "orderMenuId" => $extraItemObj->getObjectId(),
                                    "orderId"=>$orderId
                                ];
                            }
                        }
                    $i++;
                }

                if($i == count($cartData)){
                    $response = [
                        "success" => true,
                        "message" => "Order place successfully",
                        "orderMenuId" => $orderMenu->getObjectId(),
                        "orderId"=>$orderId
                    ];
                    // forgot session here
                    unset($_SESSION['cart']);

                }
            }
          }

      } catch (ParseException $ex) {  
        $response = [
            "success" => false,
            "message" => $ex->getMessage()
        ];
      }
    echo json_encode($response);
}
?>