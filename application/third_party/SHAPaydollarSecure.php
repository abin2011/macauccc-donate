<?php
/**
 * @Author            :  Clickr Abin
 * @Create Date       :  2019-09-23 17:33:16
 * @Last Modified by  :  Clickr Abin
 * @Last Modified time:  2019-09-24 13:09:47
 * @email             :  info@clickrweb.com
 * @description       :  PaydollarSecure 聯款通支付數據加密/校對
 */
interface PaydollarSecure {

  public function generatePaymentSecureHash($merchantId,
    $merchantReferenceNumber, $currencyCode, $amount,
    $paymentType, $secureHashSecret);

  public function verifyPaymentDatafeed($src, $prc, $successCode,
    $merchantReferenceNumber, $paydollarReferenceNumber,
    $currencyCode, $amount,
    $payerAuthenticationStatus,$secureHashSecret,$secureHash);

}

class SHAPaydollarSecure implements PaydollarSecure {

  public function generatePaymentSecureHash($merchantId, $merchantReferenceNumber, $currencyCode, $amount, $paymentType, $secureHashSecret) {
    $buffer = $merchantId . '|' . $merchantReferenceNumber . '|' . $currencyCode . '|' . $amount . '|' . $paymentType . '|' . $secureHashSecret;
    return sha1($buffer);
  }

  public function verifyPaymentDatafeed($src, $prc, $successCode, $merchantReferenceNumber, $paydollarReferenceNumber, $currencyCode, $amount, $payerAuthenticationStatus, $secureHashSecret, $secureHash) {
    $buffer = $src . '|' . $prc . '|' . $successCode . '|' . $merchantReferenceNumber . '|' . $paydollarReferenceNumber . '|' . $currencyCode . '|' . $amount . '|' . $payerAuthenticationStatus . '|' . $secureHashSecret;
    $verifyData = sha1($buffer);
    if ($secureHash == $verifyData) {
      return TRUE;
    }
    return FALSE;
  }

}