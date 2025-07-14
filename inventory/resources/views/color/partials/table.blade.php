<table class="table">
    <thead class="thead-light">
        <tr>
            <th>
                <label class="checkboxs">
                    <input type="checkbox" id="select-all">
                    <span class="checkmarks"></span>
                </label>
            </th>
            <th>Brand Name</th>
            <th>Image</th>
            <th>Created On</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($brands as $brand)
            <tr>
                <td>
                    <label class="checkboxs">
                        <input type="checkbox" class="select-row" value="{{ $brand->brand_id }}">
                        <span class="checkmarks"></span>
                    </label>
                </td>
                <td>{{ $brand->title ?? '-' }}</td>
                <td>
                    @if($brand->brand_img)
                        <img src="{{ asset('uploads/brand/' . $brand->brand_img) }}" alt="Brand Image" width="40" height="40">
                    @else
                        <span class="text-muted">No Image</span>
                    @endif
                </td>
                <td>{{ $brand->created_at ? $brand->created_at->format('d M, Y') : '-' }}</td>
                <td>
                    @if ($brand->status == 1)
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
                        @if ($brand->status == 1)
                            <a class="me-2 p-2 mb-0" onclick="changeBrandStatus({{ $brand->brand_id }}, 0, 'Are you sure you want to deactivate this brand?')">
                                <i class="ti ti-lock-cancel" title="Deactivate Brand"></i>
                            </a>
                        @else
                            <a class="me-2 p-2 mb-0" onclick="changeBrandStatus({{ $brand->brand_id }}, 1, 'Are you sure you want to activate this brand?')">
                                <i class="ti ti-check" title="Activate Brand"></i>
                            </a>
                        @endif

                        <a class="me-2 p-2 mb-0" onclick="editBrand({{ $brand->brand_id }})" title="Edit Brand">
                            <i class="ti ti-edit"></i>
                        </a>

                        <a onclick="changeBrandStatus({{ $brand->brand_id }}, 2, 'Are you sure you want to delete this brand?')" class="p-2 mb-0" title="Delete Brand">
                            <i class="ti ti-trash"></i>
                        </a>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center text-muted">No brands found.</td>
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
        {{ $brands->withQueryString()->links() }}
    </div>
</div>
