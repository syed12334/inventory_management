<table class="table">
    <thead class="thead-light">
        <tr>
            <th>
                <label class="checkboxs">
                    <input type="checkbox" id="select-all">
                    <span class="checkmarks"></span>
                </label>
            </th>
            <th>Color Name</th>
            <th>Color Code</th>
            <th>Created On</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($colors as $color)
            <tr>
                <td>
                    <label class="checkboxs">
                        <input type="checkbox" class="select-row" value="{{ $color->co_id }}">
                        <span class="checkmarks"></span>
                    </label>
                </td>
                <td>{{ $color->name ?? '-' }}</td>
                <td>
                    <span class="badge" style="background-color: {{ $color->ccode }}; color: #fff;">
                        {{ $color->ccode }}
                    </span>
                </td>
                <td>{{ $color->created_at ? $color->created_at->format('d M, Y') : '-' }}</td>
                <td>
                    @if ($color->status == 1)
                        <span class="d-inline-flex align-items-center p-1 pe-2 rounded-1 text-white bg-success fs-10">
                            Active
                        </span>
                    @else
                        <span class="d-inline-flex align-items-center p-1 pe-2 rounded-1 text-white bg-danger fs-10">
                            Inactive
                        </span>
                    @endif
                </td>
                <td class="action-table-data">
                    <div class="edit-delete-action">
                        @if ($color->status == 1)
                            <a class="me-2 p-2 mb-0" onclick="changeColorStatus({{ $color->co_id }}, 0, 'Are you sure you want to deactivate this color?')">
                                <i class="ti ti-lock-cancel" title="Deactivate Color"></i>
                            </a>
                        @else
                            <a class="me-2 p-2 mb-0" onclick="changeColorStatus({{ $color->co_id }}, 1, 'Are you sure you want to activate this color?')">
                                <i class="ti ti-check" title="Activate Color"></i>
                            </a>
                        @endif

                        <a class="me-2 p-2 mb-0" onclick="editColor({{ $color->co_id }})" title="Edit Color">
                            <i class="ti ti-edit"></i>
                        </a>

                        <a onclick="changeColorStatus({{ $color->co_id }}, 2, 'Are you sure you want to delete this color?')" class="p-2 mb-0" title="Delete Color">
                            <i class="ti ti-trash"></i>
                        </a>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center text-muted">No colors found.</td>
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
        {{ $colors->withQueryString()->links() }}
    </div>
</div>
