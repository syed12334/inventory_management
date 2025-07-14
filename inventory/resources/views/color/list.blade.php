@extends('layouts.app')

@section('title', 'Brands')

@push('style')
    <style>
        #title-error {
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
                <h4 class="fw-bold">Brands</h4>
                <h6>Manage your brands</h6>
            </div>
        </div>

        <ul class="table-top-head">
            <li><a data-bs-toggle="tooltip" title="Pdf"><img src="{{ asset('img/icons/pdf.svg') }}" alt="PDF"></a></li>
            <li><a data-bs-toggle="tooltip" title="Excel"><img src="{{ asset('img/icons/excel.svg') }}" alt="Excel"></a></li>
            <li><a data-bs-toggle="tooltip" title="Refresh"><i class="ti ti-refresh"></i></a></li>
            <li><a data-bs-toggle="tooltip" title="Collapse"><i class="ti ti-chevron-up"></i></a></li>
        </ul>

        <div class="page-btn">
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-brand">
                <i class="ti ti-circle-plus me-1"></i>Add Brand
            </a>
        </div>
    </div>

    <!-- Brand List -->
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
        </div>

        <div class="card-body p-0">
            <div class="table-responsive" id="brandListTable">
                @include('brand.partials.table', ['brands' => $brands])
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Brand Modal -->
<div class="modal fade" id="add-brand" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 id="modalTitle">Add Brand</h4>
                <button type="button" class="close bg-danger text-white fs-16" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

           <form action="{{ route('brand.store') }}" method="post" id="brandForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="brand_id" id="brand_id" value="">

                <div class="modal-body">
                    {{-- Brand Title --}}
                    <div class="mb-3">
                        <label class="form-label">Brand Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="title" class="form-control" placeholder="Enter brand title" required>
                        <span class="text-danger" id="error_title"></span>
                    </div>

                    {{-- Brand Image --}}
                    <div class="mb-3">
                        <label class="form-label">Brand Image <span class="text-danger"></span></label>
                        <input type="file" name="brand_img" id="brand_img" class="form-control" accept="image/*">
                        <span class="text-danger" id="error_brand_img"></span>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="brandSubmitButton">Add Brand</button>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    const brandIndexUrl  = "{{ route('brand.index') }}";
    const brandEditUrl   = "{{ route('brand.edit') }}";
    const brandUpdateUrl = "{{ route('brand.update') }}";

    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    $(document).ready(function () {
        $("#brandForm").validate({
            rules: {
                title: {
                    required: true,
                    minlength: 2
                },
                brand_img: {
                    extension: "png|jpeg|jpg|gif"   // optional & only these types
                }
            },
            messages: {
                title: {
                    required: "Please enter a brand name",
                    minlength: "Brand name must be at least 2 characters"
                },
                brand_img: {
                    extension: "Only PNG, JPG, JPEG, or GIF files are allowed"
                }
            },  // ← **comma was missing here**
            submitHandler: function (form) {
                const formData = new FormData(form);

                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (res) {
                        if (res.status) {
                            toastr.success(res.msg);
                            $('#add-brand').modal('hide');
                            refreshBrandTable();
                        } else if (res.errors) {
                            $.each(res.errors, function (key, val) {
                                $('#error_' + key).text(val[0]);
                                $('#' + key).addClass('error');
                            });
                        }
                    },
                    error: function () {
                        toastr.error('Something went wrong!');
                    }
                });

                return false; // prevent default form submit
            }
        });
    });

    function refreshBrandTable() {
        $.ajax({
            url: brandIndexUrl,
            type: 'GET',
            success: function (res) {
                $('#brandListTable').html(res.html);
            },
            error: function () {
                toastr.error('Failed to refresh brand list.');
            }
        });
    }

    function editBrand(brand_id) {
        $.ajax({
            url: brandEditUrl,
            type: 'POST',
            data: {
                brand_id: brand_id,                  // use correct param name
                _token: "{{ csrf_token() }}"
            },
            success: function (response) {
                if (response.status) {
                    const brand = response.data;

                    $('#brandForm')[0].reset();
                    $('#title').val(brand.title);
                    $('#brand_id').val(brand.brand_id);

                    $('#modalTitle').text('Edit Brand');
                    $('#brandSubmitButton').text('Update Brand');
                    $('#brandForm').attr('action', brandUpdateUrl);
                    $('#add-brand').modal('show');
                } else {
                    toastr.error(response.msg || 'Brand not found');
                }
            },
            error: function () {
                toastr.error('Error while loading brand details.');
            }
        });
    }
</script>
@endpush
