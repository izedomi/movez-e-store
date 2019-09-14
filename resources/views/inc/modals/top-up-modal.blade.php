<div class="modal fade" id="top-up" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">TOP UP</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" action="top-up">
          @csrf
          <div class="form-group">
            <label for="amount" class="col-form-label text-danger">Enter Top-up Amount</label>
            <input type="text" class="form-control" name="amount" id="amount" placeholder="e.g 25000">
          </div>
          <div class="form-group">
            <hr class="my-3">
            <button type="submit" name="submit" class="btn btn-primary">Top-Up</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
