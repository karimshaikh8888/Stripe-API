<?php
Class stripe_api{
	
   function __construct(){
		
		 \Stripe\Stripe::setApiKey(""); // Add Stripe Secret Key
	}
	
	/**************Find customer ID from email address***********/
	public function find_customerid($email){
	   $last_customer = NULL;
		while (true) {
			$customers = \Stripe\Customer::all(array("limit" => 500, "starting_after" => $last_customer));
			foreach ($customers->autoPagingIterator() as $customer) {
				if ($customer->email == $email) {
					$customerIamLookingFor = $customer;
					return $customerIamLookingFor['id'];
					echo "Customer Id Found<br>";
					break 2;
				}
			}
			if (!$customers->has_more) {
				break;
			}
			$last_customer = end($customers->data);
		}
	  return null;
	}
	
	/**************Add Subscription to Contact************/
	public function add_subscription($customer_id,$subscription_planid,$trial_period){
		$add_subscriptionid = $this->check_subscription_planid_exists($customer_id, $subscription_planid);
		
		if($subscription_planid == $add_subscriptionid){
		  return "Added Subscription Already Present to User<br>";
		 // return $customer_id;
		  break;
		}else{
		 $data =	\Stripe\Subscription::create(array(
		  "customer" => $customer_id,
		  "items" => array(
			array(
			  "plan" => $subscription_planid,
			),
		  
		  ),
		  "trial_period_days" => $trial_period,
		 ));	  
		 return "Subscription Applied to User =>".$subscription_planid;
		// return $subscription_planid;
		}
		
	}
	
	/**************Find Subscription Plan id from given Plan ID for customer************/
	public function check_subscription_planid_exists($customerid, $given_subscription_plan_id){
		  $subscriptions_data = $this->retrive_allsubcription($customerid);
		   //echo count($subscriptions_data->data); 
			foreach ($subscriptions_data->data as $subscription) {
				 foreach ($subscription->items->data as $plan) {
					$subscription_plan_id = $plan->plan->id;
					if($subscription_plan_id == $given_subscription_plan_id){
						return $given_subscription_plan_id; //subscription plan already exist for user.
						break;
					}
				 }
			}
	 return null;
	}
	
	/**************Retrieve all Subscription to Contact************/
	public function retrive_allsubcription($customerId){
		return \Stripe\Subscription::all(array('customer'=>$customerId,'limit'=>50));
	}
		
	/**************Cancel Subscription to Contact************/	
	public function cancel_subscription($customerid,$cancel_subscriptionplanid){
		$cancel_subscriptionid = $this->find_subscription_id($customerid, $cancel_subscriptionplanid);
		if(!empty($cancel_subscriptionid)){
		 \Stripe\Subscription::retrieve($cancel_subscriptionid)->cancel();
		  return "Subscription Cancelled for user =>".$cancel_subscriptionplanid;	  
		 // return $cancel_subscriptionplanid;
		}else{
			 return "Given Cancel Subscription Not Present for user<br>";		 
			 //return null;
		}
		
	}
	
	/**************Find Subscription id from given Plan ID for customer************/
	public function find_subscription_id($customerid, $cancelsubscription_plan_id){
		  $subscriptions_data = $this->retrive_allsubcription($customerid);
		   //echo count($subscriptions_data->data); 
			foreach ($subscriptions_data->data as $subscription) {
				$cancel_subscription_id = $subscription->id;
				//echo "<br>"."Subscription ID=>".$cancel_subscription_id;
				 foreach ($subscription->items->data as $plan) {
					$subscription_plan_id = $plan->plan->id;
					if($subscription_plan_id==$cancelsubscription_plan_id){
						return $cancel_subscription_id; // subscription ID present for user.
						break;
					}
				 }
			}
	 return null;
	}
	
	
}
?>