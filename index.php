<?php
require_once('stripe-lib/init.php');
require_once('Stripe.php');
$stripe_obj = new stripe_api(); 

$email = "testemailstrip@gmail.com"; 
$subscription_planid = "test1"; // this plan id must be present inside Stripe.
$cancel_subscription_planid ="test1";
$trial_period = 30;

// Find Customer ID In Stripe.
$stripe_customer_id = $stripe_obj->find_customerid($email);

// Add Subscription
//echo $response = $stripe_obj->add_subscription($stripe_customer_id,$subscription_planid,$trial_period);

// Cancel Subscription
//echo $response =   $stripe_obj->cancel_subscription($stripe_customer_id, $cancel_subscription_planid);

?>