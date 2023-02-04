<?php

namespace Sample;

require __DIR__ . 'vendor/autoload.php';

use Sample\PayPalClient;
use PayPalCheckoutSdk\Orders\OrdersGetRequest;
use Sample\CaptureIntentExamples\CreateOrder;

// include "../order/choose-payment.php";


require './paypal-client.php';
$orderID = $_GET['orderID'];

echo "success page";

include "../admin/conn.php";
include "../admin/auth.php";

$user_id = $_SESSION['id'];

// echo $user_id;


$order = "SELECT * FROM orders WHERE user_id = $user_id ORDER BY id DESC";
$result = mysqli_query($con, $order);
$fetch = mysqli_fetch_array($result);
$order_no = $fetch['id'];

// $order_no = $id;

// echo $order_no;


class GetOrder
{

    /**
     * This function can be used to retrieve an order by passing order Id as argument.
     */
    public static function getOrder($orderId)
    {


        // 3. Call PayPal to get the transaction details
        $client = PayPalClient::client();
        $response = $client->execute(new OrdersGetRequest($orderId));

        // transaction details
        $orderID = $response->result->id;
        $email = $response->result->payer->email_address;
        $name = $response->result->purchase_units[0]->shipping->name->full_name;
        $payer_id = $response->result->payer->payer_id;
        $amount = $response->result->purchase_units[0]->amount->value;
        $currency = $response->result->purchase_units[0]->amount->currency_code;
        $transaction_id = $response->result->purchase_units[0]->payments->captures[0]->id;
        $status = $response->result->purchase_units[0]->payments->captures[0]->status;
        $address1 = $response->result->purchase_units[0]->address->address_line_1;
        $address2 = $response->result->purchase_units[0]->address->admin_area_2;
        $address3 = $response->result->purchase_units[0]->address->admin_area_1;
        $address4 = $response->result->purchase_units[0]->address->postal_code;
        $address5 = $response->result->purchase_units[0]->address->country_code;
        $FullAddress = $address1 . ", " . $address2 . ", " . $address3 . ", " . $address4 . ", " . $address5;

        // insert data to a database
        include("../admin/conn.php");
        include "../admin/auth.php";

        $user_id = $_SESSION['id'];

        $order = "SELECT * FROM orders WHERE user_id = $user_id ORDER BY id DESC";
        $result = mysqli_query($con, $order);
        $fetch = mysqli_fetch_array($result);
        $order_no = $fetch['id'];

        $stmt = $con->prepare('INSERT INTO `payments` (ord_id, user_id, name, email, payer_id, order_id, transaction_id, amount, currency, status, address) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->bind_param(
            'sssssssssss',
            $order_no,
            $user_id,
            $name,
            $email,
            $payer_id,
            $orderID,
            $transaction_id,
            $amount,
            $currency,
            $status,
            $FullAddress
        );
        $stmt->execute();



        if (!$stmt) {
            echo "An error occurred" . mysqli_error($con);
            print_r("An error occurred" . mysqli_error($con));
        } else {



            $nxt = mysqli_query($con, "SELECT * FROM orders WHERE id = $order_no");

            while ($pg = mysqli_fetch_assoc($nxt)) {
                header("location: https://graduate-essay-helpers.com/order/choose-payment.php?id=" . $pg['id']);
                exit;
            }

        }
        $stmt->close();
        $con->close();

        // print "Status Code: ($response->statusCode)\n";
        // print "Status: ($response->result->status)\n";
        // print "Order ID: ($response->result->id)\n";
        // print "Intent: ($response->response->intent)\n";
        // print "Link:\n";
        // foreach($response->result->links as $link){}
    }
}

if (!count(debug_backtrace())) {
    GetOrder::getOrder($orderID, true);
}
