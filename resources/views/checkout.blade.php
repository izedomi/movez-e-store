@extends("layouts.products")
@section("content")

       <div class="col-md-4 mb-4 my-5">
        <div class="card">
          <h3 class="text-center my-3"> CHECK OUT </h3>
          <img width="92%" class="text-center mx-3" src="http://localhost/lpseven/public/img/{{$data['image']}}" alt="Card image cap" height="250px">
          <div class="card-body">
            <p class="card-title">PAYMENT DETAILS</p>
            <hr>
            <p class="lead">Movie: {{$data['title']}}</p>
            <p class="lead">Price: {{$data['amount']}}</p>
            <p class="lead">Quantity: {{$data['qty']}}</p>
            <p class="lead text-danger">Total Due: {{$data['formatted_total']}}</p>
            <hr>
            <p> Payment Option </p>
            <form method="post" action="pay">
                @csrf
                <input type="hidden" name="title" value="{{$data['title']}}" />
                <input type="hidden" name="checkout_price" value="{{$data['total']}}" />
                <input type="hidden" name="qty" value="{{$data['qty']}}" />
                <button type="submit" name="submit"  value="wallet" class="btn btn-primary" <?php if(!$data['wallet_purchase']){echo "disabled='true'";} ?>>
                   <i class="fa fa-google-wallet fa-fw"></i> MY WALLET
                </button>
                <button type="submit" name="submit" value="card" class="btn btn-success"> <i class="fa fa-credit-card fa-fw"></i>CARD </button>
             </form>

          </div>
        </div>
       </div>

@endsection
