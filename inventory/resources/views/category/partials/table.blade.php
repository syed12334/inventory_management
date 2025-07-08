<table class="table">
    <thead class="thead-light">
        <tr>
            <th>
                <label class="checkboxs">
                    <input type="checkbox" id="select-all">
                    <span class="checkmarks"></span>
                </label>
            </th>
            <th>Category</th>
            <th>Category Slug</th>
            <th>Created On</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($categories as $category)
            <tr>
                <td>
                    <label class="checkboxs">
                        <input type="checkbox" class="select-row" value="{{ $category->category_id }}">
                        <span class="checkmarks"></span>
                    </label>
                </td>
                <td>{{ $category->title }}</td>
                <td>{{ $category->slug }}</td>
                <td>{{ $category->created_at ? $category->created_at->format('d M, Y') : '-' }}</td>
                <td>
                    @if ($category->status == 1)
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
                        @if ($category->status == 1)
                            <a class="me-2 p-2 mb-0" onclick="changeCategoryStatus({{ $category->category_id }}, 0, 'Are you sure you want to deactivate this category?')">
                                <i class="ti ti-lock-cancel" title="Deactivate Category"></i>
                            </a>
                        @else
                            <a class="me-2 p-2 mb-0" onclick="changeCategoryStatus({{ $category->category_id }}, 1, 'Are you sure you want to activate this category?')">
                                <i class="ti ti-check" title="Activate Category"></i>
                            </a>
                        @endif

                        <a class="me-2 p-2 mb-0" onclick="editCategory({{ $category->category_id }})" title="Edit Category">
                            <i class="ti ti-edit"></i>
                        </a>
                        <a onclick="changeCategoryStatus({{ $category->category_id }}, 2, 'Are you sure you want to delete this category?')" class="p-2 mb-0" title="Delete Category">
                            <i class="ti ti-trash"></i>
                        </a>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center text-muted">No categories found.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<!-- Pagination & Page Size Selector -->
<div class="d-flex justify-content-between align-items-center mt-3">
    <select class="form-select form-select-sm w-auto" id="getPaging">
        <option value="10" @if(request('paging') == 10) selected @endif>10</option>
        <option value="20" @if(request('paging') == 20) selected @endif>20</option>
        <option value="30" @if(request('paging') == 30) selected @endif>30</option>
    </select>

    <div class="paginglinks">
        {{ $categories->links() }}
    </div>
</div>
