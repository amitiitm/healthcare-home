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
                    <h1>Password Reset</h1>

                    <form method="POST" action="<% url('password/reset') %>">
                        <%% csrf_field() %%>
                        <input type="hidden" name="token" value="<% $token %>">

                        @if (count($errors) > 0)
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li><% $error %></li>
                                @endforeach
                            </ul>
                        @endif
                        <div class="form-group"></div>
                        <div class="form-group">
                            <label>Email</label>
                            <input class="form-control" type="email" name="email" value="<% old('email') %>" placeholder="Email">
                        </div>

                        <div class="form-group">
                            <label>Password</label>
                            <input class="form-control" type="password" name="password" placeholder="Password">
                        </div>

                        <div class="form-group">
                            <label>Confirm Password</label>
                            <input class="form-control" type="password" name="password_confirmation" placeholder="Confirm Password">
                        </div>

                        <div class="form-group text-center">
                            <button class="btn btn-main" type="submit">
                                Reset Password
                            </button>
                        </div>

                    </form>

                </div>

            </div>
        </div>
        </div>

    </section>


@endsection
