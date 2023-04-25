<?php
    include_once(dirname(__FILE__) . '/../config/db.php');
    include_once(dirname(__FILE__) . '/../middleware/error.php');


class Order
{
    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->connect();
    }
    public function getAllOrder($id){ // id of customer
        try{
            $query = "SELECT * FROM orders WHERE CustomerID='$id';";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
        } catch (mysqli_sql_exception $e) {
            throw new InternalServerError('Server Error !');
        }
    }
    public function getOrder($id){ // id of 1 order
        try{
            $query = "SELECT * FROM orders JOIN include ON orders.OrderID = include.OrderID WHERE orders.OrderID='$id'";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
        } catch (mysqli_sql_exception $e) {
            throw new InternalServerError('Server Error !');
        }
    }
    public function confirm($id){
        try{
            $query = "UPDATE orders SET status = 1  WHERE OrderID='$id'";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
        } catch (mysqli_sql_exception $e) {
            throw new InternalServerError('Server Error !');
        }
    }
    public function makeOrder($info){
        try{
            $CUSTOMER = $info['CUSTOMER'];
            $NAME = $info['NAME'];
            $PAY = $info['PAY'];
            $NOTE = $info['NOTE'];
            $PHONE = $info['PHONE'];
            $ADD = $info['ADD'];
            $COST = $info['COST'];
            $TOTAL_PRODUCT = $info['NUM'];
        
            $query = "INSERT INTO include (ProductID,COLOR,SIZE,NUMBER,OrderID) SELECT ProductID, COLOR, SIZE, NUMBER, OrderID FROM add_to_cart JOIN 
                ( SELECT AUTO_INCREMENT AS OrderID FROM information_schema.TABLES
                WHERE TABLE_SCHEMA = 'ltw' AND TABLE_NAME = 'orders') AS TEMP WHERE CustomerID='$CUSTOMER';";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();

            $query = "INSERT INTO orders (CUSTOMERID,NAME,TOTAL_PRODUCT,TOTAL_COST,PAY_METHOD,NOTE,RECEIVE_PHONE,RECEIVE_ADDRESS) VALUES ('$CUSTOMER','$NAME','$TOTAL_PRODUCT','$COST','$PAY','$NOTE','$PHONE','$ADD')";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();

            $query = "DELETE add_to_cart WHERE CutomerID='$CUSTOMER'";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();

        } catch (mysqli_sql_exception $e) {
            throw new InternalServerError('Server Error !');
        }
    }
    

}