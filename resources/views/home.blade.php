@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center mt-5">
        <div class="col-md-10 justify-content-center">
            @include('inc.alerts.msg')

            <div class="container">
                  <div class="row">
                    <div class="col-md-12 bg-light border border-info">
                      <div class="row">
                        <div class="col-md-12 py-3">

                            <?php //$walletBalance = "NGN350,000"; ?>
                            @if($data['walletBalance'] > -1)
                            <div class="text-center bg-info p-1 px-5 mb-4 float-left">
                              WALLET BALANCE<br/>
                              <span class="text-white">{{$data['walletBalance']}}</span>
                            </div>
                            @endif

                             <a href="buy-product" class="btn btn-danger float-right m-1">
                               BUY PRODUCT <i class="fa fa-plus fa-fw text-light"></i>
                              </a>

                            <a class="btn btn-success text-white float-right m-1" data-toggle="modal" data-target="#top-up">
                              TOP UP
                            </a>
                            <a class="btn btn-info text-white float-right m-1" data-toggle="modal" data-target="#gift-cash">
                             GIFT CASH
                            </a>

                        </div>
                      </div>
                      <div class="row justify-content-center">

                        <div class="col-md-4 bg-light pb-3 mb-3 border-right border-info">

                          <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                            <h3 class="text-dark mb-3"> Wallet History </h3>
                            <a class="nav-link active" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="true">
                              Check Out<span class="badge badge-light float-right mt-1"></span>
                            </a>
                            <a class="nav-link" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-profile" role="tab" aria-controls="v-pills-profile" aria-selected="false">
                              Top up<span class="badge badge-light float-right mt-1"></span>
                            </a>
                            <a class="nav-link" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-gift-cash" role="tab" aria-controls="v-pills-gift-cash" aria-selected="false">
                              Gift Cash<span class="badge badge-light float-right mt-1"></span>
                            </a>
                          </div>
                        </div>
                        <div class="col-md-8 bg-light py-3">
                          <div class="tab-content" id="v-pills-tabContent">
                              <!-- supplier tab -->
                               <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                                  <h3 class="text-info"> CHECK OUT </h3>
                                  <hr class="mt-2 mb-4">

                                  @if(count($data['checkoutHistories']) > 0)
                                     @foreach($data['checkoutHistories'] as $checkoutHistory)
                                         <p>
                                           <i class="fa fa-arrow-right fa-fw"></i>
                                           {{$checkoutHistory['title']}} on {{$checkoutHistory['updated_at']}}
                                           at {{$checkoutHistory['total']}}
                                           @if($checkoutHistory['payment_type'] == "wallet")
                                               <span class="badge badge-primary text-light float-right mt-1">{{$checkoutHistory['payment_type']}}</span>
                                           @else
                                               <span class="badge badge-success text-light float-right mt-1">{{$checkoutHistory['payment_type']}}</span>
                                           @endif

                                         </p>
                                     @endforeach
                                  @else
                                     <p> No Purchase has been made </p>
                                  @endif

                               </div>
                               <!-- supplier tab -->

                               <!-- top up -->
                               <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                                 <h3 class="mb-2 mt-2 text-info"> TOP-UPs </h3>
                                 <hr class="mt-2 mb-4">

                                     @if(count($data['topupHistories']) > 0)
                                        @foreach($data['topupHistories'] as $topupHistory)
                                            <p><i class="fa fa-arrow-right fa-fw"></i> {{$topupHistory['amount']}} on {{$topupHistory['updated_at']}}</p>
                                        @endforeach
                                     @else
                                        <p> No top-ups </p>
                                     @endif

                               </div>
                               <!-- top up -->

                               <!-- Gift cash -->
                               <div class="tab-pane fade" id="v-pills-gift-cash" role="tabpanel" aria-labelledby="v-pills-gift-card-tab">
                                 <h3 class="mb-2 mt-2 text-info"> WALLET GIFT </h3>
                                 <hr class="mt-2 mb-4">

                                 @if(count($data['giftHistories']) > 0)
                                   @foreach($data['giftHistories'] as $giftHistory)

                                      @if($giftHistory->sender_id == Auth::user()->id)
                                        <p>
                                          You Gifted {{$giftHistory['amount']}} to {{$giftHistory->receiver_name}} on {{$giftHistory->created_at}}
                                          <span class="badge badge-danger float-right mt-1">sent</span>
                                       </p>
                                      @else
                                        <p>
                                          {{$giftHistory->sender_name}} Gifted {{$giftHistory->amount}} to You on {{$giftHistory->created_at}}
                                          <span class="badge badge-success float-right mt-1">Received</span>
                                       </p>
                                      @endif
                                   @endforeach
                                 @else
                                      <p> No Gifts </p>
                                 @endif
                               </div>
                               <!-- gift cash -->
                          </div>
                        </div>

                      </div>
                    </div>
                  </div>
            </div>


            <!-- add new supplier modal -->
            @include('inc.modals.buy-product-modal')

            <!-- add new supplier modal -->

            <!-- bulk transfer modal -->
            @include('inc.modals.top-up-modal')

            <!-- bulk transfer modal -->

            @include('inc.modals.gift-cash-modal')

        </div>
    </div>
</div>

@endsection
