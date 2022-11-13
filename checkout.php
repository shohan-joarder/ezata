
  <!-- 
    Check user if logged in
    -->
    <?php 
    require 'vendor/autoload.php';
    session_start();
    if(isset($_SESSION["isLoggedIn"])){
      $userId = $_SESSION["userObjId"];
    }else{
      header('Location: /order.php');
    }
    use Parse\ParseQuery;
    use Parse\ParseException;
    use Parse\ParseClient;
    ParseClient::initialize("9ONzVfqhJ8XvsDSthjVg6cf86Gg1I1HD4RIue3VB", "yg51KKzO3QMgw8brdP1FETmTerNDB4MKTEH9HneI", "I82wQlOUEAXSlG5EspgatZvJfWJlqnnusfvB0tI8");
    ParseClient::setServerURL('https://parseapi.back4app.com', '/');


    /* 
    Parse query for address start
    */
    // $getUser = new ParseQuery("User");
    // $getUser->equalTo("objectId", $userId);
    // echo "<pre>";
    // print_r($userId);
    // die;

    $addressOptions = '<option value="">Select your address...</option>';

    $query2 = new ParseQuery("Address");
    $query2->includeKey('user');
    // $query2->matchesQuery("user", $userId);
    // $query = new ParseQuery("User");
    // $query2->equalTo("user", "DQYVFrvYkP");
    // $addressQuery->equalTo("user", $userId);
    // die;
    // echo "<pre>";
    // print_r($query2->find());
    // die;

    try {
      $addressQueryData = $query2->find();

      foreach ($addressQueryData as $key => $value) {
        if($value->user){
        if($value->user->getObjectId() === @$userId){
          // $addressOptions .= '<option value="'. $value->street .'">'.$value->street.'</option>';
          $addressOptions .= '<option value="'.$value->getObjectId() .'">'.$value->street.'</option>';
          }
        }
      }

    } catch (ParseException $ex) {
      echo $ex->getMessage();
    }
    /* 
    Parse query for address end
    */


    ?>
  <!-- 
    Check user if logged in
    -->
<?php include 'header.php'; ?>
  
<style>

  .text-navy {
      color: #1ab394;
  }
  .cart-product-imitation {
    text-align: center;
    padding-top: 30px;
    height: 80px;
    width: 80px;
    background-color: #f8f8f9;
  }
  .product-imitation.xl {
    padding: 120px 0;
  }
  .product-desc {
    padding: 20px;
    position: relative;
  }
  .ecommerce .tag-list {
    padding: 0;
  }
  .ecommerce .fa-star {
    color: #d1dade;
  }
  .ecommerce .fa-star.active {
    color: #f8ac59;
  }
  .ecommerce .note-editor {
    border: 1px solid #e7eaec;
  }
  table.shoping-cart-table {
    margin-bottom: 0;
  }
  table.shoping-cart-table tr td {
    border: none;
    text-align: right;
  }
  table.shoping-cart-table tr td.desc,
  table.shoping-cart-table tr td:first-child {
    text-align: left;
  }
  table.shoping-cart-table tr td:last-child {
    width: 80px;
  }
  .ibox {
    clear: both;
    margin-bottom: 25px;
    margin-top: 0;
    padding: 0;
  }
  .ibox.collapsed .ibox-content {
    display: none;
  }
  .ibox:after,
  .ibox:before {
    display: table;
  }
  .ibox-title {
    -moz-border-bottom-colors: none;
    -moz-border-left-colors: none;
    -moz-border-right-colors: none;
    -moz-border-top-colors: none;
    background-color: #ffffff;
    border-color: #e7eaec;
    border-image: none;
    border-style: solid solid none;
    border-width: 3px 0 0;
    color: inherit;
    margin-bottom: 0;
    padding: 14px 15px 7px;
    min-height: 48px;
  }
  .ibox-content {
    background-color: #ffffff;
    color: inherit;
    padding: 15px 20px 20px 20px;
    border-color: #e7eaec;
    border-image: none;
    border-style: solid solid none;
    border-width: 1px 0;
  }
  .ibox-footer {
    color: inherit;
    border-top: 1px solid #e7eaec;
    background: #ffffff;
    padding: 10px 15px;
  }
  .form-control {
      display: block;
      width: 100%;
      padding: .5rem .75rem;
      font-size: 14px;
  }
</style>
    
    <!--//END RESERVE A SEAT -->
    
    <!--============================= BOOKING DETAILS =============================-->
    <section class="light-bg booking-details_wrap">
        
       <div class="container" style="padding-top:20px;">
  
        <h3>Checkout </h3>
      <br>
      <div class="row">
        <div class="col-md-4 order-md-2 mb-4">
          <h4 class="d-flex justify-content-between align-items-center mb-3" >
            <span class="text-muted" style="font-size:24px">Your cart</span>
            <span class="badge badge-secondary badge-pill" style="font-size:16px">  
			<?php if(isset($_SESSION['cart'])): ?> 
			<?= count($_SESSION['cart']) ?>
            <?php else: ?> 
			0
			<?php endif  ?> Items</span>
          </h4>
          <ul class="list-group mb-3">
           <?php $total=0; foreach ($_SESSION['cart'] as $k => $item):?>
               <?php $total+= $item['qty']*$item['price'] ?>
            <li class="list-group-item d-flex justify-content-between lh-condensed">
              <div>
              <small class="text-muted"> <?= $item['store_title'] ?> - <?= $item['category_title'] ?></small>
                <h6 class="my-0" style="font-size:18px"><?= $item['qty'] ?> x <?= $item['title'] ?></h6>
                <small class="text-muted">
                 <?php if(isset($item['extra_item'])): ?>
                           <?php $extraa =  explode(',',$item['extra_item']); ?>
                           <?php if(count($extraa)): ?>
                           
                            <?php for($i=0; $i<count($extraa);  $i++): ?>
                            <?= ucwords($extraa[$i])  ?>
                            <?php  endfor ?>
                            <?php endif ?>
                            <?php endif ?>
                
                </small>
              </div>
              <span class="text-muted" style="padding-top:10px;"> $<?= $item['qty']*$item['price'] ?> </span>
            </li>
              
            <?php endforeach ?>
            
            <li class="list-group-item d-flex justify-content-between lh-condensed" style="background:#868e96; color:white">
               <h6 class="my-0" style="font-size:18px; line-height:22px">Grand Total</h6>
                <span style="padding-top:;"> $<?= $total ?> </span>
              </li>
            
             </ul>

          <form class="card p-2">
            <div class="input-group">
              <input type="text" class="form-control" placeholder="Promo code">
              <div class="input-group-append">
                <button type="submit" class="btn btn-secondary">Redeem</button>
              </div>
            </div>
          </form>
        </div>
        <div class="col-md-8 order-md-1"  style="background:white; padding-top:30px; padding-bottom:30px">
          <h4 class="mb-3" style="font-size:18px">Billing address</h4>
          <form class="needs-validation" novalidate="" action="storeOrder.php" method="post">
            <input type="hidden" name="cartData" value="<?php print json_encode($_SESSION['cart']); ?>" >
            <div class="row">
              <div class="col-md-12 mb-3">
                <label for="firstName">Time: </label>
                &nbsp;
                <input checked type="radio" name="billing_time" value="ASAP - 44 - 54 Min"> ASAP - 44 - 54 Min
                &nbsp;&nbsp;
                 <input type="radio" name="billing_time" value="Schedual for Later"> Schedual for Later
               
              </div>
              
            </div>
             <div class="row">
              <div class="col-md-12 mb-3">
                <label for="firstName">Delivery Type: </label>
                &nbsp;
                <input checked type="radio" name="delivery_type" value="Delivery"> Delivery
                &nbsp;&nbsp;
                 <input type="radio" name="delivery_type" value="Pickup"> Pickup
               
              </div>
              
            </div>

           

            

            <div class="mb-3">
              <label for="address">Address</label>
              <select class="form-control" name="" id="address">
                <?= $addressOptions ?>
              </select>
              <!-- <input type="text" class="form-control" id="address" placeholder="1234 Main St" required=""> -->
              <div class="invalid-feedback errorAddress">
                Please enter your shipping address.
              </div>
              
              <br>
              <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d26360909.888257876!2d-113.74875964478716!3d36.242299409623534!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x54eab584e432360b%3A0x1c3bb99243deb742!2sUnited%20States!5e0!3m2!1sen!2s!4v1608471468128!5m2!1sen!2s" width="100%" height="200" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
            </div>


            
            <hr class="mb-4">
            
          
            <h4 class="mb-3"  style="font-size:18px">Payment</h4>

           
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="cc-name">Name on card</label>
                <input type="text" class="form-control" id="cc-name" placeholder="" required="">
                <small class="text-muted">Full name as displayed on card</small>
                <div class="invalid-feedback">
                  Name on card is required
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <label for="cc-number">Credit card number</label>
                <input type="text" class="form-control" id="cc-number" placeholder="" required="">
                <div class="invalid-feedback">
                  Credit card number is required
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-3 mb-3">
                <label for="cc-expiration">Expiration</label>
                <input type="text" class="form-control" id="cc-expiration" placeholder="" required="">
                <div class="invalid-feedback">
                  Expiration date required
                </div>
              </div>
              <div class="col-md-3 mb-3">
                <label for="cc-expiration">CVV</label>
                <input type="text" class="form-control" id="cc-cvv" placeholder="" required="">
                <div class="invalid-feedback">
                  Security code required
                </div>
              </div>
            </div>
            <hr class="mb-4">
            <?php if( count($_SESSION['cart']) >0): ?>
              <div class="checkoutButton">
                  <button class="btn btn-primary btn-lg btn-block" style="background:#868e96; border-color:#868e96" type="submit">Continue to checkout</button>
              </div>
            <?php endif ?>
          </form>
        </div>
      </div>

     
    </div>
    </section>
    <!--//END BOOKING DETAILS -->
  




<script>
  $(document).ready(function(){
    // init parse from node
  

    // const Parse = require('parse');


    // const Parse = require('parse/node');

    // let liveQuery = new Parse.Query('Orders');
    // let subscription = liveQuery.subscribe();
    
    // subscription.on('create', (object) => {
    //   console.log('New object created with objectId: ' + object.id);
    // });

    $(document).on("submit",'.needs-validation',function(e){
      e.preventDefault();
      
      let userId = "<?= $userObjId?>";
      if(userId == false){
        $("#productDetailsModal").modal("hide");
        document.getElementById("log").style.display = "inline-block";
        return false;
      }

      let billingTime = $("input[name='billing_time']:checked").val();
      let deliveryType = $("input[name='delivery_type']:checked").val();
      // let current_userId = $userId;?
      let address = $("#address").val();
      // console.log(address);return false;
      if(address == ""){
        $(".errorAddress").show();
      }else{
        $(".checkoutButton").html(`<button class="btn btn-default btn-lg btn-block" type="button" disabled style="color:black;">
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            Loading...
        </button>`);
        ajaxSubmit(billingTime,deliveryType,address);
      }
      
    
    });
    
    function ajaxSubmit(billingTime,deliveryType,address){
      let cartData = '<?= json_encode($_SESSION["cart"]) ?>';
      $.ajax({
        url: "storeOrder.php",
        type: "POST",
        data: { billingTime: billingTime, deliveryType: deliveryType, address: address, cartData: cartData },
        success: function(response){
          let data = JSON.parse(response);
          const { success, message,orderId } = data;
          if(success){
            alert(message);
            window.location.href = "orderprocess.php?order-id="+orderId;
          }else{
            alert(message);
          }
          // console.log(response);
          $(".checkoutButton").html(`<button class="btn btn-primary btn-lg btn-block" style="background:#868e96; border-color:#868e96" type="submit">Continue to checkout</button>`);
        }
      });
    }

  });
</script>
<!-- Modal -->
<?php include 'footer.php'; ?>
