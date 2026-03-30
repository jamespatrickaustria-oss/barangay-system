@extends('layouts.app')

@section('title', 'User Activity Logs')

@section('content')
<style>
    .logs-wrap {
        max-width: 1200px;
        margin: 0 auto;
        padding: 24px;
    }

    .logs-head {
        margin-bottom: 20px;
    }

    .logs-title {
        margin: 0 0 6px;
        font-size: 28px;
        font-weight: 700;
        color: #16324f;
    }

    .logs-sub {
        margin: 0;
        color: #4b5e72;
        font-size: 14px;
    }

    .filters {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 12px;
        background: #ffffff;
        border: 1px solid #d9e5f0;
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 16px;
    }

    .field {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .field label {
        font-size: 12px;
        color: #4b5e72;
        font-weight: 600;
    }

    .field select,
    .field input {
        border: 1px solid #cfd8e3;
        border-radius: 8px;
        padding: 9px 11px;
        font-size: 14px;
    }

    .filter-actions {
        display: flex;
        align-items: end;
        gap: 8px;
    }

    .btn {
        border: none;
        border-radius: 8px;
        padding: 10px 14px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .btn-primary {
        background: #1a6ec7;
        color: #fff;
    }

    .btn-muted {
        background: #f1f5f9;
        color: #334155;
    }

    .table-card {
        background: #fff;
        border: 1px solid #d9e5f0;
        border-radius: 12px;
        overflow: hidden;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        padding: 12px 14px;
        border-bottom: 1px solid #eef2f7;
        text-align: left;
        vertical-align: top;
    }

    th {
        background: #f8fbff;
        font-size: 12px;
        color: #4b5e72;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    td {
        font-size: 14px;
        color: #1e293b;
    }

    tr:last-child td {
        border-bottom: none;
    }

    .badge {
        display: inline-block;
        border-radius: 999px;
        padding: 3px 10px;
        font-size: 12px;
        font-weight: 700;
    }

    .badge-admin {
        background: #dbeafe;
        color: #1e40af;
    }

    .badge-official {
        background: #dcfce7;
        color: #166534;
    }

    .badge-resident {
        background: #fef3c7;
        color: #92400e;
    }

    .badge-guest {
        background: #e2e8f0;
        color: #334155;
    }

    .empty {
        padding: 30px;
        text-align: center;
        color: #64748b;
    }

    @media (max-width: 900px) {
        .filters {
            grid-template-columns: 1fr;
        }

        .table-card {
            overflow-x: auto;
        }

        table {
            min-width: 700px;
        }
    }
</style>

<div class="logs-wrap">
    <div class="logs-head">
        <h1 class="logs-title">User Activity Logs</h1>
        <p class="logs-sub">Audit trail of important actions by Admin, Official, and Resident users.</p>
    </div>

    <form method="GET" action="{{ route('admin.activity-logs') }}" class="filters">
        <div class="field">
            <label for="role">Role</label>
            <select name="role" id="role">
                <option value="" {{ $roleFilter === '' ? 'selected' : '' }}>All Roles</option>
                <option value="admin" {{ $roleFilter === 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="official" {{ $roleFilter === 'official' ? 'selected' : '' }}>Official</option>
                <option value="resident" {{ $roleFilter === 'resident' ? 'selected' : '' }}>Resident</option>
                <option value="guest" {{ $roleFilter === 'guest' ? 'selected' : '' }}>Guest</option>
            </select>
        </div>

        <div class="field">
            <label for="date">Date</label>
            <input type="date" id="date" name="date" value="{{ $dateFilter }}">
        </div>

        <div class="filter-actions">
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="{{ route('admin.activity-logs') }}" class="btn btn-muted">Reset</a>
        </div>
    </form>

    <div class="table-card">
        @if(empty($entries))
            <div class="empty">No log entries found for the selected filters.</div>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Timestamp</th>
                        <th>Role</th>
                        <th>User</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($entries as $entry)
                        @php
                            $roleValue = strtolower($entry['role']);
                            $badgeClass = 'badge-guest';

                            if ($roleValue === 'admin') {
                                $badgeClass = 'badge-admin';
                            } elseif ($roleValue === 'official') {
                                $badgeClass = 'badge-official';
                            } elseif ($roleValue === 'resident') {
                                $badgeClass = 'badge-resident';
                            }
                        @endphp
                        <tr>
                            <td>{{ $entry['timestamp'] }}</td>
                            <td><span class="badge {{ $badgeClass }}">{{ $entry['role'] }}</span></td>
                            <td>{{ $entry['user'] }}</td>
                            <td>{{ $entry['action'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
@endsection
