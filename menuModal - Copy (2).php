<?php
use Parse\ParseQuery;
use Parse\ParseException;
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$menuQuery = new ParseQuery("Menu");
$menuQuery->equalTo("objectId", $ItemId);

$menuExtraGroupQuery = new ParseQuery("MenuExtraGroup");
$menuExtraGroupQuery->matchesQuery("parentMenu", $menuQuery);
try {
    $menuExtraGroupItems = $menuExtraGroupQuery->find();
} catch (ParseException $ex) {
    echo $ex->getMessage();
}
?>

<div class="modal fade" tabindex="-1" id="myModal_<?= $ItemId ?>" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="post">
                <div class="modal-body">
                    <button type="button" class="close" style="float:right" data-dismiss="modal">&times;</button>
                    <br><br>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="col-lg-12 img-thumbnail" style="padding-top: 100px;padding-bottom: 100px;margin:auto;">
                                <img src="<?= $imageURL ?>" class="img-fluid">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <h3 style="color:#dc3545;font-size: 25px;"><?= ucwords($allItem["dishTitle"]) ?></h3>
                            <hr style="margin-top:0">
                            <p style="font-size:15px;"><?= nl2br($allItem["dishDescription"]) ?> </p>
                            <hr>
                            <div style="height:200px;  overflow:auto; line-height: 20px;">
                                <?php
                                foreach ($menuExtraGroupItems as $k => $menuGroupItemValue):
                                $menuGroupItemID = $menuGroupItemValue->getObjectId();
                                $maxSelectableItems = $menuGroupItemValue->get('maxSelectableItems');

                                if ($maxSelectableItems == 1) {
                                    $selectableType = "radio";
                                } else {
                                    $selectableType = "checkbox";
                                }
                                ?>
                                   <ul style="list-style:none;padding-inline-start: 0px; margin-bottom:0;">
                                        <li style=" border-bottom: solid 1px #f5f5f5; padding-bottom: 5px; margin-bottom: 8px;margin-top: 8px ">
                                            <span><b><?= ucwords($menuGroupItemValue->get('title')) ?></b> - <?= $menuGroupItemValue->get('groupText') ?><span><span style="float: right;color: #8c8787f7;">REQUIRED</span>
                                        </li>
                                    </ul>
                                    <?php
                                    $menuExtraGroupQuery = new ParseQuery("MenuExtraGroup");
                                    $menuExtraGroupQuery->equalTo("objectId", $menuGroupItemID);
                                    $menuExtraItemQuery = new ParseQuery("MenuExtraItem");
                                    $menuExtraItemQuery->matchesQuery("parentGroup", $menuExtraGroupQuery);
                                    try {
                                        $menuExtraItems = $menuExtraItemQuery->find();
                                    } catch (ParseException $ex) {
                                        echo $ex->getMessage();
                                    }
                                    ?>

                                    <div class="container">
                                        <div class="row">
                                            <?php
                                            foreach ($menuExtraItems as $menuExtraItemsIndex => $menuExtraItemsValue):
                                                $menuExtraGroupItemID = $menuExtraItemsValue->getObjectId();
                                                $idd = "'" . $menuExtraGroupItemID . "'";
                                                $extra = "'" . $menuExtraItemsValue->get('name') . "'";
                                                $name = "'" . ucwords($menuExtraItemsValue->get('name')) . "'";
                                                echo '<div class="col-12" style="margin-top:8px;margin-bottm:8px"><span style="font-size:14px">' . ucwords($menuExtraItemsValue->get('name')) . '&nbsp;|&nbsp;Price $' . $menuExtraItemsValue->get('price') . '</span>
                                                    <input onChange="get_total(' . $id . ',' . $name . ')"  data-in="num_' . $ItemId . '" value="' . ucwords($menuExtraItemsValue->get('name')) . '|' . $menuExtraItemsValue->get('price') . '"  id="check_id_' . $menuExtraGroupItemID . '" type="' . $selectableType . '" name="' . $menuGroupItemID . '" 
                                                    style="float:right;height:18px;width:18px" class="switch checkbox-' . $menuGroupItemID . '"></div>';
                                            endforeach;
                                            //end menu items loop
                                            ?>
                                        </div>
                                    </div>

                                <?php
                                if ($selectableType == "checkbox") {
                                    echo '<script>                                                                                   
                                    $(document).ready(function() {
                                    $(document).on("change","input.checkbox-' . $menuGroupItemID . '", function(evt) {
                                        if($(this).siblings(":checked").length >= ' . $maxSelectableItems . ') {
                                        this.checked = false;
                                        }
                                    }); 
                                    }); 
                                    </script>';
                                    }
                                endforeach;
                                ?>
                            </div>
                            <br>
                            <div class="container">
                                <div class="row" style="">
                                    <div class="col-5">
                                        <div class="input-group">
                                            <input type="number"
                                                   style="border-radius: 25px;border: 1px solid black;"
                                                   class="form-control qty"
                                                   id="qty_<?= $ItemId ?>" value="1"
                                                   min="0">

                                        </div><!--input group-->
                                    </div>
                                    <div class="col-7">
                                        <a href="#" id="cart_btn2_<?= $ItemId ?>"
                                           onClick="add_to_cart('<?= $ItemId ?>','<?= $categoryObject ?>','<?= $categoryTitle ?>',0,'<?= $storeId ?>','<?= $storeName ?>')">
                                            <button class="add_to_cart" style="border:none !important">
                                                <span style="float: left"><i id="cart_btn_<?= $ItemId ?>"class=""></i>
                                                    ADD TO CART
                                                </span>
                                                <span style="float: right" id="item_price_html_<?= $ItemId ?>">$<?= $allItem["price"] ?>
                                                </span>
                                            </button>
                                        </a>
                                    </div>
                                </div><!--row-->
                            </div>

                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>