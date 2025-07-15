<?php

namespace App\Repository;

use App\Models\Unit;
use Illuminate\Support\Facades\Log;

class UnitRepository
{
    protected $unitModel;

    public function __construct(Unit $unit)
    {
        $this->unitModel = $unit;
    }

    public function getUnits(array $filters = [])
    {
        $paginate = $filters['paging'] ?? 10;

        $query = $this->unitModel->query();

        if (isset($filters['status'])) {
            if ($filters['status'] == -1) {
                $query->whereIn('status', [0, 1]);
            } else {
                $query->where('status', $filters['status']);
            }
        }

        return $query->orderByDesc('created_at')
                     ->paginate($paginate);
    }

    public function getUnitById($id)
    {
        return $this->unitModel
                    ->where('id', $id)
                    ->first();
    }

    public function store(array $data)
    {
        try {
            return $this->unitModel->create($data);
        } catch (\Exception $e) {
            Log::error('Unit creation failed: ' . $e->getMessage(), [
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
            $unit = $this->unitModel->findOrFail($id);
            $unit->update($data);
            return $unit;
        } catch (\Exception $e) {
            Log::error('Unit update failed: ' . $e->getMessage(), [
                'unit_id' => $id,
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
            $unit = $this->unitModel->findOrFail($id);
            $unit->status = $status;
            $unit->save();
            return $unit;
        } catch (\Exception $e) {
            Log::error('Unit status change failed: ' . $e->getMessage(), [
                'unit_id' => $id,
                'status'  => $status,
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);

            return false;
        }
    }
}
