<?php


namespace App\Http\Controllers;


use App\User;
use App\CustomerWallet;
use App\GiftHistory;
use App\TopupHistory;
use App\CheckoutHistory;
use App\Products;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Utility;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(){$this->middleware('auth');}

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
     public function index(){
         $all_users = User::all();
         $gift_history = GiftHistory::where('sender_id', Auth::user()->id)->orWhere("receiver_id", Auth::user()->id)->get();
         $topup_history = TopupHistory::where('customer_id', Auth::user()->id)->where('status', 1)->get();
         $checkout_history = CheckoutHistory::where('customer_id', Auth::user()->id)->where('payment_type', '!=', "null")->get();
         $walletBalance = Utility::amount_delimeter($this->get_wallet_balance());

         foreach ($gift_history as $gift) {
           $gift->amount = Utility::amount_delimeter($gift->amount);
         }
         foreach ($topup_history as $topup) {
           $topup->amount = Utility::amount_delimeter($topup->amount);
         }


        //return $gift_history;
       //return $topup_history;
       //return $checkout_history;
         $data = array(
           'users' => $all_users,
           'walletBalance' => $walletBalance,
           'giftHistories' => $gift_history,
           'topupHistories' => $topup_history,
           'checkoutHistories' => $checkout_history
         );

         return view('home')->with('data', $data);
     }


    //post
    public function top_up(Request $request){

      //return $request->all();

      $email = Auth::user()->email;
      $reference = Utility::reference();
      $amount = (int) trim($request->input('amount'));
      $amount_in_kobo = $amount * 100;
      $callback_url = "http://movez-e-store.masterscad.com.ng/top-up-success";

      $newTopup = new TopupHistory();
      $newTopup->customer_id = Auth::user()->id;
      $newTopup->amount = $amount;
      $newTopup->status = 0;
      $newTopup->ref = $reference;
      $newTopup->save();


      /*
      $topup = TopupHistory::where('customer_id', Auth::user()->id)->where('ref', $reference)->get();
      $wallet = CustomerWallet::where('customer_id', Auth::user()->id)->get();

      $topup_amount = (int)$topup[0]['amount'];
      $existing_balance = (int)$wallet[0]['wallet_balance'];
      $new_wallet_balance = $existing_balance + $topup_amount;

      return $new_wallet_balance;
      */

      // simulate success
      //return redirect("top-up-success?reference={$reference}");

      //uncomment me when live
      return $this->initialize_payment($email, $amount_in_kobo, $callback_url, $reference);

    }
    public function gift_cash(Request $request){
      $this->validate($request, [
         'user_id' => 'required',
         'amount' => 'required',

      ]);

      //return $request;

      //amount
      $amount_to_gift = (int) $request->input('amount');

      //sender
      $receiver_id = $request->input('user_id');
      $sender_id = Auth::user()->id;

      //balance check
      $walletBalance = $this->get_wallet_balance();
      //return $walletBalance;
      if($amount_to_gift > $walletBalance){
        return redirect("/home")->with("error", "You cannot gift more than your wallet balance!!!");
      }

      // calculate new sender wallet balance and save
      $sender = CustomerWallet::where('customer_id', $sender_id)->get();
      $sender_existing_balance = $sender[0]->wallet_balance;
      $sender_current_balance = $sender_existing_balance - $amount_to_gift;

      if(count($sender) > 0){
        $sender[0]->wallet_balance = $sender_current_balance;
        $sender[0]->save();
      }


      // calculate new receiver wallet balance and save
      $receiver = CustomerWallet::where('customer_id', $receiver_id)->get();
      $receiver_existing_balance = $receiver[0]->wallet_balance;
      $sender_current_balance = $receiver_existing_balance + $amount_to_gift;
      if(count($receiver) > 0){
        $receiver[0]->wallet_balance = $sender_current_balance;
        $receiver[0]->save();
      }


      // keep record
      $newGift = new GiftHistory();
      $newGift->sender_id = $sender_id;
      $newGift->receiver_id = $receiver_id;
      $newGift->sender_name = $sender[0]->customer_name;
      $newGift->receiver_name = $receiver[0]->customer_name;
      $newGift->amount = $amount_to_gift;
      $newGift->save();

      return redirect("/home")->with("success", "Cash Gifted Successfully");

    }
    public function checkout(Request $request){

        $purchase_with_wallet = false;

        $wallet = CustomerWallet::where('customer_id', Auth::user()->id)->get();
        if(count($wallet) > 0){
            $wallet_balance = $wallet[0]->wallet_balance;
        }

        if((int)$request->amount < $wallet_balance){
           $purchase_with_wallet = true;
        }

        $data = array(
          'title' => $request->title,
          'amount' => Utility::amount_delimeter($request->amount),
          'qty' => $request->qty,
          'total' => (int)$request->qty * (int)$request->amount,
          'formatted_total' => Utility::amount_delimeter((int)$request->qty * (int)$request->amount),
          'image' => $request->image,
          'wallet_purchase' => $purchase_with_wallet
        );

        return view('checkout')->with('data', $data);
    }
    public function pay(Request $request){
      //  return $request;
        if($request->submit == "wallet"){
            return $this->pay_with_wallet($request);
        }
        if($request->submit == "card"){
          return $this->pay_with_card($request);
        }
    }
    private function pay_with_wallet($request){

      $wallet = CustomerWallet::where('customer_id', Auth::user()->id)->get();
      if(count($wallet) > 0){
        $existing_balance = $wallet[0]->wallet_balance;
        $wallet[0]->wallet_balance = (int)$existing_balance - $request->checkout_price;
        $wallet[0]->save();
      }
      $newCheckoutHistory = new CheckoutHistory();
      $newCheckoutHistory->customer_id = Auth::user()->id;
      $newCheckoutHistory->title = $request->title;
      $newCheckoutHistory->qty = (int)$request->qty;
      $newCheckoutHistory->total = Utility::amount_delimeter($request->checkout_price);
      $newCheckoutHistory->unit_price = (int)$request->checkout_price / $request->qty;
      $newCheckoutHistory->payment_ref = Utility::reference();
      $newCheckoutHistory->payment_type = "wallet";
      $newCheckoutHistory->save();

      return redirect('/home')->with('success', "Order Completed Successfully");
    }
    private function pay_with_card(Request $request){

      $email = Auth::user()->email;
      $reference = Utility::reference();
      $amount = (int) trim($request->checkout_price);
      $amount_in_kobo = $amount * 100;
      $callback_url = "http://movez-e-store.masterscad.com.ng/checkout-success";

      $newCheckoutHistory = new CheckoutHistory();
      $newCheckoutHistory->customer_id = Auth::user()->id;
      $newCheckoutHistory->title = $request->title;
      $newCheckoutHistory->qty = (int)$request->qty;
      $newCheckoutHistory->total = Utility::amount_delimeter($request->checkout_price);
      $newCheckoutHistory->unit_price = (int)$request->checkout_price / $request->qty;
      $newCheckoutHistory->payment_ref = $reference;
      $newCheckoutHistory->payment_type = "null";
      $newCheckoutHistory->save();

      // simulate success
      return redirect("checkout-success?reference={$reference}");

      //uncomment me when live
      return $this->initialize_payment($email, $amount_in_kobo, $callback_url, $reference);

    }

    //get
    public function get_wallet_balance(){
      $customer_id = Auth::user()->id;
      $user = CustomerWallet::where('customer_id', $customer_id)->get();
      if(count($user) > 0){
        $walletBalance = $user[0]->wallet_balance;
        return (int)$walletBalance;
      }
    }
    public function buy_product(){

      $all_products = Products::all();
      foreach($all_products as $product){
        $product->formatted_amount = Utility::amount_delimeter($product->amount);
      }
      //return $all_products;
      return view('products')->with('products', $all_products);

    }
    public function top_up_successful(){


      if(isset($_GET["reference"])){

          $ref = trim($_GET["reference"]);

          $topup = TopupHistory::where('customer_id', Auth::user()->id)->where('ref', $ref)->get();
          $wallet = CustomerWallet::where('customer_id', Auth::user()->id)->get();

          if(count($topup) < 1){
            return redirect('/home')->with('error', 'Ooops..we encountered an error, please try again!');
          }

          if(count($topup) > 0 && count($wallet) > 0){
              $topup_amount = (int)$topup[0]->amount;
              $existing_balance = (int)$wallet[0]->wallet_balance;
              $new_wallet_balance = $existing_balance + $topup_amount;


              if($topup[0]['ref'] == $ref){

                //update top-up status after successful top-up
                $topup[0]['status'] = 1;
                $topup[0]->save();

                //update wallet balance after topup
                $wallet[0]->wallet_balance = $new_wallet_balance;
                $wallet[0]->save();

                return redirect('/home')->with('success', 'Top up successful');
              }
              else{
                $topup[0]->delete();
                return redirect('/home')->with('error', 'Ooops..we encountered an errorr, please try again!');
              }

          }
      }
      else{
          return redirect('/home')->with('error', 'Ooops..we encountered an errorrrr, please try again!');
      }

    }
    public function check_out_success(){

      if(isset($_GET["reference"])){
          $ref = trim($_GET["reference"]);

          $checkout = CheckoutHistory::where('customer_id', Auth::user()->id)->where('payment_ref', $ref)->get();

          if(count($checkout) < 1){
            return redirect('/home')->with('error', 'Ooops..we encountered an error, please try again!');
          }
          else{
            if($checkout[0]['payment_ref'] == $ref){

              $checkout[0]['payment_type'] = "card";
              $checkout[0]->save();

              return redirect('/home')->with('success', 'Item Order Successfully!');
            }
            else{
              $topup[0]->delete();
              return redirect('/home')->with('error', 'Ooops..we encountered an errorr, please try again!');
            }
          }

      }
      else{
          return redirect('/home')->with('error', 'Ooops..we encountered an errorrr, please try again!');
      }

    }

    //API
    private function initialize_payment($email, $amount, $callback_url, $reference){

      $curl = curl_init();
      curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.paystack.co/transaction/initialize",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode([
          'amount'=>"{$amount}",
          'email'=>$email,
          'callback_url'=>$callback_url,
          'reference'=>$reference,
        ]),
        CURLOPT_HTTPHEADER => [
          //"authorization: Bearer sk_test_50a81d6e3035dfd39a64e14a03e05b824e913e2f",
          config('app.secret_key'),
          "content-type: application/json",
          "cache-control: no-cache"
        ],
      ));

      $response = curl_exec($curl);
      $err = curl_error($curl);

      if($err){
        // there was an error contacting the Paystack API
        return redirect("/home")->with("error", $err);
      }

      $tranx = json_decode($response, true);

      //return $tranx;

      if(!$tranx["status"]){
         return redirect("/home")->with("error", $tranx['message']);
      }

      if(!$tranx['data']['authorization_url']){
        // there was an error from the API
         return redirect("/home")->with("error", $tranx['message']);
      }

      return redirect($tranx['data']['authorization_url']);
      exit();
    }


}
