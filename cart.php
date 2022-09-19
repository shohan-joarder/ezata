  <?php ini_set('max_execution_time', 0); 
ini_set('memory_limit','2048M');
ini_set('session.gc_maxlifetime', 360000);
session_set_cookie_params(360000);

session_start();
error_reporting(0);


?>
<?php

function searchArrayKeyVal($sKey, $id, $array) {
   foreach ($array as $key => $val) {
       if ($val[$sKey] == $id) {
           return $key;
       }
   }
   return false;
}




if(isset($_POST['title']))
{
	
	if($_POST['pro']==1)
	{
		unset($_SESSION['cart']);
	}
	
	
	if($_SESSION["cart"])
	{
		$store = searchArrayKeyVal("store_id", $_POST['store_id'], $_SESSION["cart"]);
		if ($store!==false)
		{
			$item['title'] = $_POST['title'];
			$item['price'] = $_POST['price'];
			$item['id'] = $_POST['id'];
			$item['pic'] = $_POST['pic'];
			$item['qty'] = $_POST['qty'];
			$item['extra_item'] = $_POST['extra_item'];
			$item['store_id'] = $_POST['store_id'];
			$item['store_title'] = $_POST['store_title'];
			$item['category_id'] = $_POST['category_id'];
			$item['category_title'] = $_POST['category_title'];
			
			$arrayKey = searchArrayKeyVal("title", $_POST['title'], $_SESSION["cart"]);
			if ($arrayKey!==false) 
			{
				$_SESSION["cart"][$arrayKey]['qty'] += $_POST['qty'];
				
				array_merge($_SESSION["cart"],$item);
			} 
			else 
			{
				$_SESSION["cart"][]=$item;
			}
			
			echo 1;
		}
		else
		{
			echo 0;
		}
	}
	else
	{
		$item['title'] = $_POST['title'];
		$item['price'] = $_POST['price'];
		$item['id'] = $_POST['id'];
		$item['qty'] = $_POST['qty'];
		$item['extra_item'] = $_POST['extra_item'];
		$item['store_id'] = $_POST['store_id'];
		$item['store_title'] = $_POST['store_title'];
		$item['category_id'] = $_POST['category_id'];
		$item['category_title'] = $_POST['category_title'];
		$_SESSION["cart"][]=$item;	
		echo 1;
	}

}

if(isset($_GET['remove']))
{
  
  
      unset($_SESSION["cart"][$_GET['remove']]);
    
	if(isset($_GET['Id']))
	{
		  echo  '<script>window.location="detail.php?Id='.$_GET['Id'].'"</script>';	
	}
	else
	{
		  echo  '<script>window.location="index.php"</script>';
	}
  
    
}
if(isset($_GET['cart']))
{
  
  
      unset($_SESSION['cart']);
	  unset($_SESSION['parseData']);
    
   if(isset($_GET['Id']))
	{
		  echo  '<script>window.location="detail.php?Id='.$_GET['Id'].'"</script>';	
	}
	else
	{
		  echo  '<script>window.location="index.php"</script>';
	}
    
} ?>
    