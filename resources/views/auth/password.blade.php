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

                    <form method="POST" action="<% url('password/email') %>">
                        <%% csrf_field() %%>

                        @if (count($errors) > 0)
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li><% $error %></li>
                                @endforeach
                            </ul>
                        @endif
                        <div class="form-group">
                        <div>
                            <input class="form-control" type="email" name="email" value="<% old('email') %>" placeholder="Email id">
                        </div>
                        </div>
                        <div class="text-center">
                            <button class="btn btn-main" type="submit">
                                Send Link
                            </button>
                        </div>
                    </form>

                </div>

            </div>
        </div>
        </div>

    </section>


@endsection