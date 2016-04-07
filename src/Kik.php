<?php

class Kik {

  const API_URL = 'https://api.kik.com/v1/';

  private $_username;

  private $_api_key;

  private $_base_url;

  private $_feature_manually_send_read_receipts = FALSE;

  private $_feature_receive_read_receipts = FALSE;

  private $_feature_receive_delivery_receipts = FALSE;

  private $_feature_receive_is_typing = FALSE;

  public function __construct($config) {
    if (is_array($config)) {
      $this->setUsername($config['username']);
      $this->setApiKey($config['api_key']);
      $this->setBaseUrl($config['base_url']);
      if(isset($config['features'])) {
        $this->setFeatures($config['features']);
      }
    }
  }

  public function setUsername($username) {
    $this->_username = $username;
  }

  public function getUsername() {
    return $this->_username;
  }

  public function setApiKey($apiKey) {
    $this->_api_key = $apiKey;
  }

  public function getApiKey() {
    return $this->_api_key;
  }

  public function setBaseUrl($baseUrl) {
    $this->_base_url = $baseUrl;
  }

  public function getBaseUrl() {
    return $this->_base_url;
  }

  public function setFeatures($features) {
    if(isset($features['manuallySendReadReceipts'])) {
      $this->_feature_manually_send_read_receipts = $features['manuallySendReadReceipts'];
    }
    if(isset($features['receiveReadReceipts'])) {
      $this->_feature_receive_read_receipts = $features['receiveReadReceipts'];
    }
    if(isset($features['receiveDeliveryReceipts'])) {
      $this->_feature_receive_delivery_receipts = $features['receiveDeliveryReceipts'];
    }
    if(isset($features['receiveIsTyping'])) {
      $this->_feature_receive_is_typing = $features['receiveIsTyping'];
    }
  }

  public function getFeatures() {

    return array(
      'manuallySendReadReceipts' => $this->_feature_manually_send_read_receipts,
      'receiveReadReceipts' => $this->_feature_receive_read_receipts,
      'receiveDeliveryReceipts' => $this->_feature_receive_delivery_receipts,
      'receiveIsTyping' => $this->_feature_receive_is_typing
    );

  }

  public function configure() {

    return $this->_makeApiCall('config', array(
      'webhook' => $this->_base_url,
      'features' => array(
        'manuallySendReadReceipts' => $this->_feature_manually_send_read_receipts,
        'receiveReadReceipts' => $this->_feature_receive_read_receipts,
        'receiveDeliveryReceipts' => $this->_feature_receive_delivery_receipts,
        'receiveIsTyping' => $this->_feature_receive_is_typing
      )
    ));

  }

  public function _makeApiCall($path, $apiData)
  {

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, self::API_URL . $path);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($apiData));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      'Content-Type: application/json'
    ));
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_USERPWD, $this->_username . ':' . $this->_api_key);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 90);
    $jsonData = curl_exec($ch);

    curl_close($ch);

    return $jsonData;
  }

}
