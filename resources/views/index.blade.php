@extends('auditlogger::layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-3">Audit Logs</h2>

    <form method="GET" class="row mb-4">
        <div class="col-md-2">
            <input type="text" name="user_id" class="form-control" placeholder="User ID" value="{{ request('user_id') }}">
        </div>
        <div class="col-md-2">
            <select name="event" class="form-control">
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
        <div class="col-md-1">
            <button type="submit" class="btn btn-primary w-100">Filter</button>
        </div>
    </form>

    <table class="table table-bordered">
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
            @foreach ($auditLogs as $log)
                <tr>
                    <td>{{ $log->user?->name ?? 'N/A' }}</td>
                    <td>{{ ucfirst($log->event) }}</td>
                    <td>{{ $log->model }} (ID: {{ $log->model_id }})</td>
                    <td>
                        <strong>Old:</strong> {{ json_encode($log->old_values) }} <br>
                        <strong>New:</strong> {{ json_encode($log->new_values) }}
                    </td>
                    <td>{{ $log->ip_address }}</td>
                    <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $auditLogs->withQueryString()->links() }}
</div>
@endsection
