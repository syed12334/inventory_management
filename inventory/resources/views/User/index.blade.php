@extends('layouts.app')
    @section('title')
        Users
    @endsection
	@push('style')
		<style>
			#name-error {
				background-image:none!important;
				margin-top:3px
			}
			#name-error {
				background-image:none!important;
				margin-top:3px
			}
			#role-error {
				background-image:none!important;
				margin-top:3px
			}
			#email-error {
				background-image:none!important;
				margin-top:3px
			}
			#password_confirmation-error {
				background-image:none!important;
				margin-top:3px
			}
			#password-error {
				background-image:none!important;
				margin-top:3px
			}
			#phone-error {
				background-image:none!important;
				margin-top:3px
			}
			.small {
				display:none!important
			}
			#deleteMultipleuser {
				display:none
			}
			#tableUsers {
				cursor:pointer!important
			}
		</style>
	@endpush
	@section('content')

				<form id="getSubmit" method="get">
					<input type="hidden" name="status" value="{{ request('status') }}" id="status" />
					<input type="hidden" name="paging" value="{{ request('paging') }}" id="paging" />
					<input type="hidden" name="roles" value="{{ request('roles') }}" id="roles" />
				</form>
				<div class="content">
					<div class="page-header">
						<div class="add-item d-flex">
							<div class="page-title">
								<h4 class="fw-bold">Users</h4>
								<div><a href="{{ url('/') }}">Home</a> 
									<svg class="breadcrumb-icon" viewBox="0 0 20 20" fill="currentColor" style="margin-top:-1px;width:18px">
									<path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
									</svg> Users
								</div>
							</div>
						</div>
						@if(session('success'))
							<div class="alert alert-solid-success alert-dismissible fade show">
								{{ session('success') }}
								<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
									<i class="fas fa-xmark"></i>
								</button>
							</div>
						@endif
						<div class="page-btn">
							<a href="{{ route('roles') }}" class="btn btn-secondary"><i class="ti ti-circle-plus me-1"></i> Manage Roles</a>
							<a href="#" class="btn btn-primary" id="addUsers"><i class="ti ti-circle-plus me-1"></i>Add User</a>
						</div>
					</div>
					<div class="card">
					  <form method="post" action="{{ route('multipleDelete'); }}">
							@csrf
						<button class="btn btn-danger" id="deleteMultipleuser" type="button" data-bs-toggle="modal" data-bs-target="#delete-multiple-modal" style="position:absolute;top:17px;left:240px;z-index:99999999999!important"><i class="ti ti-trash"></i> Delete</button>
						<div class="card-header d-flex align-items-center justify-content-between flex-wrap row-gap-3">
							<div class="search-set">
								<div class="search-input">
									<span class="btn-searchset"><i class="ti ti-search fs-14 feather-search"></i></span>
								</div>
							</div>
							<div class="d-flex table-dropdown my-xl-auto right-content align-items-center flex-wrap row-gap-3">
								<div class="dropdown me-2">
									<a href="javascript:void(0);" class="dropdown-toggle btn btn-white btn-md d-inline-flex align-items-center" data-bs-toggle="dropdown">
										@if(request('roles') ==0) 
											{{ "All"}} 
										@else
											@foreach($roles as $item)
												@if(request('roles') !="" && !empty(request('roles')))
													@if($item->id == request('roles'))
														{{$item->name}}
													@endif
												@endif
											@endforeach
										@endif
									</a>
									<ul class="dropdown-menu  dropdown-menu-end p-3">
									<li onclick="return getRoles(0,'All')">
											<a href="javascript:void(0);" class="dropdown-item rounded-1">All</a>
										</li>
										 @foreach($roles as $item)
											<li><a class="dropdown-item" href="javascript:void(0)" onclick="return getRoles({{$item->id}},'{{$item->name}}')">{{$item->name}}</a></li>
										 @endforeach
									</ul>
								</div>
								<div class="dropdown">
									<a href="javascript:void(0);" class="dropdown-toggle btn btn-white btn-md d-inline-flex align-items-center" data-bs-toggle="dropdown">
										@if(request('status') ==1 && request('status') !="")
											Active
										@elseif(request('status') ==0 && request('status') !="")
											Inactive
										@elseif(request('status') ==-1 && request('status') !="")
										 All
										@else
										  All
										@endif
									</a>
									<ul class="dropdown-menu  dropdown-menu-end p-3">
										<li onclick="getStatus(-1)">
											<a href="javascript:void(0);" class="dropdown-item rounded-1">All</a>
										</li>
										<li onclick="getStatus(1)">
											<a href="javascript:void(0);" class="dropdown-item rounded-1">Active</a>
										</li>
										<li onclick="getStatus(0)">
											<a href="javascript:void(0);" class="dropdown-item rounded-1">Inactive</a>
										</li>
									</ul>
								</div>
							</div>
						</div>
						<div class="card-body p-0">
							<div class="table-responsive">
								<table class="table datatable">
									<thead class="thead-light">
										<tr>
											<th class="no-sort">
													<label class="checkboxs">
														<input type="checkbox" id="select-all">
														<span class="checkmarks"></span>
													</label>
												</th>
												<th>User Name</th>
												<th>Email</th>
												<th>Role</th>
												<th>Status</th>
												<th class="no-sort" style="width:70px!important">Action</th>
										</tr>
									</thead>
									<tbody>
									@if(count($users) >0) 
												@foreach ($users as $k =>$user)
													<tr>
														<td>
															<label class="checkboxs">
																<input type="checkbox" value="{{ $user->id; }}" name="deleteUser[]" class="getusercheckbox">
																<span class="checkmarks"></span>
															</label>
														</td>
														<td>
															<div class="d-flex align-items-center">
																<a href="javascript:void(0);" class="avatar avatar-md me-2">
																	<img src="{{ asset($user->profile_image ) }}" alt="product">
																</a>
																<a href="javascript:void(0);">{{$user->name }}</a>
															</div>
														</td>
														<td>{{ $user->email }}</td>
														<td>@foreach($user->roles as $role)
																{{ $role->name }}
															@endforeach</td>
														<td> @if($user->status ==1) <span class="d-inline-flex align-items-center p-1 pe-2 rounded-1 text-white bg-success fs-10">Active</span> @else <span class="d-inline-flex align-items-center p-1 pe-2 rounded-1 text-white bg-danger fs-10">Inactive</span> @endif</td>
														<td class="action-table-data">
															<div class="edit-delete-action">
																@if($user->status ==1)
																	<a class="me-2 p-2 mb-0" onclick="deleteUser({{ $user->id; }},0,'Are you sure you want to inactive user?')">
																			<i class="ti ti-lock-cancel" title="Inactive User"></i>
																	</a>
																@else
																	<a class="me-2 p-2 mb-0" onclick="deleteUser({{ $user->id; }},1,'Are you sure you want to activate user?')">
																		<i class="ti ti-check" title="Active User"></i>
																	</a>
																@endif
																<a class="me-2 p-2 mb-0" onclick="edit({{ $user->id }})" title="Edit User">
																	<i class="ti ti-edit" title="Edit User"></i>
																</a>
																<a onclick="deleteUser({{ $user->id; }},2,'Are you sure you want to delete user?')"  class="p-2 mb-0" title="Delete User">
																	<i class="ti ti-trash" title="Delete User"></i>
																</a>
															</div>
														</td>
													</tr>
												@endforeach
											@endif	
										
									</tbody>
								</table>
							</div>
						</div>
						    <!-- Delete multiple Moal -->
								<div class="modal fade" id="delete-multiple-modal">
								<div class="modal-dialog modal-dialog-centered">
									<div class="modal-content">
										<div class="page-wrapper-new p-0">
											<div class="content p-5 px-3 text-center" style="min-height:230px;padding-top:35px!important">
													<span class="rounded-circle d-inline-flex p-2 bg-danger-transparent mb-2" id="statusIcon"><i class="ti ti-trash fs-24 text-danger"></i></span>
													<h4 class="fs-20 fw-bold mb-2 mt-1" id="statusTitle">Delete Multiple User</h4>
													<p class="mb-0 fs-16" id="statusText">Are you sure to delete selected user?</p>
													<div class="modal-footer-btn mt-3 d-flex justify-content-center">
														<button type="button" class="btn me-2 btn-secondary fs-13 fw-medium p-2 px-3 shadow-none" data-bs-dismiss="modal">Cancel</button>
														<button type="submit" class="btn btn-primary fs-13 fw-medium p-2 px-3">Submit</button>
													</div>							
											</div>
										</div>
									</div>
								</div>
							</div>
					  </form>
					  <select class="form-select form-select-sm d-none d-lg-block d-xxl-none" id="getPaging"><option value="10" @if(request('paging') ==10) selected @endif>10</option><option value="20" @if(request('paging') ==20) selected @endif>20</option><option value="30" @if(request('paging') ==30) selected @endif>30</option></select>
								<div class="paginglinks">
									{{ $users->links(); }}
								</div>
					</div>
					<!-- /product list -->
					
				</div>
	<!-- Add user -->
		<div class="modal fade" id="add-user">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="page-wrapper-new p-0">
						<div class="content">
							<div class="modal-header">
								<div class="page-title">
									<h4 id="modalTitle">Add User</h4>
								</div>
								<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<form action="{{ route('storeUser'); }}" method="post" id="usersList" enctype="multipart/form-data">
								@csrf
								<div class="modal-body">
									<div class="row">
										<input type="hidden" name="getuserid" id="getuserid" />
										<div class="col-lg-12">
											<div class="mb-3">
												<label class="form-label">Username<span class="text-danger ms-1">*</span></label>
												<input type="text" class="form-control" name="name" id="name" required>
												<span class="text-danger" id="error_name"></span>
											</div>
										</div>
										<div class="col-lg-12">
											<div class="mb-3">
												<label class="form-label">Role<span class="text-danger ms-1">*</span></label>
												<select class="form-control select2" name="role" id="role" required>
													<option value="">Select role</option>
													@if(count($roles) >0)
														@foreach($roles as $k => $val)
															<option value="{{ $val->name;}}">{{ $val->name;}}</option>
														@endforeach
													@endif
												</select>
												<span class="text-danger" id="error_role"></span>
											</div>
										</div>
										<div class="col-lg-12">
											<div class="mb-3">
												<label class="form-label">Email<span class="text-danger ms-1">*</span></label>
												<input type="email" class="form-control" name="email" id="email" required>
												<span class="text-danger" id="error_email"></span>
											</div>
										</div>
										<div class="col-lg-12">
											<div class="mb-3">
												<label class="form-label">Phone<span class="text-danger ms-1">*</span></label>
												<input type="tel" class="form-control" name="mobile_number"  maxlength="10" required>
												<span class="text-danger" id="error_mobile_number"></span>
											</div>
										</div>
									
										<div class="col-lg-6">
											<div class="mb-3">
												<label class="form-label">Password<span class="text-danger ms-1">*</span></label>
												<div class="pass-group">
													<input type="password" class="pass-input form-control" name="password" id="password" required>
													<i class="ti ti-eye-off toggle-password"></i>
													<span class="text-danger" id="error_password"></span>
												</div>
											</div>
										</div>
										<div class="col-lg-6">
											<div class="mb-3">
												<label class="form-label">Confirm Password<span class="text-danger ms-1">*</span></label>
												<div class="pass-group">
													<input type="password" class="pass-input form-control" name="password_confirmation" id="password_confirmation" required>
													<i class="ti ti-eye-off toggle-password"></i>
													<span class="text-danger" id="error_password_confirmation"></span>
												</div>
											</div>
										</div>

										<div class="col-lg-12">
											<div class="mb-3">
												<label class="form-label">Profile Image</label>
												<input type="file" class="dropify" name="profile_img" class="form-control" data-allowed-file-extensions="JPEG jpg png PNG JPG" data-height="200" data-width="100" required>
												<p class="fs-13 mt-2" style="color:red">(Only JPEG, PNG, JPG are allowed)</p>
												<span class="text-danger" id="error_profile_img"></span>
											</div>
										</div>
										
									</div>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn me-2 btn-secondary" data-bs-dismiss="modal">Cancel</button>
									<button type="submit" id="userSubmitBtn" class="btn btn-primary">Add User</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>

		
		<!-- delete modal -->
		<div class="modal fade" id="delete-modal">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="page-wrapper-new p-0">
						<div class="content p-5 px-3 text-center" style="min-height:230px;padding-top:35px!important">
							<form action="{{ route('userStatusChange') }}" method="post">
								@csrf
								<input type="hidden" name="user_id" id="user_id" />
								<input type="hidden" name="status" id="statusChange" />
								<span class="rounded-circle d-inline-flex p-2 bg-danger-transparent mb-2" id="statusIcon"><i class="ti ti-trash fs-24 text-danger"></i></span>
								<h4 class="fs-20 fw-bold mb-2 mt-1" id="statusTitle">Delete User</h4>
								<p class="mb-0 fs-16" id="statusText">Are you sure you want to delete user?</p>
								<div class="modal-footer-btn mt-3 d-flex justify-content-center">
									<button type="button" class="btn me-2 btn-secondary fs-13 fw-medium p-2 px-3 shadow-none" data-bs-dismiss="modal">Cancel</button>
									<button type="submit" class="btn btn-primary fs-13 fw-medium p-2 px-3">Submit</button>
								</div>	
							</form>
													
						</div>
					</div>
				</div>
			</div>
		</div>
@endsection
@push('script')
    <script>
		 var dr = $('.dropify').dropify().data('dropify');
		/* Toast notification*/
		  toastr.options = {
			"closeButton": true,
			"progressBar": true,
			"timeOut": "4000",           
			"extendedTimeOut": "4000"
			};
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});
			/* Save data*/
			$(document).ready(function() {
				
				$("#usersList").validate({
				rules: {
					name: {
						required: true,
						minlength: 3
					},
					email: {
						required: true,
						email: true
					},
					role: {
						required: true,
					},
					password: {
						required: true,
					},
					password_confirmation: {
						required: true,
					},
					phone: {
						required: true,
						minlength:10,
						maxlength:10
					},
					profile_img: {
						required:true
					}
				},
				messages: {
					name: {
						required: "Please enter a username",
						minlength: "Your category name must consist of at least 2 characters"
					},
					role: {
						required: "Please select role",
					},
					email: {
						required: "Please enter email",
						email:"Please enter valid email"
					},
					password: {
						required: "Please enter password",
					},
					password_confirmation: {
						required: "Please enter cpassword",
					},
					profile_img: {
						required: "Please select profile image",
					},
					phone: {
						required: "Please enter mobile number",
						minlength:"Please enter minimum 10 digits",
						manlength:"Please enter maximum 10 digits",
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
							if(res.status == true) {
								toastr.success(res.msg);
								$('#add-user').modal('hide');
								$('#usersList')[0].reset();
								setTimeout(function() {
									location.reload();
								},4000);
							}else if(res.status ==422) {
								$.each(res.errors, function (key, val) {
									$('#error_' + key).text(val[0]);
									$("#"+key).addClass('error');
								});
							}
						},
						error: function(jqXHR, textStatus, errorThrown) {
							var msg = "Something went wrong !";
							switch (jqXHR.status) {
							case 403: msg = "Token error ! Re-try again";break;
							default : msg = textStatus+" - "+errorThrown;break;
							} 
					}
					});
				}
			});
		});
		/* Select status change*/
		function getStatus(status) {
			$("#status").val(status);
			$("#paging").val({{ request('paging') }});
			$("#getSubmit").submit();
		}
		/* Select paging*/
		$(document).ready(function() {
			$(document).on("change",'#getPaging',function() {
				var paging = $(this).val();
				$("#paging").val(paging);
				$("#status").val({{ request('status') }});
				$("#getSubmit").submit();
			});
		});
		/* Select delete user */
		function deleteUser(user_id,status,statusText) {
			$("#delete-modal").modal('show');
			$("#user_id").val(user_id);
			$("#statusChange").val(status);
			$("#statusText").text(statusText);
			if(status ==0) {
				$("#statusIcon").html('<i class="ti ti-lock-cancel text-danger" style="font-size:35px!important"></i>');
				$("#statusTitle").text('Inactive User');
			}
			else if(status ==1) {
				$("#statusIcon").html('<i class="ti ti-check fs-24 text-danger" style="font-size:35px!important"></i>');
				$("#statusTitle").text('Activate User');
			}else if(status ==2) {
				$("#statusIcon").html('<i class="ti ti-trash fs-24 text-danger" style="font-size:35px!important"></i>');
				$("#statusTitle").text('Delete User');
			}
		}
		/* Select user checkbox*/
		$('.getusercheckbox').on('click', function () {
          if ($('.getusercheckbox:checked').length > 0) {
              $("#deleteMultipleuser").show();
          } else {
              $("#deleteMultipleuser").hide();
          }
        });
		/* Select roles*/
		 function getRoles(rid,rname) {
           $("#roles").val(rid);
           $("#status").val({{request('status')}});
           $("#paging").val({{request('paging')}});
            $("#getSubmit").submit();
        }
		/* Select multiple*/
	 $('#select-all').on('change', function() {
		 let deleteList = $('input[name="deleteUser[]"]:checked').length;
        if (deleteList >0) {
            $("#deleteMultipleuser").show();
        } else {
           $("#deleteMultipleuser").hide();
        }
    });
	/* sort table */
	
	/* sort col*/
	$('.sort-col').click(function(){
		$(this).toggleClass('desc-order');
	});
	/* edit col */
	function edit(user_id) {
		$.ajax({
			url:"{{ route('editUser'); }}",
			method:"post",
			cache:false,
			dataType:"json",
			data: {
				user_id :user_id
			},
			success:function(response) {
				if(response.status ==true) {
					$("#name").val(response.data[0].name);
					$("#role").val(response.data[0].roles[0].name);
					$("#email").val(response.data[0].email);
					$("#phone").val(response.data[0].mobile_number);
					$("#modalTitle").text('Edit User');
					$("#add-user").modal('show');
					$("#userSubmitBtn").text('Edit User');
					var fileName ="http://localhost:8000/"+response.data[0].profile_image;
					 dr.resetPreview(); 
					 dr.clearElement();
					dr.settings.defaultFile = fileName;
					dr.destroy(); 
					dr.init();
					$(".dropify").attr('data-default-file',fileName);
					$("#usersList").attr("action", "{{ route('updateUser') }}");
				}else {
					$("#add-user").modal('hide');
				}
			}
		});
	}
	/* Reset form element on modal*/
	$(document).on("click","#addUsers",function() {
		$("#modalTitle").text('Add User');
		$("#userSubmitBtn").text('Add User');
		$("#usersList").attr("action", "{{ route('storeUser') }}");
		$("#usersList")[0].reset();
		 dr.resetPreview(); 
					 dr.clearElement();
					dr.settings.defaultFile = "";
					dr.destroy(); 
					dr.init();
		$("#add-user").modal('show');
	});

	</script>
@endpush
