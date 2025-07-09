<!DOCTYPE html>
<html lang="en">
    

<head>

		<!-- Meta Tags -->
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="Dreams POS is a powerful Bootstrap based Inventory Management Admin Template designed for businesses, offering seamless invoicing, project tracking, and estimates.">
		<meta name="keywords" content="inventory management, admin dashboard, bootstrap template, invoicing, estimates, business management, responsive admin, POS system">
		<meta name="author" content="Dreams Technologies">
		<meta name="robots" content="index, follow">
		<title>Admin</title>
		
		<script src="{{ asset('js/theme-script.js')}}" ></script>

		<!-- Favicon -->
        <link rel="shortcut icon" type="image/x-icon" href="{{ asset('img/favicon.png')}}">

		<!-- Apple Touch Icon -->
		<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('img/apple-touch-icon.png')}}">
		
		<!-- Bootstrap CSS -->
        <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css')}}">
		
        <!-- Fontawesome CSS -->
		<link rel="stylesheet" href="{{ asset('plugins/fontawesome/css/fontawesome.min.css')}}">
		<link rel="stylesheet" href="{{ asset('plugins/fontawesome/css/all.min.css')}}">

         <!-- Tabler Icon CSS -->
	    <link rel="stylesheet" href="{{ asset('plugins/tabler-icons/tabler-icons.css')}}">
		<!-- Toaster -->
        <link rel="stylesheet" type="text/css" href="{{ asset('css/toastr.min.css')}}">

	    <!-- Main CSS -->
        <link rel="stylesheet" href="{{ asset('css/style.css')}}">
		<style>
            .input-group {
                position:relative;
            }
            #email-error {
				background-image:none!important;
				margin-top:3px;
                position:absolute;
                top:36px
			}
			#error_email {
				position:absolute;
                top:39px;
			}
            #password-error {
                background-image:none!important;
				margin-top:3px;
            }
        </style>
    </head>
    <body class="account-page bg-white">

        <div id="global-loader" >
			<div class="whirly-loader"> </div>
		</div>
	
		<!-- Main Wrapper -->
        <div class="main-wrapper">
			<div class="account-content">
				<div class="login-wrapper login-new">
                    <div class="row w-100">
                        <div class="col-lg-5 mx-auto">
                            <div class="login-content user-login">
                                <div class="login-logo">
                                    <img src="{{ asset('img/logo.svg')}}" alt="img">
                                    <a href="index.html" class="login-logo logo-white">
                                        <img src="{{ asset('img/logo-white.svg')}}"  alt="Img">
                                    </a>
                                </div>
                                <form action="{{ route('loginUser') }}" method="post" id="loginForm" enctype="multipart/form-data">

                                    @csrf
                                    <div class="card">
                                        <div class="card-body p-5">
                                            <div class="login-userheading">
                                                <h3>Login</h3>
																				<div id="errorMsg"></div>

                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label" for="email">Email <span class="text-danger"> *</span></label>
                                                <div class="input-group">
                                                    <input type="email" id="email" placeholder="Enter email" name="email" class="form-control border-end-0" required>
                                                    <span class="input-group-text border-start-0">
                                                        <i class="ti ti-mail"></i>
                                                    </span>
                                                    <span class="text-danger" id="error_email"></span>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Password <span class="text-danger"> *</span></label>
                                                <div class="pass-group">
                                                    <input type="password" placeholder="Enter password" class="pass-input form-control" name="password" required>
                                                    <span class="ti toggle-password ti-eye-off text-gray-9"></span>
                                                    <span class="text-danger" id="error_password"></span>
                                                </div>
                                            </div>
                                          
                                           
                                            <div class="form-login">
                                                <button type="submit" class="btn btn-login">Login</button>
                                            </div>
                                           
                                         
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="my-4 d-flex justify-content-center align-items-center copyright-text">
                                <p>Copyright &copy; 2025 Dummy</p>
                            </div>
                        </div>
                    </div>
                </div>
			</div>
        </div>
		<!-- /Main Wrapper -->
		  

		<!-- jQuery -->
        <script src="{{ asset('js/jquery-3.7.1.min.js')}}"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>


         <!-- Feather Icon JS -->
		<script src="{{ asset('js/feather.min.js')}}"></script>
		
		<!-- Bootstrap Core JS -->
        <script src="{{ asset('js/bootstrap.bundle.min.js')}}"></script>
        <!-- Toaster -->
        		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

		
		<!-- Custom JS -->
<script src="{{ asset('js/script.js')}}"></script>
<script>
  
				
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});
			/* Save data*/
			$(document).ready(function() {
			  $("#loginForm").validate({
				rules: {
					email: {
						required: true,
                        email:true
					},
                    password: {
						required: true,
					} 
				},
				messages: {
					email: {
						required: "Please enter email",
                        email:"Please enter valid email"
					},
                    password:{
                        required:"Please enter password"
                    }
				},
				submitHandler: function (form) {
					var formData = new FormData(form);
					$.ajax({
						url: form.action,
						method: form.method,
						data: formData,
						processData: false,
						contentType: false,
						success: function (res) {
						console.log(res);
							if(res.status == "true") {
								window.location.href="{{  route('dashboard') }}";
								
							}else if(res.status ==422) {
								$.each(res.errors, function (key, val) {
									$('#error_' + key).text(val[0]);
									$("#"+key).addClass('error');
								});
							}
							else if(res.status =="false") {
								$("#errorMsg").html('<div class="alert alert-danger" style="top:5%!important">'+res.msg+'</div>');
							}
						},
						error: function(jqXHR, textStatus, errorThrown) {
							let msg = "Something went wrong!";
							switch (jqXHR.status) {
								case 400:
									msg = "Bad Request!";
									break;
								case 401:
									msg = "Unauthorized! Please log in.";
									break;
								case 403:
									msg = "Access denied or token error!";
									break;
								case 404:
									msg = "Requested resource not found!";
									break;
								case 419:
									msg = "Page expired. Please refresh and try again.";
									break;
								case 422:
									msg = "Validation failed!";
									break;
								case 429:
									msg = "Too many requests. Login limit exceeded!";
									break;
								case 500:
									msg = "Internal Server Error!";
									break;
								case 503:
									msg = "Service unavailable. Please try again later.";
									break;
								default:
									msg = textStatus + " - " + errorThrown;
									break;
							}
							$("#errorMsg").html(`<div class="alert alert-danger" style="top:5%!important">${msg}</div>`);
						}
					});
				}
			});
		});

	
</script>
	
</body>


</html>