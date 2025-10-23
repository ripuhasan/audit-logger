<?php

namespace MahedulHasan\AuditLogger\Observers;

use MahedulHasan\AuditLogger\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditObserver
{
    public function created($model)
    {
        if ($this->shouldSkip($model)) return;

        $this->logChange('created', [], $model->getAttributes(), $model);
    }

    public function updated($model)
    {
        if ($this->shouldSkip($model)) return;

        $changes = $model->getDirty();
        if (!empty($changes)) {
            $old = [];
            $new = [];
            foreach ($changes as $key => $value) {
                $old[$key] = $model->getOriginal($key);
                $new[$key] = $value;
            }
            $this->logChange('updated', $old, $new, $model);
        }
    }

    public function deleted($model)
    {
        if ($this->shouldSkip($model)) return;
        $this->logChange('deleted', $model->getOriginal(), [], $model);
    }

    protected function logChange($event, $old, $new, $model)
    {
        AuditLog::create([
            'user_id' => Auth::id(),
            'event' => $event,
            'model' => get_class($model),
            'model_id' => $model->getKey(),
            'old_values' => $old,
            'new_values' => $new,
            'ip_address' => Request::ip(),
        ]);
    }

    protected function shouldSkip($model): bool
    {
        return $model instanceof AuditLog;
    }
}
