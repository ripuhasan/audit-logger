<?php

namespace MahedulHasan\AuditLogger\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use MahedulHasan\AuditLogger\Models\AuditLog;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::query();

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('event')) {
            $query->where('event', $request->event);
        }

        if ($request->filled('model')) {
            $query->where('model', $request->model);
        }

        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('created_at', [$request->from, $request->to]);
        }

        $auditLogs = $query->latest()->paginate(20);

        return view('auditlogger::index', compact('auditLogs'));
    }
}
