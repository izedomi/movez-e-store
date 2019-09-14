<div class="modal fade" id="buy-product" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3> Product Details </h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p> Product : Robin Shared Hosting </p>
        <p> Unit Price: NGN250, 000 </p>
        <p> Quantity: 1 </p>
        <p> Total Payable: <span class="text-danger">NGN250,000</span></p>
        <form method="post" action="make-payment">
          @csrf
          <div class="form-group">
            <hr class="my-3">
            <p>Payment Method: </p>
            <button type="submit" name="submit" class="btn btn-primary">WALLET</button>
            <button type="submit" name="card" class="btn btn-success">CARD</button>
          </div>
         </form>
      </div>
    </div>
  </div>
</div>
