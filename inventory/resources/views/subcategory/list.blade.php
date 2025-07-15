@extends('layouts.app')

@section('title', 'Sub Category')

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
                    <h4 class="fw-bold">Sub Category</h4>
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
                <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-subcategory">
                    <i class="ti ti-circle-plus me-1"></i>Add Sub Category
                </a>
            </div>
        </div>

        <!-- sub Category List -->
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
                <div class="table-responsive" id="subcategoryListtable">
                     @include('subcategory.partials.table', ['subcategories' => $subcategories])
                </div>
            </div>
        </div>
        <!-- /sub Category List -->
    </div>

    <!-- Add sub Category Modal -->
    <div class="modal fade" id="add-subcategory" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="page-title">
                        <h4 id="modalTitle">Add Sub Category</h4>
                    </div>
                    <button type="button" class="close bg-danger text-white fs-16" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form action="{{ route('subcategory.storeCategory') }}" method="post" id="categoryform">
                    <input type="hidden" name="subcategory_id" id="subcategory_id" value="">
                    @csrf

                    <div class="modal-body">
                        {{-- sub Category Selection --}}
                        <div class="mb-3">
                            <label class="form-label">Select Category <span class="text-danger ms-1">*</span></label>
                            <select class="form-select" name="category_id" id="category_id" required>
                                <option value="">-- Select Category --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->category_id }}">{{ $category->title }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger" id="error_category_id"></span>
                        </div>

                        {{-- Subcategory Name --}}
                        <div class="mb-3">
                            <label class="form-label">Subcategory Name <span class="text-danger ms-1">*</span></label>
                            <input type="text" class="form-control" name="name" id="name" placeholder="Subcategory Name" required>
                            <span class="text-danger" id="error_name"></span>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn me-2 btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="categorySubmitButton">Add Subcategory</button>
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

    $(function () {
        const $form = $('#categoryform');
        const listUrl = "{{ route('subcategory.index') }}";
        const storeUrl = "{{ route('subcategory.storeCategory') }}";
        const editUrl = "{{ route('subcategory.editCategory') }}";
        const updateUrl = "{{ route('subcategory.updateCategory') }}";

        // Validation and Submit Handler
        $form.validate({
            rules: {
                category_id: { required: true },
                name: { required: true, minlength: 3 }
            },
            messages: {
                category_id: { required: "Please select a category" },
                name: {
                    required: "Please enter a sub category",
                    minlength: "Sub category must be at least 3 characters"
                }
            },
            submitHandler: function (form) {
                const formData = new FormData(form);

                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (res) {
                        if (res.status === true) {
                            toastr.success(res.msg);
                            $('#add-subcategory').modal('hide');
                            refreshSubcategoryList();
                        } else if (res.status === 422 && res.errors) {
                            $.each(res.errors, function (key, val) {
                                $('#error_' + key).text(val[0]);
                                $("#" + key).addClass('error');
                            });
                        } else {
                            toastr.error(res.msg || "Something went wrong.");
                        }
                    },
                    error: ajaxError
                });

                return false;
            }
        });

        function refreshSubcategoryList() {
            $.get(listUrl)
                .done(function (res) {
                    $('#subcategoryListtable').html(res.html);
                })
                .fail(function () {
                    toastr.error('Failed to refresh sub category list.');
                });
        }

        function ajaxError(jqXHR, textStatus, errorThrown) {
            let msg = "Something went wrong!";
            try {
                const res = jqXHR.responseJSON || JSON.parse(jqXHR.responseText);
                if (res.errors) {
                    const messages = Object.values(res.errors).flat().join('<br>');
                    toastr.error(messages);
                    return;
                } else if (res.msg) {
                    toastr.error(res.msg);
                    return;
                }
            } catch (err) {
                console.error("Error parsing response:", err);
            }
            toastr.error(msg);
        }

        function resetSubcategoryForm() {
            $form[0].reset();
            $('.error').text('');
            $('.is-invalid').removeClass('is-invalid');
            $('#subcategory_id').val('');
        }

        // Reset form when modal closes
        $('#add-subcategory').on('hidden.bs.modal', function () {
            resetSubcategoryForm();
            $('#modalTitle').text('Add Sub Category');
            $('#categorySubmitButton').text('Add Sub Category');
            $form.attr('action', storeUrl).attr('method', 'POST');
        });

        // Expose globally
        window.editSubCategory = function (sub_category_id) {
            $.post(editUrl, { sub_category_id })
                .done(function (response) {
                    if (response.status === true) {
                        const sub = response.data;

                        resetSubcategoryForm();
                        $('#name').val(sub.subcategory_name);
                        $('#category_id').val(sub.category_id);
                        $('#subcategory_id').val(sub.subcategory_id); // make sure this field exists

                        $('#modalTitle').text('Edit Sub Category');
                        $('#categorySubmitButton').text('Update Sub Category');
                        $form.attr('action', updateUrl).attr('method', 'POST');
                        $('#add-subcategory').modal('show');
                    } else {
                        toastr.error(response.message || "Unable to load sub category.");
                    }
                })
                .fail(ajaxError);
        };
    });
</script>
@endpush

