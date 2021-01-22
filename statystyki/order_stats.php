
<?php
include 'cors_headers.php';

function add_order_stats()
{
    $recieved_json = json_decode(file_get_contents('php://input'), true, 512, JSON_UNESCAPED_UNICODE);
    if ($recieved_json == NULL)
        return "Recieved orders JSON is NULL";
    $order_array = [];
    foreach ($recieved_json as $main_key => $values_array) 
    {
        foreach ($values_array as $order_key => $order_values_array) 
        {
            $order_array[$order_key] = $order_values_array;
        }
        
    }
//CREATE TABLE ORDERS(id INTEGER PRIMARY KEY AUTOINCREMENT, order_date DATE, new_client INTEGER, marketing_permit INTEGER, sex INTEGER, delivery_place TEXT, discount_code TEXT);
//CREATE TABLE MEALS(id INTEGER PRIMARY KEY AUTOINCREMENT, order_id INTEGER, meal_name TEXT, meal_price REAL, category_name TEXT);
//CREATE TABLE ADDITIONS(id INTEGER PRIMARY KEY AUTOINCREMENT, order_id INTEGER, addition_name TEXT, addition_price REAL);
//CREATE TABLE CAMPAIGNS(id INTEGER PRIMARY KEY AUTOINCREMENT, campaign_name TEXT, campaign_type INTEGER, messages_count INTEGER);

    try  {  
        $db = new PDO("sqlite:stats.db");
        $order_date = $recieved_json["ordered_date"];
        //$new_client = $recieved_json["new_client"];
        $marketing_permit = $recieved_json["marketing_permit"];
        $sex = $recieved_json["sex"];
        $delivery_place = $recieved_json["delivery_place"];
        $discount_code = $recieved_json["discount_code"];
        $db->exec("INSERT INTO ORDERS(order_date, marketing_permit, sex, delivery_place, discount_code) values('$order_date', '$marketing_permit', '$sex', '$delivery_place', '$discount_code')");
    }
    catch(PDOException $e) {
        echo $e->getMessage();
        echo "<br><br>Database <b>NOT</b> loaded successfully. ";
        die( "<br><br>Query Closed! $e");
    } 

    try  {  
        $db = new PDO("sqlite:stats.db");
        $results = $db->query("SELECT MAX(id) FROM ORDERS");

        while ($row = $results->fetch(\PDO::FETCH_ASSOC)){
            $order_array['id'] = $row['id'];
        }
    }
    catch(PDOException $e) {
        echo $e->getMessage();
        echo "<br><br>Database <b>NOT</b> loaded successfully. ";
        die( "<br><br>Query Closed! $e");
    } 
    add_ordered_meal($order_array);
    return "OK";
}

function add_ordered_meal($order){
    try  {  
        $db = new PDO("sqlite:stats.db");
        $order_date = $recieved_json["ordered_date"];
        //$new_client = $recieved_json["new_client"];
        $marketing_permit = $recieved_json["marketing_permit"];
        $sex = $recieved_json["sex"];
        $delivery_place = $recieved_json["delivery_place"];
        $discount_code = $recieved_json["discount_code"];
        $db->exec("INSERT INTO ORDERS(order_date, marketing_permit, sex, delivery_place, discount_code) values('$order_date', '$marketing_permit', '$sex', '$delivery_place', '$discount_code')");
    }
    catch(PDOException $e) {
        echo $e->getMessage();
        echo "<br><br>Database <b>NOT</b> loaded successfully. ";
        die( "<br><br>Query Closed! $e");
    } 
}

function add_ordered_additions($order){

}

?>
