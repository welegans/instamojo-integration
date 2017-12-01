<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class Instamojo extends Controller
{
    /*
    Create Payment for the order of Customer
    @request contains
    @purpose = "pass order id or unqiue id to identify the payment made by"
    @amount = "amount to pay"
    @phone = "Phone no of the payee"
    @buyer_name = "Name of the payee"
    @email =  "email id of the payee"
    */
    public function createPayment(Request $request)
    {
      $domain_url = "https://nmuzjhigsr.localtunnel.me";
      //"http://127.0.0.1:8000";
    //paramters to be passed to payment take from the order/request
    $body = [
    'purpose' => ' pass custom unique order id',
    'amount' => '2500',
    'phone' => '9999999999',
    'buyer_name' => 'John Doe',
    'email' => '2713@gmail.com',
    'redirect_url' => $domain_url.'/paymentStatus',
    'send_email' => false,
    'webhook' => $domain_url,
    'send_sms' => false,
    'allow_repeated_payments' => false
    ];

    //make http create payment requests
    $res = $this->makeHttpCall('POST', 'payment-requests/', [], 'form_params', $body);

    //Check the status of the request
    $statusCode = $res->getStatusCode();

    //If success i.e 201 created forward the Customer.
    if($statusCode == 201){
      $body = json_decode($res->getBody());
      //redirect to the payment interface.
      return redirect($body->payment_request->longurl);
    }else{
      return "handle the payment creation failure and let the user try again";
    }

    }
    /*
    After payment user is redirected here
    @request param contains
    @payment_id = "payment id is passed in the redirect url"
    @payment_request_id = "payment_request_id is passed in the redirect url"
    AFTER THE PAYMENT INTERFACE USER IS REDIRECTED TO THIS CONTROLLER
    Http call to get the details of the payment using the payment_id
    Process logic on succesfull payment and Failed payment
    */
    public function paymentDetails(Request $request)
    {
      $payment_id = $request->payment_id;
      $payment_request_id = $request->payment_request_id;
      //passsing bodytype and body as null as 'GET' doesn't contains request body
      $res = $this->makeHttpCall('GET', 'payments/'.$payment_id, [], null, null);
      //check status code of response
      $statusCode = $res->getStatusCode();
      if($statusCode == 200){
        //Get the json body from the response
        $body = json_decode($res->getBody());
        //if payment success
        if($body->success == true){
          return "payment is success";
        }else{
          return "Payment has failed";
        }
      }else{
        return "Error in the http call handle it";
      }

    }
    /*
    Creating http call for instamojo API/1.1
    @httpMethod = "GET/POST/"
    @url = "payments/" after the base url "https://test.instamojo.com/api/1.1/"
    @headers = pass extra header e.g ["key"=>"value"] if required exculding [X-Api-Key,X-Auth-Token]
    @bodyType = "form_params(application/x-www-form-urlencoded)/multipart(multipart/form-data)/body(raw data)/"
    @body = passing the body data after setting the type of body
    */
    public function makeHttpCall($httpMethod, $url, $headers, $bodyType, $body)
    {
        $instamojo_url = "https://test.instamojo.com/api/1.1/";
        //Create GuzzleHttp Client with base uri
        $client = new Client([
          'base_uri' => $instamojo_url,
        ]);
        //Set the instamojo API credentials
        $instamojo_cred = ["X-Api-Key"=>"a548dba7d6e9a248c5db47b8f75ae749",
          "X-Auth-Token"=> "6367883f83561504447a26ea21192f76"
        ];
        //merging the credentials and headers passed params
        $headers = array_merge($instamojo_cred,$headers);

        //Making request to Insatamojo $res is the response recieved
        $res = $client->request($httpMethod, $url, ['headers'=>$headers, $bodyType=>$body]);
        return $res;
    }
}
