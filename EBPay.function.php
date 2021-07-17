<?php
$HashKey    = '';
$HashIV     = '';
$MerchantID = '';

/* EBPay function */
function create_mpg_aes_encrypt($parameter = "", $key = "", $iv = ""){
    $return_str = '';
    if (!empty($parameter)){
        $return_str = http_build_query($parameter);
    }
    return trim(bin2hex(openssl_encrypt(addpadding($return_str) , 'aes-256-cbc', $key, OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, $iv)));
}

function addpadding($string, $blocksize = 32){
    $len = strlen($string);
    $pad = $blocksize - ($len % $blocksize);
    $string .= str_repeat(chr($pad) , $pad);

    return $string;
}

function create_aes_decrypt($parameter = "", $key = "", $iv = ""){
    return strippadding(openssl_decrypt(hex2bin($parameter) , 'AES-256-CBC', $key, OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, $iv));
}

function strippadding($string){
    $slast = ord(substr($string, -1));
    $slastc = chr($slast);
    $pcheck = substr($string, -$slast);
    if (preg_match("/$slastc{" . $slast . "}/", $string)){
        $string = substr($string, 0, strlen($string) - $slast);
        return $string;
    }else{
        return false;
    }
}

function SHA256($key = "", $tradeinfo = "", $iv = ""){
    $HashIV_Key = "HashKey=" . $key . "&" . $tradeinfo . "&HashIV=" . $iv;

    return $HashIV_Key;
}

function CheckOut($URL = "", $MerchantID = "", $TradeInfo = "", $SHA256 = "", $VER = ""){
    $szHtml = '<!doctype html>';
    $szHtml .= '<html>';
    $szHtml .= '<head>';
    $szHtml .= '<meta charset="utf-8">';
    $szHtml .= '</head>';
    $szHtml .= '<body>';
    $szHtml .= '<form name="newebpay" id="newebpay" method="post" action="' . $URL . '" style="display:none;">';
    $szHtml .= '<input type="text" name="MerchantID" value="' . $MerchantID . '" type="hidden">';
    $szHtml .= '<input type="text" name="TradeInfo" value="' . $TradeInfo . '"   type="hidden">';
    $szHtml .= '<input type="text" name="TradeSha" value="' . $SHA256 . '" type="hidden">';
    $szHtml .= '<input type="text" name="Version"  value="' . $VER . '" type="hidden">';
    $szHtml .= '</form>';
    $szHtml .= '<script type="text/javascript">';
    $szHtml .= 'document.getElementById("newebpay").submit();';
    $szHtml .= '</script>';
    $szHtml .= '</body>';
    $szHtml .= '</html>';

    return $szHtml;
}
?>
