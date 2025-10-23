<?php

use MahedulHasan\AuditLogger\Models\AuditLog;

Route::get('audit-logs', function () {
    $logs = AuditLog::latest()->paginate(20);
    return view('auditlogger::index', compact('logs'));
});
