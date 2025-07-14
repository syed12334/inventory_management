@extends('layouts.app')

@section('title', 'Colors')

@push('style')
<style>
    #name-error, #ccode-error {
        background-image: none !important;
        margin-top: 3px;
    }
</style>
@endpush

@section('content')
<div class="content">
    <div class="page-header d-flex justify-content-between align-items-center">
        <div>
            <h4 class="fw-bold">Colors</h4>
            <h6>Manage your colors</h6>
        </div>

        <div class="d-flex align-items-center">
            <ul class="table-top-head me-3">
                <li><a data-bs-toggle="tooltip" title="PDF"><img src="{{ asset('img/icons/pdf.svg') }}" alt="PDF"></a></li>
                <li><a data-bs-toggle="tooltip" title="Excel"><img src="{{ asset('img/icons/excel.svg') }}" alt="Excel"></a></li>
                <li><a href="#" onclick="refreshColorTable()" data-bs-toggle="tooltip" title="Refresh"><i class="ti ti-refresh"></i></a></li>
            </ul>

            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-color">
                <i class="ti ti-circle-plus me-1"></i> Add Color
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
            <div class="table-responsive" id="colorListTable">
                @include('color.partials.table', ['colors' => $colors])
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Color Modal -->
<div class="modal fade" id="add-color" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 id="modalTitle">Add Color</h4>
                <button type="button" class="btn-close bg-danger text-white" data-bs-dismiss="modal" aria-label="Close">
                    &times;
                </button>
            </div>

            <form action="{{ route('color.store') }}" method="post" id="colorForm">
                @csrf
                <input type="hidden" name="co_id" id="co_id" value="">

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Color Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Enter color name" required>
                        <span class="text-danger" id="error_name"></span>
                    </div>

                    <div class="mb-3">
                        <label for="ccode" class="form-label">Color Code <span class="text-danger">*</span></label>
                        <div class="d-flex align-items-center gap-2">
                            <input name="ccode" type="color" class="form-control form-control-color m-auto" 
                                id="ccode" value="#000000" required>
                            <input type="text" class="form-control" id="ccode_text" value="#000000" readonly>
                        </div>
                        <span class="text-danger" id="error_ccode"></span>
                    </div>

                      
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="colorSubmitButton">Add Color</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>

   
    document.getElementById('ccode').addEventListener('input', function () {
        document.getElementById('ccode_text').value = this.value;
    });


    const colorIndexUrl  = "{{ route('color.index') }}";
    const colorEditUrl   = "{{ route('color.edit') }}";
    const colorUpdateUrl = "{{ route('color.update') }}";

    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    $(document).ready(function () {
        $("#colorForm").validate({
            rules: {
                name: {
                    required: true,
                    minlength: 2
                },
                ccode: {
                    required: true,
                    pattern: /^#?[0-9A-Fa-f]{3,8}$/
                }
            },
            messages: {
                name: {
                    required: "Please enter a color name",
                    minlength: "Color name must be at least 2 characters"
                },
                ccode: {
                    required: "Please enter a hex color code",
                    pattern: "Invalid color code format (e.g. #FFF or #000000)"
                }
            },
            submitHandler: function (form) {
                const formData = new FormData(form);
                const isUpdate = $('#co_id').val() !== '';

                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (res) {
                        if (res.status) {
                            toastr.success(res.msg);
                            $('#add-color').modal('hide');
                            refreshColorTable();
                            form.reset();
                            $('#co_id').val('');
                            $('#colorForm').attr('action', "{{ route('color.store') }}");
                            $('#modalTitle').text('Add Color');
                            $('#colorSubmitButton').text('Add Color');
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

    function refreshColorTable() {
        $.ajax({
            url: colorIndexUrl,
            type: 'GET',
            success: function (res) {
                if (res.status) {
                    $('#colorListTable').html(res.html);
                } else {
                    toastr.error('Failed to refresh color list.');
                }
            },
            error: function () {
                toastr.error('Failed to refresh color list.');
            }
        });
    }

    function editColor(co_id) {
        $.ajax({
            url: colorEditUrl,
            type: 'POST',
            data: {
                co_id: co_id,
                _token: "{{ csrf_token() }}"
            },
            success: function (response) {
                if (response.status) {
                    const color = response.data;

                    $('#colorForm')[0].reset();
                    $('#name').val(color.name);
                    $('#ccode').val(color.ccode);
                    $('#co_id').val(color.co_id);

                    $('#modalTitle').text('Edit Color');
                    $('#colorSubmitButton').text('Update Color');
                    $('#colorForm').attr('action', colorUpdateUrl);
                    $('#add-color').modal('show');
                } else {
                    toastr.error(response.msg || 'Color not found');
                }
            },
            error: function () {
                toastr.error('Error while loading color details.');
            }
        });
    }
</script>
@endpush
