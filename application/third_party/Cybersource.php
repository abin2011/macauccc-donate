<?php
/**
 * Cybersource 支付类
 * 暫時棄用
 */
class Cybersource{
  public function sign($params, $secretKey){
    return $this->signData($this->buildDataToSign($params), $secretKey);
  }

  public function signData($data, $secretKey){
    return base64_encode(hash_hmac('sha256', $data, $secretKey, true));
  }

  public function buildDataToSign($params){
    $signedFieldNames = explode(",",$params["signed_field_names"]);
    foreach ($signedFieldNames as $field){
      $dataToSign[] = $field . "=" . $params[$field];
    }
    return $this->commaSeparate($dataToSign);
  }

  public function commaSeparate($dataToSign){
    return implode(",",$dataToSign);
  }
}
?>