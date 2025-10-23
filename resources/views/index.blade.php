<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audit Logs</title>
    <!-- ✅ Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2 class="mb-4 text-center">🕵️ Audit Logs</h2>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-striped table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Event</th>
                        <th>Model</th>
                        <th>Model ID</th>
                        <th>Changed Fields</th>
                        <th>IP</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td>{{ $log->id }}</td>
                            <td>{{ optional($log->user)->name ?? 'System' }}</td>
                            <td>
                                @if($log->event === 'created')
                                    <span class="badge bg-success">Created</span>
                                @elseif($log->event === 'updated')
                                    <span class="badge bg-warning text-dark">Updated</span>
                                @elseif($log->event === 'deleted')
                                    <span class="badge bg-danger">Deleted</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($log->event) }}</span>
                                @endif
                            </td>
                            <td><code>{{ class_basename($log->model) }}</code></td>
                            <td>{{ $log->model_id }}</td>
                            <td>
                                @php
                                    $old = $log->old_values ?? [];
                                    $new = $log->new_values ?? [];
                                @endphp

                                @if($log->event === 'updated' && !empty($new))
                                    <ul class="mb-0">
                                        @foreach($new as $field => $value)
                                            <li>
                                                <strong>{{ $field }}</strong>:
                                                <span class="text-danger">{{ $old[$field] ?? '—' }}</span>
                                                →
                                                <span class="text-success">{{ $value }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @elseif($log->event === 'created')
                                    <span class="text-success">New record created</span>
                                @elseif($log->event === 'deleted')
                                    <span class="text-danger">Record deleted</span>
                                @endif
                            </td>
                            <td>{{ $log->ip_address }}</td>
                            <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-3">No audit logs found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer text-end">
            {{ $logs->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

</body>
</html>
