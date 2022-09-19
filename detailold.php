<?php


require 'vendor/autoload.php';

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
//$storeQuery->equalTo('user','ySHH4KuNZx');
try {
    //$storeObject = $storeQuery->get($storeId);
    $storeObject = $storeQuery->first();
    //print_r($storeObject);exit;

    // To get attributes, you can use the "get" method, providing the attribute name:
    $storeName = $storeObject->get("name");
    $address = $storeObject->get("address");
    $storeImageUrl = $storeObject->get("storeImageUrl");
    $openingHours = $storeObject->get("openingHours");
    $phoneNumber = $storeObject->get("phoneNumber");
    $emailAddress = $storeObject->get("emailAddress");
    $coordinate = $storeObject->get("coordinate");
    $storeDescription = $storeObject->get("storeDescription");
    $website = $storeObject->get("website");
    $priceRate = $storeObject->get("price_rate");
    $headline = $storeObject->get("headline");
    $reviewsCount = $storeObject->get("reviews_count");
    $authorImage = $storeObject->get("author_image");
    $authorCountry = $storeObject->get("author_country");
    $amenities = $storeObject->get("amenities");
    $authorName = $storeObject->get("author_name");
    $storeRating = $storeObject->get("store_rating");

} catch (ParseException $ex) {
// The object was not retrieved successfully.
// error is a ParseException with an error code and message.
}

$storeQuery = new ParseQuery("Store");
$storeQuery->equalTo("objectId", $storeId);

$cQuery = new ParseQuery("Categories");
$cQuery->matchesQuery("ParentStore", $storeQuery);
try {
    $results = $cQuery->find();
} catch (ParseException $ex) {
    echo $ex->getMessage();
}

include 'header.php';
?>


<div>
    <!-- Swiper -->
    <div class="swiper-container">
        <div class="swiper-wrapper">

            <div class="swiper-slide">
                <a href="images/reserve-slide2.jpg" class="grid image-link">
                    <img src="images/reserve-slide2.jpg" class="img-fluid" alt="#">
                </a>
            </div>
            <div class="swiper-slide">
                <a href="images/reserve-slide1.jpg" class="grid image-link">
                    <img src="images/reserve-slide1.jpg" class="img-fluid" alt="#">
                </a>
            </div>
            <div class="swiper-slide">
                <a href="images/reserve-slide3.jpg" class="grid image-link">
                    <img src="images/reserve-slide3.jpg" class="img-fluid" alt="#">
                </a>
            </div>
            <div class="swiper-slide">
                <a href="images/reserve-slide1.jpg" class="grid image-link">
                    <img src="images/reserve-slide1.jpg" class="img-fluid" alt="#">
                </a>
            </div>
            <div class="swiper-slide">
                <a href="images/reserve-slide2.jpg" class="grid image-link">
                    <img src="images/reserve-slide2.jpg" class="img-fluid" alt="#">
                </a>
            </div>
            <div class="swiper-slide">
                <a href="images/reserve-slide3.jpg" class="grid image-link">
                    <img src="images/reserve-slide3.jpg" class="img-fluid" alt="#">
                </a>
            </div>
        </div>
        <!-- Add Pagination -->
        <div class="swiper-pagination swiper-pagination-white"></div>
        <!-- Add Arrows -->
        <div class="swiper-button-next swiper-button-white"></div>
        <div class="swiper-button-prev swiper-button-white"></div>
    </div>
</div>


<!--//END BOOKING -->
<!--============================= RESERVE A SEAT =============================-->
<section class="reserve-block">
    <div class="container">
        <div class="row">
            <div class="col-md-9">

                <h5 style="display:block;margin-bottom:10px;"><?php echo ucwords($storeName) ?> </h5>
                <p style="padding-left:0px;"><span>$$$</span>$$</p>
                <p class="reserve-description"><?php echo $headline ?></p>
                <div>
                    <?php foreach ($results as $result):
                        $maincategoryObjectId = $result->getObjectId();
                        $maincategoryTitle = $result->get("itemTitle");
                    ?>
                    <a href="#<?php echo $maincategoryObjectId ?>" class="customer-rating customer-rating-red"
                       style="margin-right:5px;float:left;margin-bottom:3px;text-decoration:none;"><?php echo $maincategoryTitle ?></a>
                    <?php endforeach; ?>

                </div>

            </div>
            <div class="col-md-3">
                <div class="reserve-seat-block">
                    <div class="reserve-rating">
                        <span><?php echo $storeRating ?></span>
                    </div>
                    <div class="review-btn">
                        <a href="#" class="btn btn-outline-danger">WRITE A REVIEW</a>
                        <span><?php echo $reviewsCount ?> reviews</span>
                    </div>
                    <!--<div class="reserve-btn">
                        <div class="featured-btn-wrap">
                            <a href="#" class="btn btn-danger">RESERVE A SEAT</a>
                        </div>
                    </div>-->
                </div>
            </div>
        </div>
    </div>
</section>
<!--//END RESERVE A SEAT -->
<!--============================= BOOKING DETAILS =============================-->

<section class="light-bg booking-details_wrap">
    <div class="container">
        <div class="row">
            <div class="col-md-8 responsive-wrap">

                <?php foreach ($results as $result):
                    $categoryObject = $result->getObjectId();
                    $categoryTitle = $result->get("itemTitle");
                    ?>

                    <div id='<?= $categoryObject?>' style='padding:40px;'></div><div class='alert alert-info' style='color: #ff3a6d; background-color: #fff;border: 2px solid #ff3a6d85;'> <span class='icon-directions'></span> <?=$categoryTitle?></div>

                    <?php
                    $cQuery = new ParseQuery("Categories");
                    $cQuery->equalTo("objectId", $categoryObject);

                    $menuQuery = new ParseQuery("Menu");
                    $menuQuery->matchesQuery("ParentCategory", $cQuery);
                    $menuItem='';

                    try {
                        $menuResult = $menuQuery->find();
                    } catch (ParseException $ex) {
                        echo $ex->getMessage();
                    }

                        foreach ($menuResult as $allItem):
                            $ItemId = $allItem->getObjectId();
                            $id = "'" . $ItemId . "'";
                            $dishImage = $allItem->get('dish_image');
                            $imageURL = ($dishImage !== null) ? $dishImage->getURL() : "https://parsefiles.back4app.com/gg2zjiKqqH9hpSXWVSoXv8ZG6aJ1CA9ImhTO0bEd/ffd34653efced509c2df5f0e2241b909_bg-1.jpg";
                        $menuItem .= '
                        <div class="row featured-place-wrap" style="margin-top:50px;">  
                            <div class="col-md-6 featured-responsive" style="padding:0px;">
                                <a href="#">
                                    <img src="'. $imageURL.'" class="img-fluid" alt="'.$allItem->get('dishTitle').'">
                                </a>
                            </div> 
                            <div class="col-md-6" style="padding:0px;">
                                <div class="featured-title-box">
                                    <h6 style="margin-bottom:20px;">'.ucwords($allItem->get('dishTitle')).' Menu</h6>
                                    <p>Dish '.$allItem->get('dishTitle').' </p> <span>• </span>
                                 
                                    <p>Price <span>$</span>'.$allItem->get('price').'</p>
                                  
                                    <input type="hidden" id="item_title_'.$ItemId.'"  value="'.$allItem->get('dishTitle').'">
                                    <input  type="hidden"  id="item_price_'.$ItemId.'" value="'.$allItem->get('price').'">
                                    <input  type="hidden"  id="item_id_'.$ItemId.'" value="'.$ItemId.'">
                                    <input  type="hidden"  id="item_pic_'.$ItemId.'" value="'.$imageURL.'">
                                    <input  type="hidden"  id="gtotal_'.$ItemId.'" value="'.$allItem->get('price').'">
                                    <input  type="hidden"  id="extraitem_'.$ItemId.'" value="">
                                    
                                    <div class="bottom-icons">
                                          <div class="closed-now">
                                            <button data-toggle="modal" data-target="#myModal_'.$ItemId.'"  class="btn btn-outline-danger btn-contact " style="padding:6px;margin-left: 0px;">VIEW DETAILS</button>
                                           </div>
                                          <span class="ti-heart"></span>
                                          <span class="ti-bookmark"></span>
                                    </div>
                                </div>
                            </div>
                        </div>';
                        ?>
                        <!-- Modal -->

                        <?php require './menuModalorginal.php';?>

                        <!-- Modal -->
                        <?php
                        endforeach;

                        echo $menuItem;




                    ?>

                <?php endforeach;?>


                <div class="booking-checkbox_wrap">
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
                        <?php endforeach; ?>


                    </div>
                </div>
                <div class="booking-checkbox_wrap mt-4">
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
            <div class="col-md-4 responsive-wrap" style="margin-top:50px;">
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
                <div class="follow">
                    <div class="follow-img">
                        <img src="<?php echo $authorImage->getURL() ?>" class="img-fluid"
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
            </div>
        </div>
    </div>
</section>

<!--//END BOOKING DETAILS -->




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
        slidesPerView: 3,
        slidesPerGroup: 3,
        loop: true,
        loopFillGroupWithBlank: true,
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
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
    

