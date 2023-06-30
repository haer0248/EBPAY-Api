<?php
require_once 'EBPay.function.php';

$MerchantOrderNo = "Order" . time();

/* Create payment info */
$TradeInfoArray = array(
    'MerchantID' => $MerchantID,
    'RespondType' => 'JSON',
    'TimeStamp' => time(),
    'Version' => $Version,
    'MerchantOrderNo' => $MerchantOrderNo,
    'Amt' => 100,
    'ItemDesc' => "Test Item",
    'NotifyURL' => "https://{$_SERVER['HTTP_HOST']}/NotifyURL",
    'ClientBackURL' => "https://{$_SERVER['HTTP_HOST']}/{$_SERVER['REQUEST_URI']}",
    'ExpireDate' => date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") + 3, date("Y")))
);

/* Generate Encrypt */
$TradeInfo = create_mpg_aes_encrypt($TradeInfoArray, $HashKey, $HashIV);

/* Generate SHA256 */
$SHA256 = strtoupper(hash("sha256", SHA256($HashKey, $TradeInfo, $HashIV)));

/* Submit from */
echo CheckOut('https://ccore.newebpay.com/MPG/mpg_gateway', $MerchantID, $TradeInfo, $SHA256, $Version);
