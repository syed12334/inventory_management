<table class="table">
    <thead class="thead-light">
        <tr>
            <th class="no-sort">
                <label class="checkboxs">
                    <input type="checkbox" id="select-all">
                    <span class="checkmarks"></span>
                </label>
            </th>
            <th>User Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Status</th>
            <th class="no-sort" style="width:70px !important">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($users as $user)
            <tr>
                <td>
                    <label class="checkboxs">
                        <input type="checkbox" value="{{ $user->id }}" name="deleteUser[]" class="getusercheckbox">
                        <span class="checkmarks"></span>
                    </label>
                </td>
                <td>
                    <div class="d-flex align-items-center">
                        <a href="javascript:void(0);" class="avatar avatar-md me-2">
                            <img src="{{ asset($user->profile_image ?? 'img/dummyuser.png') }}" alt="user">
                        </a>
                        <a href="javascript:void(0);">{{ $user->name }}</a>
                    </div>
                </td>
                <td>{{ $user->email }}</td>
                <td>
                    @forelse ($user->roles as $role)
                        {{ $role->name }}@if (!$loop->last), @endif
                    @empty
                        <span class="text-muted">No Role</span>
                    @endforelse
                </td>
                <td>
                    @if ($user->status == 1)
                        <span class="badge bg-success">Active</span>
                    @else
                        <span class="badge bg-danger">Inactive</span>
                    @endif
                </td>
                <td>
                    <div class="edit-delete-action">
                        @if ($user->status == 1)
                            <a href="javascript:void(0);" class="me-2" onclick="deleteUser({{ $user->id }}, 0, 'Are you sure you want to deactivate this user?')">
                                <i class="ti ti-lock-cancel" title="Deactivate User"></i>
                            </a>
                        @else
                            <a href="javascript:void(0);" class="me-2" onclick="deleteUser({{ $user->id }}, 1, 'Are you sure you want to activate this user?')">
                                <i class="ti ti-check" title="Activate User"></i>
                            </a>
                        @endif
                        <a href="javascript:void(0);" class="me-2" onclick="edit({{ $user->id }})" title="Edit User">
                            <i class="ti ti-edit"></i>
                        </a>
                        <a href="javascript:void(0);" onclick="deleteUser({{ $user->id }}, 2, 'Are you sure you want to delete this user?')" title="Delete User">
                            <i class="ti ti-trash"></i>
                        </a>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center text-muted">No users found.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<!-- Pagination and Page Size -->
<div class="d-flex justify-content-between align-items-center mt-3">
    <select class="form-select form-select-sm w-auto" id="getPaging"
        onchange="location.href='?paging=' + this.value">
        <option value="10" @selected(request('paging') == 10)>10</option>
        <option value="20" @selected(request('paging') == 20)>20</option>
        <option value="30" @selected(request('paging') == 30)>30</option>
    </select>

    <div class="paginglinks">
        {{ $users->withQueryString()->links() }}
    </div>
</div>
