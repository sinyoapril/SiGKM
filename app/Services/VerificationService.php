<?php

namespace App\Services;

use App\Helpers\WorkflowHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class VerificationService
{
    public function ajukan(Model $model): Model
    {
        return $this->changeStatus($model, 'verifikasi', 'diajukan');
    }

    public function verifikasi(Model $model, ?string $catatan = null): Model
    {
        return DB::transaction(function () use ($model, $catatan) {
            WorkflowHelper::assertTransition('verifikasi', $model->status, 'diverifikasi');

            $model->forceFill([
                'status' => 'diverifikasi',
                'verified_by' => auth()->id(),
                'verified_at' => now(),
                'catatan_verifikasi' => $catatan,
            ])->save();

            return $model->refresh();
        });
    }

    public function tolak(Model $model, string $catatan): Model
    {
        return DB::transaction(function () use ($model, $catatan) {
            WorkflowHelper::assertTransition('verifikasi', $model->status, 'ditolak');

            $model->forceFill([
                'status' => 'ditolak',
                'verified_by' => auth()->id(),
                'verified_at' => now(),
                'catatan_verifikasi' => $catatan,
            ])->save();

            return $model->refresh();
        });
    }

    private function changeStatus(Model $model, string $workflow, string $status): Model
    {
        WorkflowHelper::assertTransition($workflow, $model->status, $status);

        $model->forceFill(['status' => $status])->save();

        return $model->refresh();
    }
}
