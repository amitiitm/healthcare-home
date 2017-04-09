@extends('layouts.front.master_auth')

@section('title')
For Specialized Patient Care at Home in Delhi NCR
@endsection

@section('content')


<section id="auth-section">

	<div class="container">
		<div class="row">
        	 <div class="col-md-4 col-md-offset-4">
        	    <div class="login_logo">
        	        <a href="<% url("/")%>"><img src="<% asset('static/images/team/pramaticare-logo.png')%>" width="200" height="200"></a>
        	    </div>
             </div>
        </div>
		<div class="row">
				<div class="auth-form-container">
					<h1>Sign Up</h1>
					<div class="register-form-group">

                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <ul class="list-unstyled">
                                    @foreach ($errors->all() as $error)
                                        <li><% $error %></li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
					<form method="POST" action="<% url('/auth/register') %>">
                        <%% csrf_field() %%>

                        <div class="form-group">
                            <input type="text" name="name" class="form-control" placeholder="Full name" value="<% old('name') %>">
                        </div>

                        <div class="form-group">

                            <input type="email" name="email" class="form-control" placeholder="Email" value="<% old('email') %>">
                        </div>

                        <div class="form-group">
                            <input type="password" class="form-control" placeholder="Password" name="password">
                        </div>

                        <div class="form-group">
                            <input type="password" class="form-control" placeholder="Confirm Password" name="password_confirmation">
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-main">Register</button>
                        </div>
                    </form>


				</div>

			</div>
		</div>
	</div>

</section>


@endsection

