<?php

use MahedulHasan\AuditLogger\Models\AuditLog;
use MahedulHasan\AuditLogger\Http\Controllers\AuditLogController;

Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('auditlogs.index');

