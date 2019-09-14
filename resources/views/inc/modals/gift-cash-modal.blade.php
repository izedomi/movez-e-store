<div class="modal fade" id="gift-cash" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">GIFT CASH</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" action="gift-cash">
          @csrf
          <div class="form-group">
            <label for="message-text" class="col-form-label">Select Recipient</label>
              <select name="user_id" class="custom-select" required>
                @if(count($data['users']) > 0)
                   @foreach($data['users'] as $user)
                    @if(Auth::user()->id != $user->id)
                      <option value="{{$user->id}}"> {{$user->name}}</option>
                    @endif
                   @endforeach
                 @else
                  <option value=""> No Gift Recipients </option>
                 @endif
              </select>
          </div>
          <div class="form-group">
            <label for="amount" class="col-form-label text-danger">Enter Amount To Gift</label>
            <input type="text" class="form-control" name="amount" id="amount" placeholder="e.g 25000">
          </div>
          <div class="form-group">
            <hr class="my-3">
            <button type="submit" name="submit" class="btn btn-primary">Gift Cash</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
