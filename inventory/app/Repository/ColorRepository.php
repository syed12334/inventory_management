<?php

namespace App\Repository;

use App\Models\Color;
use Illuminate\Support\Facades\Log;

class ColorRepository
{
    protected $colorModel;

    public function __construct(Color $color)
    {
        $this->colorModel = $color;
    }

    public function getColors(array $filters = [])
    {
        $paginate = $filters['paging'] ?? 10;

        $query = $this->colorModel->query();

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

    public function getColorById($id)
    {
        return $this->colorModel
                    ->where('co_id', $id)
                    ->where('status', '!=', 2)
                    ->first();
    }

    public function store(array $data)
    {
        try {
            return $this->colorModel->create($data);
        } catch (\Exception $e) {
            Log::error('Color creation failed: ' . $e->getMessage(), [
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
            $color = $this->colorModel->findOrFail($id);
            $color->update($data);
            return $color;
        } catch (\Exception $e) {
            Log::error('Color update failed: ' . $e->getMessage(), [
                'color_id' => $id,
                'data'     => $data,
                'file'     => $e->getFile(),
                'line'     => $e->getLine(),
            ]);
            return false;
        }
    }

    public function statusChange(int $id, int $status)
    {
        try {
            $color = $this->colorModel->findOrFail($id);
            $color->status = $status;
            $color->save();
            return $color;
        } catch (\Exception $e) {
            Log::error('Color status change failed: ' . $e->getMessage(), [
                'color_id' => $id,
                'status'   => $status,
                'file'     => $e->getFile(),
                'line'     => $e->getLine(),
            ]);
            return false;
        }
    }
}
