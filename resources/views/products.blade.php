@extends("layouts.products")
@section("content")

    <div class="container my-5">
        <div class="row">
           <div  class="card-deck">
            @if(count($products))
            @foreach($products as $product)
               <div class="col-md-4 mb-4">
                <div class="card">
                  <img src="http://localhost/lpseven/public/img/{{$product->image}}" alt="Card image cap" height="400px">
                  <div class="card-body">
                    <h5 class="card-title">{{$product->title}}</h5>
                    <p><small class="card-text">{{$product->desc}}</small></p>
                    <div class="d-flex flex-row">
                      <span class="mt-2 mr-4">{{$product->formatted_amount}}</span>
                      <form method="post" action="checkout" class="pl-3 float-right">
                          @csrf
                          <input type="hidden" name="title" value="{{$product->title}}" />
                          <input type="hidden" name="amount" value="{{$product->amount}}" />
                          <input type="hidden" name="image" value="{{$product->image}}" />
                          <input type="number" value="1" name="qty" style="width:40px" />
                          <button type="submit" name="submit" class="btn btn-success"> CHECK OUT </button>
                      </form>
                    </div>
                  </div>
                </div>
               </div>
             @endforeach
             @else
                <h3 class="text-white"> No Movies Available </h3>
             @endif
          </div>
       </div>
    </div>
@endsection
