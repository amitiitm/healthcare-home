<header id="section_header" class="main-nav" role="banner">
<div class="container">
<!-- <div class="row"> -->
     <div class="navbar-header ">
         <button type="button" class="navbar-toggle navbar-menu" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1"><i class="fa fa-bars"></i>
                <span class="sr-only">Toggle navigation</span>
            </button>
            <a class="navbar-brand" href="<% url('/')%>">
                <img src="<% asset('static/images/logo.png') %>" alt="" class="img-responsive">
            </a>
     </div><!--Navbar header End-->
        <nav class="collapse navbar-collapse navigation" id="bs-example-navbar-collapse-1" role="navigation">
            <ul class="nav navbar-nav nav-right">
                <li><a href="<% url('/auth/register') %>" class="page-scroll">Sign up </a></li>

                <?php if($_SERVER['REQUEST_URI']){ ?>
                <li><a href="<% url('/#about')%>" class="page-scroll side-border"> How it works </a></li>
                <?php }else {?>
                <li><a href="#about" class="page-scroll side-border"> How it works </a></li>
                <?php }?>

                <?php
                if(Auth::user()){
                ?>

                <li class="dropdown dropdown-front-user">
                    <a id="drop6" href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <img src="<?php echo $authObject->imageUrl ?>" />

                    </a>
                        <ul id="menu3" class="dropdown-menu " aria-labelledby="drop6">
	                        <li><span><?php echo $authObject->name; ?></span></li>
	                        <?php
	                        if($authObject->userTypeId == 1 && $authObject->isAdmin==true){
	                        ?>
	                        <li><a href="<% url('admin/dashboard') %>">CRM</a></li>
	                        <?php
	                        }else if($authObject->userTypeId == 1 ){
	                        ?>
	                        <li><a href="<% url('employee/dashboard') %>">CRM</a></li>

	                        <?php
	                        }
	                        ?>
	                        <li><a href="<% url('auth/logout')%>">Logout</a></li>
                        </ul>
                </li>
                <?php
                }else{
                ?>
                <li><a href="<% url('/auth/login')%>" class="page-scroll">Log in</a> </li>
                <?php } ?>
            </ul>
         </nav>
    </div><!-- /.container-fluid -->
</header>