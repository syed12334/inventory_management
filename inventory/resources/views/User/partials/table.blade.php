<table class="table datatable">
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
            <th class="no-sort" style="width:70px!important">Action</th>
        </tr>
    </thead>
    <tbody>
        @if(count($users) > 0)
            @foreach($users as $user)
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
                                @if(!empty($user->profile_image))
                                    <img src="{{ asset($user->profile_image) }}" alt="user">
                                @else
                                    <img src="{{ asset('img/dummyuser.png') }}" alt="user">
                                @endif
                            </a>
                            <a href="javascript:void(0);">{{ $user->name }}</a>
                        </div>
                    </td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @foreach($user->roles as $role)
                            {{ $role->name }}@if(!$loop->last), @endif
                        @endforeach
                    </td>
                    <td>
                        @if($user->status == 1)
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
                            @if($user->status == 1)
                                <a class="me-2 p-2 mb-0" onclick="deleteUser({{ $user->id }}, 0, 'Are you sure you want to inactive user?')">
                                    <i class="ti ti-lock-cancel" title="Inactive User"></i>
                                </a>
                            @else
                                <a class="me-2 p-2 mb-0" onclick="deleteUser({{ $user->id }}, 1, 'Are you sure you want to activate user?')">
                                    <i class="ti ti-check" title="Activate User"></i>
                                </a>
                            @endif
                            <a class="me-2 p-2 mb-0" onclick="edit({{ $user->id }})" title="Edit User">
                                <i class="ti ti-edit"></i>
                            </a>
                            <a onclick="deleteUser({{ $user->id }}, 2, 'Are you sure you want to delete user?')" class="p-2 mb-0" title="Delete User">
                                <i class="ti ti-trash"></i>
                            </a>
                        </div>
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="6" class="text-center">No users found.</td>
            </tr>
        @endif
    </tbody>
</table>
