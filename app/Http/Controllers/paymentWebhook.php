<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class paymentWebhook extends Controller
{
    /*
    Instamojo will hit this webhoook with the following parameters
    @amount = "amount charged"
    @buyer = "Buyer email id"
    @buyer_name = "Buyer name"
    @buyer_phone = "Buyer Phone"
    @currency = "Currency related to the paymentCurrency related to the payment"
    @fees = "Fees charged by Instamojo e.g 125.00"
    @longurl = "URL related to the payment request"
    @mac = "Message Authentication code of this webhook request"
    @payment_id = "payment id"
    @payment_request_id = "Id of payment request"
    @purpose = "unqiue or order id"
    @shorturl = "Short URL of the payment request"
    @status = "Status of the Payment. This can be either 'Credit' or 'Failed'."
    */
    public function payment(Request $request)
    {
      dd($request->all());
      //Check payment done or failed by status field
      //To do the logic of succesfull payment
      //Send email to the user
      //do other stuff business logic for successfull payment
    }
}
