<table class="table">
    <thead class="thead-light">
        <tr>
            <th>
                <label class="checkboxs">
                    <input type="checkbox" id="select-all">
                    <span class="checkmarks"></span>
                </label>
            </th>
            <th>Unit Name</th>
            <th>Code</th>
            <th>Conversion Rate</th>
            <th>Base Unit</th>
            <th>Created On</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($units as $unit)
            <tr>
                <td>
                    <label class="checkboxs">
                        <input type="checkbox" class="select-row" value="{{ $unit->id }}">
                        <span class="checkmarks"></span>
                    </label>
                </td>
                <td>{{ $unit->name ?? '-' }}</td>
                <td>{{ $unit->code ?? '-' }}</td>
                <td>{{ $unit->conversion_rate ?? '1.0000' }}</td>
                <td>
                    @if ($unit->is_base)
                        <span class="badge bg-success">Yes</span>
                    @else
                        <span class="badge bg-secondary">No</span>
                    @endif
                </td>
                <td>{{ $unit->created_at ? $unit->created_at->format('d M, Y') : '-' }}</td>
                <td class="action-table-data">
                    <div class="edit-delete-action">
                        @if ($unit->status == 1)
                            <a class="me-2 p-2 mb-0" onclick="changeUnitStatus({{ $unit->id }}, 0, 'Are you sure you want to deactivate this unit?')">
                                <i class="ti ti-lock-cancel" title="Deactivate Unit"></i>
                            </a>
                        @else
                            <a class="me-2 p-2 mb-0" onclick="changeUnitStatus({{ $unit->id }}, 1, 'Are you sure you want to activate this unit?')">
                                <i class="ti ti-check" title="Activate Unit"></i>
                            </a>
                        @endif

                        <a class="me-2 p-2 mb-0" onclick="editUnit({{ $unit->id }})" title="Edit Unit">
                            <i class="ti ti-edit"></i>
                        </a>

                        <a onclick="changeUnitStatus({{ $unit->id }}, 2, 'Are you sure you want to delete this unit?')" class="p-2 mb-0" title="Delete Unit">
                            <i class="ti ti-trash"></i>
                        </a>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center text-muted">No units found.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<!-- Pagination & Page Size Selector -->
<div class="d-flex justify-content-between align-items-center mt-3">
    <select class="form-select form-select-sm w-auto" id="getPaging" onchange="location.href='?paging=' + this.value">
        <option value="10" @if(request('paging') == 10) selected @endif>10</option>
        <option value="20" @if(request('paging') == 20) selected @endif>20</option>
        <option value="30" @if(request('paging') == 30) selected @endif>30</option>
    </select>

    <div class="paginglinks">
        {{ $units->withQueryString()->links() }}
    </div>
</div>
