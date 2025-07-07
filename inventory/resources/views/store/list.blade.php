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
                <div>
                    <a href="{{ url('/') }}">Home</a>
                    <svg class="breadcrumb-icon" viewBox="0 0 20 20" fill="currentColor" style="margin-top:-1px;width:18px">
                        <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                    </svg>
                    Users
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
        <form method="post" action="{{ route('warehouse.multipleDelete') }}" id="multipledeleteform">
            @csrf
            <button class="btn btn-danger" id="deleteMultipleuser" type="button" data-bs-toggle="modal" data-bs-target="#delete-multiple-modal" style="position:absolute;top:17px;left:240px;z-index:99999999999!important">
                <i class="ti ti-trash"></i> Delete
            </button>

            <div class="card-header d-flex align-items-center justify-content-between flex-wrap row-gap-3">
                <div class="search-set">
                    <div class="search-input">
                        <span class="btn-searchset"><i class="ti ti-search fs-14 feather-search"></i></span>
                    </div>
                </div>
                <div class="d-flex table-dropdown my-xl-auto right-content align-items-center flex-wrap row-gap-3">
                    <div class="dropdown">
                        <a href="javascript:void(0);" class="dropdown-toggle btn btn-white btn-md d-inline-flex align-items-center" data-bs-toggle="dropdown">
                            @if(request('status') == 1 && request('status') != "")
                                Active
                            @elseif(request('status') == 0 && request('status') != "")
                                Inactive
                            @elseif(request('status') == -1 && request('status') != "")
                                All
                            @else
                                All
                            @endif
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end p-3">
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
                <div class="table-responsive" id="usersListtable">
                    @include('warehouse.partials.table', ['users' => $users])
                </div>
            </div>

            <!-- Delete multiple Modal -->
            <div class="modal fade" id="delete-multiple-modal">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="page-wrapper-new p-0">
                            <div class="content p-5 px-3 text-center" style="min-height:230px;padding-top:35px!important">
                                <span class="rounded-circle d-inline-flex p-2 bg-danger-transparent mb-2" id="statusIcon">
                                    <i class="ti ti-trash fs-24 text-danger"></i>
                                </span>
                                <h4 class="fs-20 fw-bold mb-2 mt-1" id="statusTitle">Delete Multiple User</h4>
                                <p class="mb-0 fs-16" id="statusText">Are you sure to delete selected user?</p>
                                <div class="modal-footer-btn mt-3 d-flex justify-content-center">
                                    <button type="button" class="btn me-2 btn-secondary fs-13 fw-medium p-2 px-3 shadow-none" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary fs-13 fw-medium p-2 px-3" id ="submit_id">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Add user modal -->
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
                    <form action="{{ route('store.store-store-user') }}" method="post" id="usersstoreList" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="role" value="{{ $roles->name }}">
                        <div class="modal-body">
                            <div class="row">
                                <input type="hidden" name="user_id" id="getuserid" />
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label class="form-label">Username<span class="text-danger ms-1">*</span></label>
                                        <input type="text" class="form-control" name="name" id="name" required>
                                        <span class="text-danger" id="error_name"></span>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label class="form-label">Store <span class="text-danger ms-1">*</span></label>
                                        <select class="form-control" name="warehouse_store_id" id="role" required>
                                            <option value="">Select Store</option>
                                            @if(count($store_users) > 0)
                                                @foreach($store_users as $val)
                                                    <option value="{{ $val->id }}">{{ $val->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <span class="text-danger" id="error_warehouse_store_id"></span>
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
                                        <input type="tel" class="form-control" name="mobile_number" id="mobile_number" maxlength="10" required>
                                        <span class="text-danger" id="error_mobile_number"></span>
                                    </div>
                                </div>

                                <div class="col-lg-6" id="editPasswordField">
                                    <div class="mb-3">
                                        <label class="form-label">Password<span class="text-danger ms-1 removestar">*</span></label>
                                        <div class="pass-group">
                                            <input type="password" class="pass-input form-control" name="password" id="password" required>
                                            <i class="ti ti-eye-off toggle-password"></i>
                                            <span class="text-danger" id="error_password"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6" id="hidePasswordField">
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
                                        <label class="form-label">Bank Details <span class="text-danger ms-1">*</span></label>
                                        <input type="text" class="form-control" name="bank_detail" id="bank_detail" required>
                                        <span class="text-danger" id="error_bank_detail"></span>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label class="form-label">Address <span class="text-danger ms-1">*</span></label>
                                        <input type="text" class="form-control" name="billing_address" id="billing_address">
                                        <span class="text-danger" id="error_billing_address"></span>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="mb-3 form-check">
                                        <input type="hidden" name="show_email_on_invoice" value="0">
                                        <input type="checkbox" class="form-check-input" name="show_email_on_invoice" id="show_email_on_invoice" value="1">
                                        <label class="form-check-label" for="show_email_on_invoice">Show Email on Invoice</label>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="mb-3 form-check">
                                        <input type="hidden" name="show_phone_on_invoice" value="0">
                                        <input type="checkbox" class="form-check-input" name="show_phone_on_invoice" id="show_phone_on_invoice" value="1">
                                        <label class="form-check-label" for="show_phone_on_invoice">Show Phone on Invoice</label>
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

<!-- Delete Modal -->
<div class="modal fade" id="delete-modal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="page-wrapper-new p-0">
                <div class="content p-5 px-3 text-center" style="min-height:230px;padding-top:35px!important">
                    <form action="{{ route('userStatusChange') }}" method="post">
                        @csrf
                        <input type="hidden" name="user_id" id="user_id" />
                        <input type="hidden" name="status" id="statusChange" />
                        <span class="rounded-circle d-inline-flex p-2 bg-danger-transparent mb-2" id="statusIcon">
                            <i class="ti ti-trash fs-24 text-danger"></i>
                        </span>
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

		$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});
		// var dr = $('.dropify').dropify().data('dropify');
		/* Toast notification*/
		  toastr.options = {
			"closeButton": true,
			"progressBar": true,
			"timeOut": "4000",           
			"extendedTimeOut": "4000"
			};
			
			/* Save data*/
			$(document).ready(function() {
				
				$("#usersList").validate({
				rules: {
					role: {
						required: true,
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
						console.log(res);
							if (res.status == true) {
							toastr.success(res.msg);
							$('#add-user').modal('hide');
							$('#edit-user').modal('hide');

							$.ajax({
								url: "{{ route('warehouse.index') }}",
								type: 'GET',
								success: function(res) {
									$('#usersListtable').html(res.html);
								},
								error: function() {
									toastr.error('Failed to refresh user list.');
								}
							});
						} else if(res.status ==422) {
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
			  let selectedIds = [];

			$('.getusercheckbox:checked').each(function () {
				selectedIds.push($(this).val());
			});

			alert(selectedIds);
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
			url:"{{ route('warehouse.editUser') }}",
			method:"post",
			cache:false,
			dataType:"json",
			data: {
				user_id :user_id
			},
			success: function(response) {

	    console.log("Full response:", response);        // Logs the entire response object
    console.log("User data:", response.data);       // Logs the 'data' property from response


					if (response.status === true) {
						const user = response.data;

						$("#name").val(user.name);
						$("#getuserid").val(user.id);
						$("#editPasswordField").hide();
						$("#hidePasswordField").hide();
						$("#role").val(user.warehouse_store_id);
						$("#email").val(user.email);
						$("#mobile_number").val(user.mobile_number);
						$("#bank_detail").val(user.bank_detail);
						$("#billing_address").val(user.billing_address);
						$("#show_email_on_invoice").prop("checked", user.show_email_on_invoice == 1);
						$("#show_phone_on_invoice").prop("checked", user.show_phone_on_invoice == 1);
						
						
						$("#password").removeAttr('required');

						$("#modalTitle").text('Edit User');
						$("#add-user").modal('show');
						$("#userSubmitBtn").text('Edit User');
						$(".removestar").text('');

						$("#name, #email, #mobile_number").prop('disabled', true);
						$("#usersList").attr("action", "{{ route('warehouse.updateWarehouse') }}");

						$("#userSubmitBtn").off("click").on("click", function (e) {
							e.preventDefault();
							$("#usersList").submit();
							$.ajax({
								url: "{{ route('warehouse.index') }}",
								type: 'GET',
								success: function(res) {
									$('#usersListtable').html(res.html);
								},
								error: function() {
									toastr.error('Failed to refresh user list.');
								}
							});
						});
					} else {
						$("#add-user").modal('hide');
						alert("User not found.");
					}
				}

		});

		
	}

	$('#submit_id').on('click', function () {
		e.preventDefault();
		$("#multipledeleteform").submit();
		$.ajax({
			url: "{{ route('warehouse.index') }}",
			type: 'GET',
			success: function(res) {
				$('#usersListtable').html(res.html);
			},
			error: function() {
				toastr.error('Failed to refresh user list.');
			}
		});
	});

	$(document).ready(function () {
		$("#usersstoreList").validate({
			rules: {
				name: {
					required: true,
					minlength: 2
				},
				email: {
					required: true,
					email: true
				},
				mobile_number: {
					required: true,
					digits: true,
					minlength: 10,
					maxlength: 10
				},
				password: {
					required: true,
					minlength: 6
				},
				password_confirmation: {
					required: true,
					equalTo: "#password"
				},
				warehouse_store_id: {
					required: true
				},
				bank_detail: {
					required: true
				},
				billing_address: {
					required: true
				}
			},
			messages: {
				name: {
					required: "Please enter a username.",
					minlength: "Name must be at least 2 characters long."
				},
				email: {
					required: "Please enter an email address.",
					email: "Please enter a valid email address."
				},
				mobile_number: {
					required: "Please enter a phone number.",
					digits: "Please enter only digits.",
					minlength: "Phone number must be 10 digits.",
					maxlength: "Phone number must be 10 digits."
				},
				password: {
					required: "Please enter a password.",
					minlength: "Password must be at least 6 characters."
				},
				password_confirmation: {
					required: "Please confirm your password.",
					equalTo: "Passwords do not match."
				},
				warehouse_store_id: {
					required: "Please select a store."
				},
				bank_detail: {
					required: "Please enter bank details."
				},
				billing_address: {
					required: "Please enter an address."
				}
			},
			errorPlacement: function (error, element) {
				const errorId = "#error_" + element.attr("id");
				$(errorId).html(error);
			},
			submitHandler: function (form) {
				form.submit(); // Normal Laravel POST form submission
			}
		});
	});

	/* Reset form element on modal*/
	$(document).on("click", "#addUsers", function() {
    $("#modalTitle").text('Add Store User');
    $("#userSubmitBtn").text('Add Store User');
    $("#usersstoreList").attr("action", "{{ route('store.store-store-user') }}");
    $("#password").attr('required', 'required');
    $("#editPasswordField").show();
    $("#hidePasswordField").show();
    $("#usersstoreList")[0].reset();
    $(".removestar").text('*');
    $("#getuserid").val('');
    $("#name").prop('disabled', false);
    $("#email").prop('disabled', false);
    $("#mobile_number").prop('disabled', false);
    $("#add-user").modal('show');
});

	</script>
@endpush
