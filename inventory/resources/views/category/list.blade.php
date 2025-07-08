@extends('layouts.app')

@section('title', 'Category')

@push('style')
    <style>
        #name-error {
            background-image: none !important;
            margin-top: 3px;
        }
    </style>
@endpush

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4 class="fw-bold">Category</h4>
                    <h6>Manage your categories</h6>
                </div>
            </div>

            <ul class="table-top-head">
                <li><a data-bs-toggle="tooltip" title="Pdf"><img src="{{ asset('img/icons/pdf.svg') }}" alt="PDF"></a></li>
                <li><a data-bs-toggle="tooltip" title="Excel"><img src="{{ asset('img/icons/excel.svg') }}" alt="Excel"></a></li>
                <li><a data-bs-toggle="tooltip" title="Refresh"><i class="ti ti-refresh"></i></a></li>
                <li><a data-bs-toggle="tooltip" title="Collapse"><i class="ti ti-chevron-up"></i></a></li>
            </ul>

            <div class="page-btn">
                <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-category">
                    <i class="ti ti-circle-plus me-1"></i>Add Category
                </a>
            </div>
        </div>

        <!-- Category List -->
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between flex-wrap row-gap-3">
                <div class="search-set">
                    <div class="search-input">
                        <span class="btn-searchset">
                            <i class="ti ti-search fs-14 feather-search"></i>
                        </span>
                        <input type="search" class="form-control form-control-sm" placeholder="Search">
                    </div>
                </div>
                <div class="d-flex table-dropdown my-xl-auto right-content align-items-center flex-wrap row-gap-3">
                    <div class="dropdown">
                        <a href="#" class="dropdown-toggle btn btn-white btn-md d-inline-flex align-items-center" data-bs-toggle="dropdown">
                            Status
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end p-3">
                            <li><a href="#" class="dropdown-item rounded-1">Active</a></li>
                            <li><a href="#" class="dropdown-item rounded-1">Inactive</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive" id="categoryListtable">
                     @include('category.partials.table', ['categories' => $categories])
                </div>
            </div>
        </div>
        <!-- /Category List -->
    </div>

    <!-- Add Category Modal -->
    <div class="modal fade" id="add-category" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="page-title">
                        <h4 id="modalTitle">Add Category</h4>
                    </div>
                    <button type="button" class="close bg-danger text-white fs-16" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form action="{{ route('category.storeCategory') }}" method="post" id="categoryform">
                    <input type="hidden" name="category_id" id="category_id" value="">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Category<span class="text-danger ms-1">*</span></label>
                            <input type="text" class="form-control" name="name" id="name" placeholder="Category Name" required>
                            <span class="text-danger" id="error_name"></span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn me-2 btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="categorySubmitButton">Add Category</button>

                    </div>
                </form>
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

        $(document).ready(function () {
            $("#categoryform").validate({
                rules: {
                    name: {
                        required: true,
                        minlength: 3
                    }
                },
                messages: {
                    name: {
                        required: "Please enter a category",
                        minlength: "Your category name must consist of at least 3 characters"
                    }
                },
                submitHandler: function (form) {
                    let formData = new FormData(form);

                    $.ajax({
                        url: form.action,
                        type: form.method,
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (res) {
                            if (res.status === true) {
                                toastr.success(res.msg);
                                $('#add-category').modal('hide');
                               

                                $.ajax({
                                    url: "{{ route('category.index') }}",
                                    type: 'GET',
                                    success: function (res) {
                                        $('#categoryListtable').html(res.html);
                                    },
                                    error: function () {
                                        toastr.error('Failed to refresh category list.');
                                    }
                                });
                            } else if (res.status === 422) {
                                $.each(res.errors, function (key, val) {
                                    $('#error_' + key).text(val[0]);
                                    $("#" + key).addClass('error');
                                });
                            }
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            let msg = "Something went wrong!";
                            if (jqXHR.status === 403) {
                                msg = "Token error! Please try again.";
                            } else {
                                msg = textStatus + " - " + errorThrown;
                            }
                            toastr.error(msg);
                        }
                    }); 

                    return false;
                    
                }
            });
        });

        function editCategory(category_id) {
    $.ajax({
        url: "{{ route('category.editCategory') }}",
        method: "POST",
        cache: false,
        dataType: "json",
        data: {
            category_id: category_id,
            _token: "{{ csrf_token() }}"
        },
        success: function(response) {
            if (response.status === true) {
                const category = response.data;
                $("#name").val(category.title);
                 $('#category_id').val(category.category_id);
                $("#modalTitle").text('Edit Category');
                $("#add-category").modal('show');
                $("#categorySubmitButton").text('Edit Category');
                $("#categoryform").attr("action", "{{ route('category.updateCategory') }}");

            } else {
                alert(response.msg || "Category not found.");
            }
        },
        error: function(xhr, status, error) {
            console.error("AJAX Error:", {
                status: xhr.status,
                responseText: xhr.responseText,
                errorThrown: error
            });

            let errorMsg = "Something went wrong. Please try again.";

            // Try to parse Laravel validation or exception message
            try {
                const res = JSON.parse(xhr.responseText);
                if (res.message) {
                    errorMsg = res.message;
                }
            } catch (e) {
                // Response not JSON — fallback to default
            }

            alert(errorMsg);
        }
    });
}


    </script>
@endpush
