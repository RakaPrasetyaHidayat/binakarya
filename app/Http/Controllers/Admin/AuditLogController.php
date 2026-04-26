<?php

namespace App\Http\Controllers\Admin;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuditLogController extends Controller
{
    /**
     * Display a listing of audit logs
     */
    public function index(Request $request)
    {
        $query = AuditLog::query()
            ->with('user')
            ->latest('created_at');

        // Filter by action
        if ($request->filled('action')) {
            $query->where('action', $request->input('action'));
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->input('user_id'));
        }

        // Filter by model type
        if ($request->filled('model_type')) {
            $query->where('model_type', $request->input('model_type'));
        }

        // Search in description or IP
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }

        // Date range filter
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->input('from_date'));
        }
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->input('to_date'));
        }

        $logs = $query->paginate(50);
        $actions = AuditLog::distinct('action')->pluck('action');

        return view('admin.audit-logs.index', [
            'logs' => $logs,
            'actions' => $actions,
        ]);
    }

    /**
     * Display a specific audit log with details
     */
    public function show(AuditLog $auditLog)
    {
        return view('admin.audit-logs.show', [
            'log' => $auditLog,
        ]);
    }
}
