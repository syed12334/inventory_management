@extends('layouts.app')

@section('title', 'Units')

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
                <h4 class="fw-bold">Units</h4>
                <h6>Manage your units</h6>
            </div>
        </div>

        <ul class="table-top-head">
            <li><a data-bs-toggle="tooltip" title="Pdf"><img src="{{ asset('img/icons/pdf.svg') }}" alt="PDF"></a></li>
            <li><a data-bs-toggle="tooltip" title="Excel"><img src="{{ asset('img/icons/excel.svg') }}" alt="Excel"></a></li>
            <li><a data-bs-toggle="tooltip" title="Refresh"><i class="ti ti-refresh"></i></a></li>
            <li><a data-bs-toggle="tooltip" title="Collapse"><i class="ti ti-chevron-up"></i></a></li>
        </ul>

        <div class="page-btn">
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-unit">
                <i class="ti ti-circle-plus me-1"></i>Add Unit
            </a>
        </div>
    </div>

    <!-- Unit List -->
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
            <div class="table-responsive" id="unitListTable">
                @include('unit.partials.table', ['units' => $units])
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Unit Modal -->
<div class="modal fade" id="add-unit" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 id="modalTitle">Add Unit</h4>
                <button type="button" class="close bg-danger text-white fs-16" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

           <form action="{{ route('unit.store') }}" method="post" id="unitForm">
                @csrf
                <input type="hidden" name="unit_id" id="unit_id" value="">

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Unit Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Enter unit name" required>
                        <span class="text-danger" id="error_name"></span>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Unit Code <span class="text-danger">*</span></label>
                        <input type="text" name="code" id="code" class="form-control" placeholder="e.g. kg, m, pcs" required>
                        <span class="text-danger" id="error_code"></span>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Conversion Rate <span class="text-danger">*</span></label>
                        <input type="number" name="conversion_rate" id="conversion_rate" step="0.0001" class="form-control" placeholder="e.g. 1.0000" required>
                        <span class="text-danger" id="error_conversion_rate"></span>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Is Base Unit?</label>
                        <select name="is_base" id="is_base" class="form-select">
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="unitSubmitButton">Add Unit</button>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    const unitIndexUrl  = "{{ route('unit.index') }}";
    const unitEditUrl   = "{{ route('unit.edit') }}";
    const unitUpdateUrl = "{{ route('unit.update') }}";

    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    $(document).ready(function () {
        $("#unitForm").validate({
            rules: {
                name: {
                    required: true,
                    minlength: 2
                },
                code: {
                    required: true
                },
                conversion_rate: {
                    required: true,
                    number: true,
                    min: 0
                }
            },
            messages: {
                name: {
                    required: "Please enter a unit name",
                    minlength: "Unit name must be at least 2 characters"
                },
                code: {
                    required: "Please enter a unit code"
                },
                conversion_rate: {
                    required: "Please enter a conversion rate",
                    number: "Must be a valid number",
                    min: "Must be greater than or equal to 0"
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
                        if (res.status) {
                            toastr.success(res.msg);
                            $('#add-unit').modal('hide');
                            refreshUnitTable();
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

                return false;
            }
        });
    });

    function refreshUnitTable() {
        $.ajax({
            url: unitIndexUrl,
            type: 'GET',
            success: function (res) {
                $('#unitListTable').html(res.html);
            },
            error: function () {
                toastr.error('Failed to refresh unit list.');
            }
        });
    }

    function editUnit(unit_id) {
        $.ajax({
            url: unitEditUrl,
            type: 'POST',
            data: {
                unit_id: unit_id,
                _token: "{{ csrf_token() }}"
            },
            success: function (response) {
                if (response.status) {
                    const unit = response.data;

                    $('#unitForm')[0].reset();
                    $('#name').val(unit.name);
                    $('#code').val(unit.code);
                    $('#conversion_rate').val(unit.conversion_rate);
                    $('#is_base').val(unit.is_base);
                    $('#unit_id').val(unit.id);

                    $('#modalTitle').text('Edit Unit');
                    $('#unitSubmitButton').text('Update Unit');
                    $('#unitForm').attr('action', unitUpdateUrl);
                    $('#add-unit').modal('show');
                } else {
                    toastr.error(response.msg || 'Unit not found');
                }
            },
            error: function () {
                toastr.error('Error while loading unit details.');
            }
        });
    }
</script>
@endpush
