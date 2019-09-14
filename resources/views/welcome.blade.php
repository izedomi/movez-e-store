@extends("layouts.public")
@section("content")

        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @endauth
                </div>
            @endif

              <div class="content my-5">
                 <div class="jumbotron mx-5 my-5">
                   <div class="title m-b-md px-5">
                      e-SHOP APP
                   </div>
                   <h3 class="display-5"> ...for your hassle free purchases</h3>
                   <hr class="my-4">
                   <p class="lead">
                     <a href="login" role="button" class="mr-5">My Account</a>
                     <a href="register" role="button">Create New Account</a>
                   </p>
                </div>
              </div>
        </div>


@endsection
