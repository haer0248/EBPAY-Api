<?php 
require_once 'EBPay.function.php';

$TradeInfo = file_get_contents("php://input");

$arr = mb_split("&", $TradeInfo);
$get_aes = str_replace("TradeInfo=","", $arr[3]);

$data = create_aes_decrypt($get_aes, $HashKey, $HashIV);
$json = json_decode($data);

if ($json->Status == "SUCCESS"){

  // Get MerchantTradeNo example
  $MerchantTradeNo = $json->Result->MerchantOrderNo;

  /*
   *  See more: https://www.newebpay.com/website/Page/download_file?name=%E8%97%8D%E6%96%B0%E9%87%91%E6%B5%81Newebpay_MPG%E4%B8%B2%E6%8E%A5%E6%89%8B%E5%86%8A_MPG_1.1.0.pdf
   *  Download this api pdf file page 46.
   *  
   */

}else{
  echo "200 OK";
}