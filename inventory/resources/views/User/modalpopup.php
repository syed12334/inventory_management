	<!-- Add user -->
		<div class="modal fade" id="add-user">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="page-wrapper-new p-0">
						<div class="content">
							<div class="modal-header">
								<div class="page-title">
									<h4>Add User</h4>
								</div>
								<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<form action="{{ route('storeUser'); }}" method="post" id="usersList">
								@csrf
								<div class="modal-body">
									<div class="row">
									
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
												<select class="select" name="role" id="role" required>
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
												<input type="tel" class="form-control" name="phone"  maxlength="10" required>
												<span class="text-danger" id="error_phone"></span>
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
									<button type="submit" class="btn btn-primary">Add User</button>
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