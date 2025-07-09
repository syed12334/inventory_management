<?php
namespace App\Http\Controllers;
use App\Service\UserService;

use App\Service\WarehouseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class WarehouseController extends Controller
{
    protected $warehouseService;
    public function __construct(WarehouseService $warehouseService) {
        $this->warehouseService  = $warehouseService;
    }
    /* Fetch all user table */
    public function index(Request $request)
    {
        $warehouse_filters = [
            'role_scope' => 'warehouse'
        ];
        if (!empty($request->all())) {
            $warehouse_filters = array_merge($warehouse_filters, $request->all());
        }

        $users = $this->warehouseService->getUsersByRole($warehouse_filters);

        $roles = $this->warehouseService->getRolesByName("warehouse");

        $store_filters = [
            'role_scope' => 'store'
        ];

        $store_users = $this->warehouseService->getUsersByRole($store_filters);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('warehouse.partials.table', compact('store_users', 'users', 'roles'))->render()
            ]);
        }

        return view('warehouse.list', compact('store_users', 'users', 'roles'));
    }

    public function edit($id) {
          $userData =  $this->warehouseService->getuserbyid($id);
    }


    /* Store users into databse */
    public function store(Request $request) {
        return $this->warehouseService->storeUsers($request->all());
    }
    /* User status change */
    public function userStatus(Request $request) {
        $userStatusList = $this->userService->delete($request->all());
        return back()->with('success', $userStatusList['msg']);
    }
    /* Multiple delete */
    public function deleteMultiple(Request $request) {
        $data = $this->warehouseService->deleteMultiple($request->all());

        return back()->with('success', $data['msg']);
    }
    public function editwarehouse(Request $request)
    {
        try {
            $user_id = $request->input('user_id');

            if (!$user_id) {
                return response()->json([
                    'status' => false,
                    'msg' => 'User ID is required.'
                ], 400);
            }

            $userData = $this->warehouseService->getuserbyid($user_id);

            if (!empty($userData) && count((array)$userData) > 0) {
                return response()->json([
                    'status' => true,
                    'msg' => 'User data found.',
                    'data' => json_decode(json_encode($userData), true)
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'msg' => 'No data found.'
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Error in editwarehouse: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'msg' => 'Server error. Please try again later.'
            ], 500);
        }
    }
    public function update(Request $request) {
       return $this->warehouseService->userUpdate($request->all());
    }
}
 