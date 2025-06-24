
@extends('layouts.app')
    @section('title')
        Users
    @endsection
	@push('style')
		<style>
			.page-wrapper .modal-content .content {
				min-height:auto!important
			}
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
			#cpassword-error {
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
			
		</style>
	@endpush
@section('content')
   
				<div class="content">
					<div class="page-header">
						<div class="add-item d-flex">
							<div class="page-title">
								<h4 class="fw-bold">Roles</h4>
								<h6>Manage your roles</h6>
							</div>
						</div>
						
						<div class="page-btn">
							<a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-role"><i class="ti ti-circle-plus me-1"></i>Add Role</a>
						</div>
					</div>
					<!-- /product list -->
					<div class="card">
						<div class="card-header d-flex align-items-center justify-content-between flex-wrap row-gap-3">
							<div class="search-set">
								<div class="search-input">
									<span class="btn-searchset"><i class="ti ti-search fs-14 feather-search"></i></span>
								<div id="DataTables_Table_0_filter" class="dataTables_filter"><label> <input type="search" class="form-control form-control-sm" placeholder="Search" aria-controls="DataTables_Table_0"></label></div></div>
							</div>
							
						</div>
						
					<div class="card-body p-0">
							<div class="table-responsive">
								<table class="table table-bordered">
									<thead class="thead-light">
										<tr>
											<th class="no-sort" style="width:20px!important">
												<label class="checkboxs">
													<input type="checkbox" id="select-all">
													<span class="checkmarks"></span>
												</label>
											</th>
											<th>Role</th>
											<th>Permissions</th>
											<th style="width:60px!important">Action</th>
										</tr>
									</thead>
									<tbody>
										@if(count($role))
											@foreach($role as $k => $rol) 
											  <tr>
												<td>
													<label class="checkboxs">
														<input type="checkbox" name="roles[]" value="{{ $rol->id }}">
														<span class="checkmarks"></span>
													</label>
												</td>
												<td>{{ $rol->name }}</td>
												<td>{{ $rol->permissions->pluck('name')->implode(', ') }}</td>
												<td class="action-table-data">
												<div class="edit-delete-action">
												
													<a class="me-2 p-2 mb-0" href="javascript:void(0)" onclick="editRole({{ $rol->id }})">
														<i data-feather="edit" class="feather-edit"></i>
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
					</div>
					<!-- /product list -->
				</div>
		<div class="modal fade" id="add-role">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="page-wrapper-new p-0">
						<div class="content">
							<div class="modal-header">
								<div class="page-title">
									<h4>Add Role</h4>
								</div>
								<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<form method="post" id="roleForm">
								@csrf
								<div class="modal-body">
									<div class="row">
									
										<div class="col-lg-12">
											<div class="mb-3">
												<label class="form-label">Role<span class="text-danger ms-1">*</span></label>
												<input type="text" class="form-control" name="role" id="role" required>
												<span class="text-danger" id="error_role"></span>
											</div>
										</div>

										<div class="col-lg-12">
											@if(count($permission) >0) 
												@foreach ($permission as $k => $va )
														<div class="form-check form-check-inline">
														<input class="form-check-input" type="checkbox" id="permissions{{ $va->id }}" name="permissions[]" value="{{ $va->name }}">
														<label class="form-check-label" for="permissions{{ $va->id }}">{{ $va->name }}</label>
													</div>
												@endforeach
											@endif
										</div>
										
										
									</div>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn me-2 btn-secondary" data-bs-dismiss="modal">Cancel</button>
									<button type="submit" class="btn btn-primary">Add Role</button>
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
		$(document).ready(function() {
			  $("#roleForm").validate({
			rules: {
				role: {
					required: true,
					minlength: 3
				},
				"permissions[]": {
					required:true
				}
			},
			messages: {
				name: {
					required: "Please enter role",
					minlength: "Your role name must consist of at least 2 characters"
				},
			},
			 submitHandler: function (form) {
				var formData = new FormData(form);
				$.ajax({
					url: '{{ route("storeRole"); }}',
					method: 'POST',
					data: formData,
					processData: false,
                	contentType: false,
					success: function (res) {
						if(res.status == true) {
							toastr.success(res.msg);
							$('#add-role').modal('hide');
							$('#roleForm')[0].reset();
						}else if(res.status ==422) {
							toastr.error(res.errors.role);
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

		function editRole(id) {
			$.ajax({
					url: '{{ route("editRole"); }}',
					method: 'POST',
					data: formData,
					processData: false,
                	contentType: false,
					success: function (res) {
						if(res.status == true) {
							toastr.success(res.msg);
							$('#edit-role').modal('show');
							$('#roleForm')[0].reset();
						}else if(res.status ==422) {
							toastr.error(res.errors.role);
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
		
	</script>
@endpush
