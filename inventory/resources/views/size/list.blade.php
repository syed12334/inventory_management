@extends('layouts.app')

@section('title', 'Sizes')

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
    <div class="page-header d-flex justify-content-between align-items-center">
        <div>
            <h4 class="fw-bold">Sizes</h4>
            <h6>Manage your sizes</h6>
        </div>

        <div class="d-flex align-items-center">
            <ul class="table-top-head me-3">
                <li><a data-bs-toggle="tooltip" title="PDF"><img src="{{ asset('img/icons/pdf.svg') }}" alt="PDF"></a></li>
                <li><a data-bs-toggle="tooltip" title="Excel"><img src="{{ asset('img/icons/excel.svg') }}" alt="Excel"></a></li>
                <li><a href="#" onclick="refreshSizeTable()" data-bs-toggle="tooltip" title="Refresh"><i class="ti ti-refresh"></i></a></li>
            </ul>

            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-size">
                <i class="ti ti-circle-plus me-1"></i> Add Size
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <div class="search-set w-100">
                <div class="search-input">
                    <input type="search" class="form-control form-control-sm" placeholder="Search">
                    <span class="btn-searchset"><i class="ti ti-search"></i></span>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive" id="sizeListTable">
                @include('size.partials.table', ['sizes' => $sizes])
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Size Modal -->
<div class="modal fade" id="add-size" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 id="modalTitle">Add Size</h4>
                <button type="button" class="btn-close bg-danger text-white" data-bs-dismiss="modal" aria-label="Close">
                    &times;
                </button>
            </div>

            <form action="{{ route('size.store') }}" method="post" id="sizeForm">
                @csrf
                <input type="hidden" name="si_id" id="si_id" value="">

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Size Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Enter size name" required>
                        <span class="text-danger" id="error_name"></span>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="sizeSubmitButton">Add Size</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    const sizeIndexUrl  = "{{ route('size.index') }}";
    const sizeEditUrl   = "{{ route('size.edit') }}";
    const sizeUpdateUrl = "{{ route('size.update') }}";

    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    $(document).ready(function () {
        $("#sizeForm").validate({
            rules: {
                name: {
                    required: true,
                    minlength: 1
                }
            },
            messages: {
                name: {
                    required: "Please enter a size name",
                    minlength: "Size name must be at least 1 character"
                }
            },
            submitHandler: function (form) {
                const formData = new FormData(form);
                const isUpdate = $('#si_id').val() !== '';

                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (res) {
                        if (res.status) {
                            toastr.success(res.msg);
                            $('#add-size').modal('hide');
                            refreshSizeTable();
                            form.reset();
                            $('#si_id').val('');
                            $('#sizeForm').attr('action', "{{ route('size.store') }}");
                            $('#modalTitle').text('Add Size');
                            $('#sizeSubmitButton').text('Add Size');
                        } else if (res.errors) {
                            $.each(res.errors, function (key, val) {
                                $('#error_' + key).text(val[0]);
                                $('#' + key).addClass('is-invalid');
                            });
                        }
                    },
                    error: function () {
                        toastr.error('Something went wrong!');
                    }
                });

                return false;
            }
        });
    });

    function refreshSizeTable() {
        $.ajax({
            url: sizeIndexUrl,
            type: 'GET',
            success: function (res) {
                if (res.status) {
                    $('#sizeListTable').html(res.html);
                } else {
                    toastr.error('Failed to refresh size list.');
                }
            },
            error: function () {
                toastr.error('Failed to refresh size list.');
            }
        });
    }

    function editSize(si_id) {
        $.ajax({
            url: sizeEditUrl,
            type: 'POST',
            data: {
                si_id: si_id,
                _token: "{{ csrf_token() }}"
            },
            success: function (response) {
                if (response.status) {
                    const size = response.data;

                    $('#sizeForm')[0].reset();
                    $('#name').val(size.sname);
                    $('#si_id').val(size.s_id);

                    $('#modalTitle').text('Edit Size');
                    $('#sizeSubmitButton').text('Update Size');
                    $('#sizeForm').attr('action', sizeUpdateUrl);
                    $('#add-size').modal('show');
                } else {
                    toastr.error(response.msg || 'Size not found');
                }
            },
            error: function () {
                toastr.error('Error while loading size details.');
            }
        });
    }
</script>
@endpush
