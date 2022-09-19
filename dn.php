<?php


require 'vendor/autoload.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use Parse\ParseObject;
use Parse\ParseQuery;
use Parse\ParseACL;
use Parse\ParsePush;
use Parse\ParseUser;
use Parse\ParseInstallation;
use Parse\ParseException;
use Parse\ParseAnalytics;
use Parse\ParseFile;
use Parse\ParseCloud;
use Parse\ParseClient;
use Parse\ParseGeoPoint;


$MarkersHTML = '';
$output = '';
$storeName = '';

ParseClient::initialize("9ONzVfqhJ8XvsDSthjVg6cf86Gg1I1HD4RIue3VB", "yg51KKzO3QMgw8brdP1FETmTerNDB4MKTEH9HneI", "I82wQlOUEAXSlG5EspgatZvJfWJlqnnusfvB0tI8");
ParseClient::setServerURL('https://parseapi.back4app.com', '/');

$storeId = $_GET["Id"];
$storeQuery = new ParseQuery("Store");
$storeQuery->equalTo("objectId", $storeId);
$store = [] ;
$manicatagory = [];
$mymenuresult = [];
$data =[];
//$storeQuery->equalTo('user','ySHH4KuNZx');
try {
    //$storeObject = $storeQuery->get($storeId);
    $storeObject = $storeQuery->first();
    $store["storeName"] = $storeObject->get("name");
    $store["address"] = $storeObject->get("address");
    $store["storeImageUrl"] = $storeObject->get("storeImageUrl");
    $store["openingHours"] = $storeObject->get("openingHours");
    $store["phoneNumber"] = $storeObject->get("phoneNumber");
    $store["emailAddress"] = $storeObject->get("emailAddress");
    $store["coordinate"] = $storeObject->get("coordinate");
    $store["storeDescription"] = $storeObject->get("storeDescription");
    $store["website"] = $storeObject->get("website");
    $store["priceRate"] = $storeObject->get("price_rate");
    $store["headline"] = $storeObject->get("headline");
    $store["reviewsCount"] = $storeObject->get("reviews_count");
    $store["authorImage"] = $storeObject->get("author_image");
    $store["authorImageUrl"] = $storeObject->get("author_image")->getURL();
    $store["authorCountry"] = $storeObject->get("author_country");
    $store["amenities"] = $storeObject->get("amenities");
    $store["authorName"] = $storeObject->get("author_name");
    $store["storeRating"] = $storeObject->get("store_rating");

    $storeQuery = new ParseQuery("Store");
    $storeQuery->equalTo("objectId", $storeId);
    $cQuery = new ParseQuery("Categories");
    $cQuery->matchesQuery("ParentStore", $storeQuery);
    try {
        $results = $cQuery->find();
    } catch (ParseException $ex) {
        echo $ex->getMessage();
    }
    
    $i=0;
    foreach ($results as $result){

        $categoryObject = $result->getObjectId();
        $cQuery = new ParseQuery("Categories");
        $cQuery->equalTo("objectId", $categoryObject);

        $menuQuery = new ParseQuery("Menu");
        $menuQuery->matchesQuery("ParentCategory", $cQuery);

        try {
            $menuResult = $menuQuery->find();
        } catch (ParseException $ex) {
            echo $ex->getMessage();
        }
        $a=0;
        foreach ($menuResult as $key => $value) {
            $mymenuresult[$a]["ItemId"] =$value->getObjectId();
            $mymenuresult[$a]["id"] = "'" . $value->getObjectId() . "'";
            $mymenuresult[$a]["dishImage"] = $value->get('dish_image');
            $mymenuresult[$a]["dishImageurl"] = ($value->get('dish_image') != null) ? $value->get('dish_image')->getURL() : 'https://parsefiles.back4app.com/gg2zjiKqqH9hpSXWVSoXv8ZG6aJ1CA9ImhTO0bEd/ffd34653efced509c2df5f0e2241b909_bg-1.jpg';
            $mymenuresult[$a]["dishTitle"] = $value->get('dishTitle');

            $mymenuresult[$a]["dishDescription"] = $value->get('dishDescription');

            $mymenuresult[$a]["price"] = $value->get('price');
            $a++;
        }
        $manicatagory[$i]["object_id"] = $result->getObjectId();
        $manicatagory[$i]["title"] = $result->get("itemTitle");
        $manicatagory[$i]["result"] =  $mymenuresult;
            
        $i++;
    }

    $data["store"] = $store;
    $data["manicatagory"] = $manicatagory;

    $sites = realpath(dirname(__FILE__)).'/'.'jsonproduct'.'/';
    $newfile = $sites.$storeId.".json";

    if (! file_exists($newfile)) {
        $fh = fopen($newfile, 'wb');
        fwrite($fh, json_encode($data));
        fclose($fh);
    }
        $fh = file_get_contents($newfile);
        $mydata = json_decode($fh,true);
        $mystoredata = $mydata["store"];


        foreach ($mystoredata as $key => $value) {
            $$key = $value;
        }
        // 

} catch (ParseException $ex) {
// The object was not retrieved successfully.
// error is a ParseException with an error code and message.
}

include 'header.php';
?>


<div class="container store_cover_container">
    <div class="row">
        <div class="col-md-12">
            <div class="store_cover">
                <img src="images/storeCover.jpg" class="img-fluid" alt="#">
            </div>
        </div>
    </div>
</div>

<!--//END BOOKING -->
<!--============================= RESERVE A SEAT =============================-->
<section class="reserve-block">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="store_logo">
                    <img src="images/StoreLogo.jpg" class="img-fluid" alt="#">
                </div>
                <div class="store_meta">
                    <span class="store_category_sec">
                        <a href="#" style="margin-right:5px;float:left;margin-bottom:3px;text-decoration:none;" class="category_list">Breakfast&nbsp,</a>
                        <a href="#" style="margin-right:5px;float:left;margin-bottom:3px;text-decoration:none;" class="category_list">Family Meals&nbsp.</a>
                    </span>
                    <span class="store_rating"><?php echo $storeRating ?> <span class="icon-star"></span></span>
                    <span class="store_review_count">(<?php echo $reviewsCount ?> reviews)</span>
                    <span class="store_distance">4.6 k.m.</span>
                    <span><span>$</span></span>
                </div>
                <h5 class="store_name" style="display:block;margin-bottom:10px;"><?php echo ucwords($storeName) ?> </h5> 
                <div class="store_meta_info_sec">
                    <span class="store_meta_info">
                        <span class="icon-location-pin"></span>
                        <p> <?php echo nl2br($address) ?></p>
                    </span>
                    <span class="store_meta_info">
                        <span class="icon-screen-smartphone"></span>
                        <p> <?php echo $phoneNumber ?></p>
                    </span>
                </div>
            </div>
        </div>
    </div>
</section>
<!--//END RESERVE A SEAT -->
<!--============================= BOOKING DETAILS =============================-->

<section class="search_section">
    <div class="container">
        <div class="row" style="">
            <div class="col-md-6">
                Menu
            </div>
            <div class="col-md-6">
                <div class="form-group has-search">
                    <span class="fa fa-search form-control-feedback"></span>
                    <input type="search" class="form-control" placeholder="Search" aria-label="Search">
                </div>
            </div>
        </div>
    </div>
</section>

<section class="menu_list_sec">
    <div class="container">
        <div class="row">
            <div class="col-md-12 menu_list_holder">
                <span class="icon-list"></span>
                <div class="swiper-container">
                    <div class="swiper-wrapper ">
                    
                        <?php 
                        foreach ($mydata["manicatagory"] as $result):
                            $maincategoryObjectId = $result["object_id"];
                            $maincategoryTitle = $result["title"]
                         ?>
                        <a href="#<?php echo $maincategoryObjectId ?>" class="swiper-slide" style=""><?php echo $maincategoryTitle ?></a>
                        <?php 
                            endforeach; 
                        ?>
                        
                    </div>
                </div>
                <!-- If we need navigation buttons -->
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
                <!-- <button id="toggle"><span class="icon-arrow-right"></span></button> -->
            </div>
        </div>  
    </div>
</section>

<section class="booking-details_wrap booking-details_wrap_new">
    <div class="container" style="padding:0 30px">
        <div class="row">
            <div class="col-md-12 responsive-wrap px-0">

                <?php 
                 
                foreach ($mydata["manicatagory"] as $result):
                    $categoryObject = $result["object_id"];
                    $categoryTitle = $result["title"];
                    $menubyitem = "";
                    
                    ?>
                    <div class="cat_content_box">
                    <div id='<?= $categoryObject?>' style='padding:0px;'></div><div class='alert alert-info cat_box_title' style='color: #ff3a6d; background-color: #fff;border: 2px solid #ff3a6d85;'> <!-- <span class='icon-directions'></span> --> <?=$categoryTitle?></div>
                    <div class="row clearfix menu_box_holder">
                        <?php
                        $x=0;
                        $menuItem = '';
                        foreach ($result["result"] as $key=>$allItem):
                            $ItemId = $allItem["ItemId"];
                            $id = $allItem["id"];
                            $dishImage = $allItem["dishImage"];
                            $imageURL = $allItem["dishImageurl"];
                            $menuItem .='<div class="col-md-6">
                                            <a data-toggle="modal" id="'.$ItemId.'" data-distitle="'.ucwords($allItem["dishTitle"]).'" data-dishdescription="'.nl2br($allItem["dishDescription"]).'" data-itemImg="'.$imageURL.'" data-target="#myModal_'.$ItemId.'" class="showMymodal" data-catobj="'. $categoryObject.'" data-cattitle="'.$categoryTitle.'" data-storeid="'.$storeId.'" data-storename="'.$storeName.'" data-price="'.$allItem['price'].'">
                                                <div class="row featured-place-wrap menu_item_box" style="margin-top:50px;">   
                                                    <div class="col-md-8 item_box_left" style="padding:0px;">
                                                        <div class="featured-title-box">
                                                            <h6 style="margin-bottom:20px;">'.ucwords($allItem["dishTitle"]).' Menu</h6>
                                                            <!--<p>Dish '.$allItem["dishTitle"].' </p> <span>• </span> -->
                                                            <p class="menu_item_desc">Two egs* your way, 2 custom cured hickory-smoked bacon strips, 2 pork, 2 custom cured hickory-smoked bacon strips, 2 pork</p>
                                                            <p class="menu_price">$'.$allItem['price'].'</p>
                                                            <input type="hidden" id="item_title_'.$ItemId.'"  value="'.$allItem['dishTitle'].'">
                                                            <input  type="hidden"  id="item_price_'.$ItemId.'" value="'.$allItem['price'].'">

                                                            <input  type="hidden"  id="item_id_'.$ItemId.'" value="'.$ItemId.'">
                                                            <input  type="hidden"  id="item_pic_'.$ItemId.'" value="'.$imageURL.'">
                                                            <input  type="hidden"  id="gtotal_'.$ItemId.'" value="'.$allItem['price'].'">
                                                            <input  type="hidden"  id="extraitem_'.$ItemId.'" value="">

                                                            <!--<div class="bottom-icons">
                                                                    <div class="closed-now">
                                                                    <button data-toggle="modal" data-target="#myModal_'.$ItemId.'"  class="btn btn-outline-danger btn-contact " style="padding:6px;margin-left: 0px;">VIEW DETAILS</button>
                                                                    </div>
                                                                    <span class="ti-heart"></span>
                                                                    <span class="ti-bookmark"></span>
                                                            </div> -->

                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 featured-responsive item_box_image" style="padding:0px; margin:0px;">
                                                        <img src="'. $imageURL.'" class="img-fluid" alt="'.$allItem['dishTitle'].'">
                                                    </div>
                                                </div>
                                            </a>
                                        </div>';
                                    ?>
                                    <!-- Modal -->

                                    <?php  // require'./menuModal - Copy (2).php'; ?>
                                    <!-- Modal -->
                                <?php    
                                endforeach;
                                echo $menuItem;
                            ?>
                        </div>
                    </div>
                <?php endforeach;?>

                <div class="booking-checkbox_wrap d-none">
                    <div class="booking-checkbox">
                        <p><?php echo nl2br($storeDescription) ?></p>
                        <hr>
                    </div>
                    <div class="row">
                        <?php foreach ($amenities as $index => $amenity): ?>
                            <div class="col-md-4">
                                <label class="custom-checkbox">
                                    <span class="ti-check-box"></span>
                                    <span class="custom-control-description"><?php echo ucwords($amenity); ?></span>
                                </label>
                            </div>
                        <?php endforeach;
                        fclose($fh);
                        ?>
                    </div>
                </div>
                <div class="booking-checkbox_wrap mt-4 d-none">
                    <h5>34 Reviews</h5>
                    <hr>
                    <div class="customer-review_wrap">
                        <div class="customer-img">
                            <img src="images/customer-img1.jpg" class="img-fluid" alt="#">
                            <p>Amanda G</p>
                            <span><?php echo $reviewsCount ?> Reviews</span>
                        </div>
                        <div class="customer-content-wrap">
                            <div class="customer-content">
                                <div class="customer-review">
                                    <h6>Best noodles in the Newyork city</h6>
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                    <span class="round-icon-blank"></span>
                                    <p>Reviewed 2 days ago</p>
                                </div>
                                <div class="customer-rating">8.0</div>
                            </div>
                            <p class="customer-text">I love the noodles here but it is so rare that I get to come here.
                                Tasty Hand-Pulled Noodles is the best type of whole in the wall restaurant. The staff
                                are really nice, and you should be seated quickly. I usually get the
                                hand pulled noodles in a soup. House Special #1 is amazing and the lamb noodles are also
                                great. If you want your noodles a little chewier, get the knife cut noodles, which are
                                also amazing. Their dumplings are great
                                dipped in their chili sauce.
                            </p>
                            <p class="customer-text">I love how you can see into the kitchen and watch them make the
                                noodles and you can definitely tell that this is a family run establishment. The prices
                                are are great with one dish maybe being $9. You just have to remember
                                to bring cash.
                            </p>
                            <ul>
                                <li><img src="images/review-img1.jpg" class="img-fluid" alt="#"></li>
                                <li><img src="images/review-img2.jpg" class="img-fluid" alt="#"></li>
                                <li><img src="images/review-img3.jpg" class="img-fluid" alt="#"></li>
                            </ul>
                            <span>28 people marked this review as helpful</span>
                            <a href="#"><span class="icon-like"></span>Helpful</a>
                        </div>
                    </div>
                    <hr>
                    <div class="customer-review_wrap">
                        <div class="customer-img">
                            <img src="images/customer-img2.jpg" class="img-fluid" alt="#">
                            <p>Kevin W</p>
                            <span>17 Reviews</span>
                        </div>
                        <div class="customer-content-wrap">
                            <div class="customer-content">
                                <div class="customer-review">
                                    <h6>A hole-in-the-wall old school shop.</h6>
                                    <span class="customer-rating-red"></span>
                                    <span class="round-icon-blank"></span>
                                    <span class="round-icon-blank"></span>
                                    <span class="round-icon-blank"></span>
                                    <span class="round-icon-blank"></span>
                                    <p>Reviewed 3 months ago</p>
                                </div>
                                <div class="customer-rating customer-rating-red">2.0</div>
                            </div>
                            <p class="customer-text">The dumplings were so greasy...the pan-fried shrimp noodles were
                                the same. So much oil and grease it was difficult to eat. The shrimp noodles only come
                                with 3 shrimp (luckily the dish itself is cheap) </p>
                            <p class="customer-text">The beef noodle soup was okay. I added black vinegar into the broth
                                to give it some extra flavor. The soup has bok choy which I liked - it's a nice textural
                                element. The shop itself is really unclean (which is the case
                                in many restaurants in Chinatown) They don't wipe down the tables after customers have
                                eaten. If you peak into the kitchen many of their supplies are on the ground which is
                                unsettling... </p>
                            <span>10 people marked this review as helpful</span>
                            <a href="#"><span class="icon-like"></span>Helpful</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 responsive-wrap d-none" style="margin-top:50px;">
                <div class="contact-info">
                    <img src="images/map.jpg" class="img-fluid" alt="#">
                    <div class="address">
                        <span class="icon-location-pin"></span>
                        <p> <?php echo nl2br($address) ?></p>
                    </div>
                    <div class="address">
                        <span class="icon-screen-smartphone"></span>
                        <p> <?php echo $phoneNumber ?></p>
                    </div>
                    <div class="address">
                        <span class="icon-link"></span>
                        <p> <?php echo $website ?></p>
                    </div>
                    <div class="address">
                        <span class="icon-clock"></span>
                        <p><?php echo $openingHours[0]; ?> <br> <?php echo $openingHours[1]; ?> </p>
                    </div>
                    <a href="#" class="btn btn-outline-danger btn-contact">SEND A MESSAGE</a>
                </div>
                <!--  -->
                <div class="follow">
                    <div class="follow-img">
                        <img src="<?php echo $authorImageUrl ?>" class="img-fluid"
                             alt="<?php echo $authorName ?>">
                        <h6><?php echo $authorName ?></h6>
                        <span><h6><?php echo $authorCountry ?></h6></span>
                    </div>
                    <ul class="social-counts">
                        <li>
                            <h6>26</h6>
                            <span>Listings</span>
                        </li>
                        <li>
                            <h6>326</h6>
                            <span>Followers</span>
                        </li>
                        <li>
                            <h6>12</h6>
                            <span>Followers</span>
                        </li>
                    </ul>
                    <a href="#">FOLLOW</a>
                </div>
                <!--  -->
            </div>
        </div>
    </div>
</section>

<!--//END BOOKING DETAILS -->


<!-- Modal start -->
<div class="modal fade" tabindex="-1" id="productDetailsModal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="post">
                <div class="modal-body">
                    <button type="button" class="close" style="float:right" data-dismiss="modal">&times;</button>
                    <br><br>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="col-lg-12 img-thumbnail" style="padding-top: 100px;padding-bottom: 100px;margin:auto;">
                                <img src="" class="img-fluid modal_img">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <h3 style="color:#dc3545;font-size: 25px;" class="dish_title"></h3>
                            <hr style="margin-top:0">
                            <p style="font-size:15px;" class="dish_description"></p>
                            <hr>

                            <div style="height:200px;  overflow:auto; line-height: 20px;" class="sauchitem">

                            </div>
                            <br>

                            <div class="container">
                                <div class="row addtocart_section" style="">

                                    <!-- <div class="col-5">
                                        <div class="input-group">
                                            <input type="number"  style="border-radius: 25px;border: 1px solid black;" class="form-control qty"
                                                   id="" value="1"
                                                   min="0">

                                        </div>
                                    </div>
                                    <div class="col-7">
                                        <a href="javascript:void(0)" class="clickaddtocart" id="" >
                                            <button class="add_to_cart" style="border:none !important">
                                                <span style="float: left"><i id="" class="cart_btn_class" ></i>
                                                    ADD TO CART
                                                </span>
                                                <span style="float: right" class="item_price_class" id="">
                                                </span>
                                            </button>
                                        </a>
                                    </div> -->

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>



<!-- Modal end -->
<span class="dynamicScript"></span>

<style>
    span {
        font-size: 14px
    }

    .marg {
        margin-top: 10px;
        margin-bottom: 10px
    }

    #add {
        height: 41px;
        width: 41px;
        border-top-right-radius: 50%;
        border-bottom-right-radius: 50%;
        border-top-left-radius: 0;
        border-bottom-left-radius: 0%;
        background-color: white;
        border-left: 0px;
        border-right: 1px solid black;
        border-top: 1px solid black;
        border-bottom: 1px solid black;
    }

    #sub {
        height: 41px;
        width: 41px;
        border-top-left-radius: 50%;
        border-bottom-left-radius: 50%;
        border-top-right-radius: 0;
        border-bottom-right-radius: 0%;
        background-color: white;
        border-right: 0px;
        border-left: 1px solid black;
        border-top: 1px solid black;
        border-bottom: 1px solid black;
    }

    .qty {
        height: 41px;
        border-left: 0px;
        border-right: 0px;
        border-bottom: 1px solid black;
        border-top: 1px solid black;
        text-align: center;
    }

    .add_to_cart {
        width: 100%;
        height: 41px;
        border-radius: 30px;
        border: 1px solid #56d4af;
        background-color: #56d4af;
        color: white;
        padding: 9px
    }
    /*NEW STYLE*/
    .booking-details_wrap_new {
        padding: 28px 0;
        font-family: Poppins;
    }
    .store_logo {
        width: 136px;
        height: 136px;
        margin-top: -83px;
        border-radius: 50%;
        overflow: hidden;
        background: #FFFFFF;
        box-shadow: 0.893087px 0.893087px 13.3963px -4.46543px rgb(0 0 0 / 25%);
        margin-left: -2px;
        margin-bottom: 9px;
    }
    .store_meta span, .store_meta a, .store_meta_info, .store_meta_info p {
        font-style: normal;
        font-weight: normal;
        font-size: 20.4897px;
        line-height: 31px;
        color: rgba(0, 0, 0, 0.6);
        display: inline-block;
        float: none !important;
    }
    .store_name {
        font-style: normal;
        font-weight: 600 !important;
        font-size: 26.7926px;
        line-height: 40px;
        color: #000000 !important;
        margin-top: 10px !important;
    }
    .store_rating span {
        font-family: 'simple-line-icons';
    }
    .store_review_count{margin: 0 20px;}
    span.store_meta_info {
        color: #000;
        margin-right: 60px;
    }
    span.store_meta_info span {
        font-size: 20px;
        font-weight: bold;
    }
    .search_section{
        font-style: normal;
        font-weight: 600;
        font-size: 21.4341px;
        line-height: 32px;
        /* identical to box height */
        color: rgba(0, 0, 0, 0.6);
    }
    .form-group.has-search {
        width: 227px;
        margin: 0 0 0 auto;
        position: relative;
    }

    .has-search .form-control-feedback {
        position: absolute;
        z-index: 2;
        display: block;
        width: 2.375rem;
        height: 2.375rem;
        line-height: 2.375rem;
        text-align: center;
        pointer-events: none;
        color: #767676;
    }

    .has-search .form-control, .has-search .form-control:focus {
        background: #ECECEC;
        border-radius: 5.6916px;
        font-style: normal;
        font-weight: normal;
        font-size: 15.9365px;
        line-height: 24px;
    /* identical to box height */
        text-align: center;
        color: rgba(0,0,0,.4);
        text-align: left;
        padding-left: 35px;
        border: none;
    }
    .menu_list_holder .swiper-slide {
        font-style: normal;
        font-weight: normal;
        font-size: 17.0748px;
        line-height: 31px;
        color: rgba(0, 0, 0, 0.6);
        padding: 0 20px 20px;
        text-align: center;
        text-decoration: none;
        margin-right: 20px;
        background: none;
        width: auto !important;
    }
    .menu_list_holder .swiper-slide.swiper-slide-active{
        font-style: normal;
        /*font-weight: 500;
        font-size: 20.4897px;
        line-height: 31px;*/
        color: #000000;
        position: relative;
    }
    .menu_list_holder .swiper-slide :hover {
        text-decoration: none;
        color: #000;
    }
    .menu_list_holder .swiper-slide.swiper-slide-active::after{
        width: 50%;
        height: 5px;
        background: #000;
        border-radius: 8.93087px;
        content: "";
        position: absolute;
        bottom: 20px;
        left: 50%;
        z-index: 1;
        transform: translate(-50%,-50%);
    }
    .menu_list_holder span.icon-list {
        color: #000;
        font-size: 20px;
        font-weight: bold;
        position: absolute;
        top: 50%;
        left: 0;
        transform: translate(0,-50%);
        margin-top: -5px;
        z-index: 1;
    }

    .menu_list_holder {
        position: relative;
        padding: 0 50px;
        margin-top: 40px;
        overflow: hidden;
    }
    .menu_list_holder::before{
        width: 50px;
        height: 90%;
        border-radius: 0;
        background: #fff;
        position: absolute;
        top: 0;
        left: 0;
        content: "";
        z-index: 1;
    }
    .menu_list_holder:after {
        width: 100%;
        height: 5px;
        background: rgba(225, 225, 225, 0.6);
        border-radius: 8.93087px;
        content: "";
        position: absolute;
        bottom: 0;
        left: 0;
    }
    .menu_list {
        width: 2000px;
        left: 0;
        display: inline-block;
        vertical-align: middle;
        position: relative;
    }
    .menu_list a:first-child {
        padding-left: 0;
    }
    .menu_list_holder #toggle {
        position: absolute;
        right: 0;
        top: 50%;
        transform: translate(0,-50%);
        margin-top: -5px;
        border: none;
        background: #fff;
        padding-left: 50px;
        cursor: pointer;
    }
    .menu_list_holder #toggle span {
        font-weight: bold;
    }
    .menu_list_holder #toggle:focus {
        border: none;
        outline: none;
    }
    .cat_box_title {
        font-style: normal;
        font-weight: 600;
        font-size: 22.7664px;
        line-height: 34px;
        color: rgba(0, 0, 0, 0.8) !important;
        border: none !important;
        padding: 0;
        display: block;
        width: 100%;
        margin-bottom: 27px;
    }
    .menu_item_box {
        background: #FFFFFF;
        border: 0.893087px solid rgba(196, 196, 196, 0.6);
        box-sizing: border-box;
        border-radius: 8.93087px;
        overflow: hidden;
        margin-top: 0 !important;
        margin-bottom: 25px;
        cursor: pointer;
    }
    .featured-title-box h6 {
        font-style: normal;
        font-weight: 600;
        font-size: 17.8617px;
        line-height: 27px;
        color: rgba(0, 0, 0, 0.8);
        margin-bottom: 5px !important;
    }

    .featured-place-wrap img {
        min-height: 216px;
        height: auto;
        left: 1px;
        position: relative;
        object-fit: cover;
    }
    .featured-title-box p.menu_price{
        display: block;
        font-style: normal;
        font-weight: 500;
        font-size: 17.0748px;
        line-height: 26px;
        color: #000000;

    }
    .featured-title-box p.menu_item_desc {
        font-style: normal;
        font-weight: normal;
        font-size: 15.9365px;
        color: rgba(0, 0, 0, 0.6);
        display: block;
        display: -webkit-box;
        margin: 0 auto;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-bottom: 10px;
    }
    .menu_list_sec .container .row {
        margin: 0;
    }
    .menu_box_holder .col-md-6 {
        padding: 0 8px;
    }
    .menu_box_holder {
        margin: 0 -8px 65px;
    }
    .menu_box_holder .featured-title-box {
        padding: 53px 27px 47px;
    }
     .swiper-container {
        width: 100%;
        height: 100%;
    }
    .swiper-slide {
        text-align: center;
        font-size: 18px;
        background: #fff;
        
        /* Center slide text vertically */
        display: -webkit-box;
        display: -ms-flexbox;
        display: -webkit-flex;
        display: flex;
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        -webkit-justify-content: center;
        justify-content: center;
        -webkit-box-align: center;
        -ms-flex-align: center;
        -webkit-align-items: center;
        align-items: center;
    }
    .menu_list_holder .swiper-button-next, .menu_list_holder .swiper-button-prev {
        width: 8px;
        height: 15px;
        right: 5px;
        background-size: 100%;
        filter: grayscale(1);
        margin-top: -8px;
    }
    .swiper-button-prev.swiper-button-disabled {
        display: none;
    }
    .menu_list_holder .swiper-button-prev{left: 40px;}
    .menu_list_sec {
        z-index: 1;
        background: #fff;
    }
    .menu_list_sec.menu_list_sec_fix {
        position: fixed;
        top: 70px;
        z-index: 1111;
        left: 0;
        width: 100%;
        transition: all 1s;
        background: #fff;
    }
    .add_padding .cat_box_title.ad_top_space{margin-top: 260px;}
    @media only screen and (max-width: 767px){
        .swiper-button-prev.swiper-button-disabled {
            display: none;
        }
        section.search_section {
            text-align: center;
        }

        .form-group.has-search {
            margin: 0 auto;
            width: 100%;
        }
        .menu_list_holder .swiper-slide{
            font-size: 12px;
            line-height: normal;
        }
        .menu_list_holder{
            padding: 0 30px;
        }
    }

</style>
<?php include 'footer.php'; ?>
<script>
    $(function () {
        var val = parseInt($('#value').val());
        $('#add').click(function () {
            val = val + 1;
            $('#value').val(val);
        });
        $('#sub').click(function () {
            val = val - 1;
            $('#value').val(val);
        });
    });
</script>
<script>
    var swiper = new Swiper('.swiper-container', {
        slidesPerView: 5,
        slidesPerGroup: 1,
        loop: false,
        loopFillGroupWithBlank: true,
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        breakpoints: {
            480: {
                slidesPerView: 2,
                slidesPerGroup: 1,
            }
        },
    });
</script>
<script>
    if ($('.image-link').length) {
        $('.image-link').magnificPopup({
            type: 'image',
            gallery: {
                enabled: true
            }
        });
    }
    if ($('.image-link2').length) {
        $('.image-link2').magnificPopup({
            type: 'image',
            gallery: {
                enabled: true
            }
        });
    }
</script>
<script>
    $(document).on('click', 'a[href^="#"]', function (event) {
        event.preventDefault();

        $('html, body').animate({
            scrollTop: $($.attr(this, 'href')).offset().top
        }, 500);
    });

    $(document).on('click', '.view-details', function () {

        $(".modal-body").html("");

        var dishId = $(this).attr('dish-id');

        var dishDetails = $("#" + dishId).clone();

        $(".modal-body").html(dishDetails);
        $(document).find(".modal-body #" + dishId).attr("style", "");

        $(".show-dish").click();


    });
</script>
<script type="text/javascript">
        $(document).ready(function () {
            $("#toggle").on('click', function () {
                var x = $(".menu_list").css("left");
            if(x == '0px') {
                $(".menu_list").animate({
                    left: '-55%'
                });
                } else {
                $(".menu_list").animate({
                    left: '0'
                });
                }
               
            });
        });
</script>
<script type="text/javascript">
    $(document).ready(function() {

            $('.swiper-slide').click(function() {

                $(this).siblings('.swiper-slide-active').removeClass('swiper-slide-active');

                if ($(this).hasClass('swiper-slide-active')) {
                    $(this).removeClass('swiper-slide-active');
                } else {
                    $(this).addClass('swiper-slide-active');
                }

            });

            var id;
            var title;
            var description;
            var img;
            var catobj;
            var cattitle;
            var storeid ;
            var storename;
            var price;

            $(document).on("click",".showMymodal",function(){

                id = $(this).attr("id");
                title = $(this).data("distitle");
                description = $(this).data("dishdescription");
                img = $(this).data("itemimg");
                catobj = $(this).data("catobj");
                cattitle = $(this).data("cattitle");
                storeid = $(this).data("storeid");
                storename = $(this).data("storename");
                price = $(this).data("price");

                $(".sauchitem").html('');
                $(".addtocart_section").html('');

                $(".dish_title").html(title);
                $(".dish_description").html(description);
                $(".modal_img").attr("src",img);
                $('.qty').attr('id','qty_'+id);
                $('.clickaddtocart').attr('id',"cart_btn2_"+id);
                $(".cart_btn_class").attr("id","cart_btn_"+id);
                $(".item_price_class").attr("id","item_price_html_"+id);
                $(".item_price_class").html(`$ ${price}`);
                $("#productDetailsModal").modal("show");
                $.ajax({
                    type: "POST",
                    data: {
                        id:id,
                        catobj:catobj,
                        cattitle:cattitle,
                        storeid:storeid,
                        storename:storename,
                        price:price
                    },
                    url: "menuModal.php",
                    success: function(data) {
                        const {sauchitem,script,addtocart} = JSON.parse(data);
                        $(".sauchitem").html(sauchitem);
                        $("dynamicScript").html(script);
                        $(".addtocart_section").html(addtocart);
                    }
                });
                
            });

        });
</script>
<!-- <script type="text/javascript">
    //CATEGORY HEADER
    $(window).scroll(function(){
        if ($(this).scrollTop() > 550) {
           $('.menu_list_sec').addClass('menu_list_sec_fix');
           $('.booking-details_wrap_new').addClass('add_padding');
        } else {
           $('.menu_list_sec').removeClass('menu_list_sec_fix');
           $('.booking-details_wrap_new').removeClass('add_padding');
        }
    });
</script> -->
<script type="text/javascript" src="js/jquery.sticky-kit.js"></script>
<script type="text/javascript">
    $(".menu_list_sec").stick_in_parent({
        offset_top: 40
    });


</script>
    
<script>                                                                                   
    $(document).ready(function() {
        $(document).on("change",".menu-check", function(evt) {
            let id = $(this).attr("data-id");
            let max = $(this).attr("data-maxtableitem");
            if($(this).siblings(":checked").length >= parseInt(max)) {
                this.checked = false;
            }
        }); 
    }); 
    </script>

