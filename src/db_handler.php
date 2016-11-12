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
        $stmt = $this->conn->prepare("INSERT INTO BTC_values(value) values (?)");
        $stmt->bind_param("d", $value);
        $result = $stmt->execute();

        if($result) echo "Updated!";
        else echo "Error!";

        $stmt->close();

    }

    public function getGraphData(){
        $stmt = $this->conn->prepare("SELECT value, created_at FROM BTC_values");
        $result = $stmt->execute();
        $records = $stmt->get_result()->fetch_all();
        $data = array();


        foreach ($records as $record){
            $data[] = $record;
        }

        $stmt->close();
        if($result){
            return $data;
        } else {
            return -1;
        }
    }

}