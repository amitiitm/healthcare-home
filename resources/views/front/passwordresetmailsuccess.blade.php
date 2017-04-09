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
                <div class="auth-form-containesr text-center">

                    <form method="POST" action="<% url('password/email') %>">
                        <%% csrf_field() %%>

                        @if (count($errors) > 0)
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li><% $error %></li>
                                @endforeach
                            </ul>
                        @endif
                        <div>
                            <h4>Email has been sent to the email you entered. Please check mail box for the instruction to reset password.</h4>
                        </div>
                    </form>

                </div>

            </div>
        </div>
        </div>

    </section>


@endsection