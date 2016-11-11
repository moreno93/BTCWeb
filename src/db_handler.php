<?php

class DbHandler
{
    private $conn;

    function __construct() {
        require_once dirname(__FILE__) . '/db_connect.php';
        $db = new DbConnect();
        $this->conn = $db->connect();
    }

    public function getBTCValue(){
        $decode = file_get_contents("https://btc-e.com/api/3/ticker/btc_usd");
        $BTCJson = json_decode($decode, true);
        $BTCvalue = $BTCJson["btc_usd"]["last"];

        return $BTCvalue;
    }

    public function updateDatabase($value){
        var_dump($value);
        $stmt = $this->conn->prepare("INSERT INTO BTC_values(value) values (?)");
        var_dump($stmt);
        $stmt->bind_param("d", $value);

        $result = $stmt->execute();
        var_dump($result);

        if($result) echo "Updated!";
        else echo "Error!";

        $stmt->close();

    }

}