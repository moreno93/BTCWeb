<?php

class BTC
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
        $stmt->close();

        if($result) return true;
        else throw new Exception("Cannot update database");



    }

    public function getGraphData(){
        $stmt = $this->conn->prepare("SELECT value, created_at FROM BTC_values WHERE created_at >= TIMESTAMPADD(DAY,-1,NOW())");
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
            throw new Exception("Cannot fetch graph data");
        }
    }

    public function deleteOldDbRecords(){
        $stmt = $this->conn->prepare("DELETE FROM BTC_values WHERE created_at < TIMESTAMPADD(DAY,-1,NOW())");
        $result = $stmt->execute();
        $stmt->close();

        if($result) return true;
        else throw new Exception("Cannot delete from database");

    }

}
