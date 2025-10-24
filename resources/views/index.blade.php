@extends('auditlogger::layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4 text-center">Audit Logs</h2>

    <div class="filter-card mb-4">
        <form method="GET" class="row g-2">
            <div class="col-md-2">
                <input type="text" name="user_id" class="form-control" placeholder="User ID" value="{{ request('user_id') }}">
            </div>
            <div class="col-md-2">
                <select name="event" class="form-select">
                    <option value="">Event Type</option>
                    <option value="created" {{ request('event') == 'created' ? 'selected' : '' }}>Created</option>
                    <option value="updated" {{ request('event') == 'updated' ? 'selected' : '' }}>Updated</option>
                    <option value="deleted" {{ request('event') == 'deleted' ? 'selected' : '' }}>Deleted</option>
                </select>
            </div>
            <div class="col-md-3">
                <input type="text" name="model" class="form-control" placeholder="Model name" value="{{ request('model') }}">
            </div>
            <div class="col-md-2">
                <input type="date" name="from" class="form-control" value="{{ request('from') }}">
            </div>
            <div class="col-md-2">
                <input type="date" name="to" class="form-control" value="{{ request('to') }}">
            </div>
            <div class="col-md-1 d-grid">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Event</th>
                    <th>Model</th>
                    <th>Changes</th>
                    <th>IP</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($auditLogs as $log)
                    <tr>
                        <td>{{ $log->user?->name ?? 'N/A' }}</td>
                        <td><span class="badge bg-info text-dark">{{ ucfirst($log->event) }}</span></td>
                        <td>{{ $log->model }} (ID: {{ $log->model_id }})</td>
                        <td>
                            <div class="small">
                                <strong>Old:</strong> <code>{{ json_encode($log->old_values) }}</code><br>
                                <strong>New:</strong> <code>{{ json_encode($log->new_values) }}</code>
                            </div>
                        </td>
                        <td>{{ $log->ip_address }}</td>
                        <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">No audit logs found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center mt-3">
        {{ $auditLogs->withQueryString()->links() }}
    </div>
</div>
@endsection
