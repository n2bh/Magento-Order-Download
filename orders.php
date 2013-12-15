<?php 
$impfile = "mageorders.txt";
// $impfile = "mageorders.html";
unlink($impfile);
ini_set("error_reporting",E_ALL);
ini_set("display_errors",true);
require_once("/usr/share/nginx/html/app/Mage.php");
require_once("/usr/share/nginx/html/mageapi/states.php");
require_once("/usr/share/nginx/html/mageapi/kint-master/Kint.class.php");
Mage::app('default');
$myOrder=Mage::getModel('sales/order');
$orders=Mage::getModel('sales/mysql4_order_collection');
//Optional filters you might want to use - more available operations in method _getConditionSql in Varien_Data_Collection_Db.
$orders->addFieldToFilter('total_paid',Array('gt'=>0)); //Amount paid larger than 0
$orders->addFieldToFilter('status',Array('eq'=>"processing"));  //Status is "processing"
$allIds=$orders->getAllIds();
ob_start();
echo "Order Number" . "\t" . "Order Date" . "\t" . "Total Qty Items Ordered" . "\t" . "Order Shipping" . "\t" . "Order Tax" . "\t" . "Order Subtotal" . "\t" . "Order Grand Total" . "\t" . "Order Shipping Method" . "\t" . "Customer Name" . "\t" . "Shipping Name" . "\t" . "Shipping Street1" . "\t" . "Shipping Street2" . "\t" . "Shipping City" . "\t" . "Shipping State" . "\t" . "Shipping Zip" . "\t" . "Shipping Country" . "\t". "Billing Name" . "\t". "Billing Street1" . "\t". "Billing Street2" . "\t" . "Billing City" . "\t" . "Billing State" . "\t" . "Billing Zip" . "\t". "Billing Country" . "\t". "Billing Phone" ."\t" . "Customer Email" . "\t" . "Shipping Phone Number" . "\t" . "Order Status" .  "\t" . "BSame" . "\t" . "Item SKU" . "\t" . "Item Name" . "\t" . "Item Qty Ordered" . "\t" . "Item Original Price" . "\t" . "Item Price" . "\t" . "Item Tax" . "\t" . "Tax Rate";
echo "\n";
foreach($allIds as $thisId) {
    $myOrder->reset()->load($thisId);

    $ordid = $myOrder->getRealOrderId();
    $items = $myOrder->getAllItems();

        echo  $myOrder->getRealOrderId() . "\t";  // Order Number
    list($month, $dayt, $year) = explode("/", $myOrder->getCreatedAtFormated('short'));
        echo $month . "/" . $dayt . "/20" . $year . "\t";  // Order Date
        echo "NULL" . "\t"; // $myOrder->getTotalItemCount(); // Total Qty Items Ordered  (doesn't matter)
        echo money_format('%.2n', $myOrder->getBaseShippingAmount()) . "\t"; // Order Shipping
        echo money_format('%.2n', $myOrder->getTaxAmount()) . "\t"; // Order Tax
        echo money_format('%.2n', $myOrder->getBaseSubtotal()) . "\t";  // Order Subtotal
        echo money_format('%.2n', $myOrder->getGrandTotal()) . "\t"; // Order Grand Total
        echo $myOrder->getShippingMethod() . "\t"; // Order Shipping Method
        echo $myOrder->getCustomerName() . "\t"; // Customer Name
        echo $myOrder->getShippingAddress()->getFirstname() . " " . $myOrder->getShippingAddress()->getLastname() . "\t"; //Shipping Name

    $shipaddress = $myOrder->getShippingAddressId();
    $billaddress = $myOrder->getBillingAddressId();

    $baddress = Mage::getModel('sales/order_address')->load($billaddress);
    $address = Mage::getModel('sales/order_address')->load($shipaddress);
    $chity = Mage::getModel('directory/region')->load($cityid);
    $bchity = Mage::getModel('directory/region')->load($bcityid);

        echo  $address->getStreet1() . "\t"; // Shipping Street1
        echo $address->getStreet2() . $address->getStreet3(). $address->getStreet4() . "\t"; // Shipping Street2
        echo $address->getCity(); // Shipping City
        echo "\t";
    $shtat = $myOrder->getShippingAddress()->getRegion();
        echo convertState($shtat, "abbrev");// Shipping State
        echo "\t";
        echo $address->getPostcode();// Shipping Zip
        echo "\t";
        echo $address->getCountry();// Shipping Country
        echo "\t";
        echo $baddress->getName(); //begin billing: bill name: bill:
        echo "\t";
        echo $baddress->getStreet1(); // street 1
        echo "\t";
        echo $baddress->getStreet2() ." " . $baddress->getStreet3() ." ".  $baddress->getStreet4() . " "; // street 2
        echo "\t";
        echo $baddress->getCity(); // city
        echo "\t";
    $tittyfuck = $myOrder->getBillingAddress()->getRegion();
        echo convertState($tittyfuck, "abbrev") . "\t"; //state
        echo $baddress->getPostcode(); //zip
        echo "\t";
        echo $baddress->getCountry();//country
        echo "\t";
        echo $baddress->getTelephone();//phone
        echo "\t";
    $bemail = $address->getEmail();
    if (isset($bemail)) {
            echo $address->getEmail;
            }
    else {
            echo $myOrder->getCustomerEmail();
            }
        echo $address->getEmail(); // Customer email
        echo "\t";
        echo $address->getTelephone(); // Shipping phone number
        echo "\t";
        echo $myOrder->getStatus() . "\t"; // Order Status
    if ( $address->getStreet1() == $baddress->getStreet1() && $address->getStreet2() == $baddress->getStreet2())   // BSame
    {
        echo "1";
    }
    else
    {
        echo "0";
    }
        echo "\t";

$runtimes = 0;
$nuitems = count($items);
$kur1 = false;
    if ($nuitems <= 1)
    {
        foreach($items as $item => $eyetem){

        $manda = $eyetem->getData();
        echo $manda['sku']; // Item SKU
        echo "\t";
        echo $manda['name']; // Item Name
        echo "\t";
        echo $manda['qty_ordered']; // Item Qty Ordered (how many of a SKU)
        echo "\t";
        echo $manda['original_price']; // Item Original Price
        echo "\t";
        echo $manda['price'];  // Item Price
        echo "\t";
        echo money_format('%.2n', $myOrder->getBaseTaxAmount()); // Item Tax
        echo "\t";
        echo $manda['tax_percent'];  // Tax Rate
        echo "\t";
        echo "\r\n";
        }
    }
    else
    {
        foreach($items as $item => $eyetem){
            if ($kur1) {
                echo "\r\n";
                echo  $myOrder->getRealOrderId();  // Order Number

                for ($i=0; $i <= 27; $i++)
                {
                    echo "\t";
                }
            }

            $manda = $eyetem->getData();
            echo $manda['sku']; // Item SKU
            echo "\t";
            echo $manda['name']; // Item Name
            echo "\t";
            echo $manda['qty_ordered']; // Item Qty Ordered (how many of a SKU)
            echo "\t";
            echo $manda['original_price']; // Item Original Price
            echo "\t";
            echo $manda['price'];  // Item Price
            echo "\t";
            echo money_format('%.2n', $myOrder->getBaseTaxAmount()); // Item Tax
            echo "\t";
            echo $manda['tax_percent'];  // Tax Rate
            echo "\t";
            ++$runtimes;
            if ($runtimes == $nuitems)
            {
                echo "\r\n";
            }
            $kur1 = true;
        }
    }
}
$content = ob_get_clean();
file_put_contents('/usr/share/nginx/html/mageapi/mageorders.txt', $content);
// file_put_contents('/usr/share/nginx/html/mageapi/mageorders.html', $content);
//  d($items);
//  d($myOrder);
