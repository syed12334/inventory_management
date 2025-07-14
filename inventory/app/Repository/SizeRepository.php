<?php

namespace App\Repository;

use App\Models\Size;
use Illuminate\Support\Facades\Log;

class SizeRepository
{
    protected $sizeModel;

    public function __construct(Size $size)
    {
        $this->sizeModel = $size;
    }

    public function getSizes(array $filters = [])
    {
        $paginate = $filters['paging'] ?? 10;

        $query = $this->sizeModel->query();

        if (isset($filters['status'])) {
            if ($filters['status'] == -1) {
                $query->whereIn('status', [0, 1]);
            } else {
                $query->where('status', $filters['status']);
            }
        }

        return $query->where('status', '!=', 2)
                     ->orderByDesc('created_at')
                     ->paginate($paginate);
    }

    public function getSizeById($id)
    {
        return $this->sizeModel
                    ->where('s_id', $id)
                    ->where('status', '!=', 2)
                    ->first();
    }

    public function store(array $data)
    {
        try {
            return $this->sizeModel->create($data);
        } catch (\Exception $e) {
            Log::error('Size creation failed: ' . $e->getMessage(), [
                'data' => $data,
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return false;
        }
    }

    public function update(int $id, array $data)
    {
        try {
            $size = $this->sizeModel->findOrFail($id);
            $size->update($data);
            return $size;
        } catch (\Exception $e) {
            Log::error('Size update failed: ' . $e->getMessage(), [
                'size_id' => $id,
                'data'    => $data,
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);
            return false;
        }
    }

    public function statusChange(int $id, int $status)
    {
        try {
            $size = $this->sizeModel->findOrFail($id);
            $size->status = $status;
            $size->save();
            return $size;
        } catch (\Exception $e) {
            Log::error('Size status change failed: ' . $e->getMessage(), [
                'size_id' => $id,
                'status'  => $status,
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);
            return false;
        }
    }
}
