@extends('layouts.front.master_auth')

@section('title')
For Specialized Patient Care at Home in Delhi NCR
@endsection

@section('content')


<section id="auth-section">

	<div class="container">
        <div class="row">
	        <div class="col-md-4 col-md-offset-4"><div class="login_logo">
	             <a href="<% url('/')%>"><img src="<% asset('static/images/team/pramaticare-logo.png')%>" width="200" height="200"></a>
	        </div>
        </div>
		<div class="row">
			<div class="col-md-4 col-md-offset-4">
				<div class="auth-form-container">
					<h1>Sign In</h1>
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
					<form class="auth-form" method="POST" action="<% url('/auth/login') %>">

                        <%% csrf_field() %%>

                        <div class="form-group">
                            <label>Email </label>
                            <input type="email" class="form-control" name="email" placeholder="Email" value="<% old('email') %>">
                        </div>

                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" class="form-control" name="password" placeholder="Password" id="password">
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <input type="checkbox" name="remember"> Remember Me
                            </div>
                            <div class="col-md-6">
                                <a class="pull-right" href="<% url('/password/email')%>">Forgot Password</a>
                            </div>
                        </div>

                        <div class="form-group text-center">
                            <button class="btn btn-main" type="submit">Login</button>
                        </div>
                    </form>

                    <div class="text-center">
                        <a href="http://pramati-care.slack.com" target="_blank">
                            <img src="<%  url('static/images/slack.png') %>" height="50" />
                        </a>
                    </div>
				</div>

			</div>
		</div>
	</div>

</section>


@endsection