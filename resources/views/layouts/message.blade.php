@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul class="list-group">
            @foreach ($errors->all() as $error)
                <li><h2>{{ $error }}</h2></li>

            @endforeach
        </ul>
    </div>
@endif


@if(Session::has('sucess'))
	<div class="alert alert-success">
        <h3>{{ Session::get('sucess') }}</h3>
    </div>
@endif

@if(Session::has('faill'))
	<div class="alert alert-waring">
        <h3>{{ Session::get('faill') }}</h3>
    </div>
@endif
