@extends('layouts.app')

@section('title', 'Resident Management')

@section('content')
@php
    $routePrefix = request()->segment(1) === 'admin' ? 'admin' : 'official';
@endphp

<style>
    :root {
        --blue: #1a6fcc;
        --green: #3a8a3f;
        --green-dark: #2d6a31;
        --blue-light: #daf0fa;
        --green-light: #e8f5e9;
        --surface: #f0f9ff;
        --text: #0d1b2a;
        --text-muted: #5a7a9a;
        --border: #c8e4f8;
        --danger: #dc3545;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }

    .page-title {
        font-size: 22px;
        font-weight: 700;
        color: var(--text);
        margin: 0;
    }

    .add-resident-btn {
        background: var(--green);
        color: white;
        border: none;
        border-radius: 10px;
        padding: 10px 22px;
        font-weight: 600;
        text-decoration: none;
        font-size: 14px;
        cursor: pointer;
        transition: background 0.2s;
        display: inline-block;
    }

    .add-resident-btn:hover {
        background: var(--green-dark);
    }

    .filter-card {
        background: white;
        border-radius: 14px;
        padding: 16px 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(26, 111, 204, 0.07);
    }

    .filter-form {
        display: flex;
        gap: 12px;
        align-items: center;
    }

    .search-wrapper {
        flex: 1;
        position: relative;
    }

    .search-input {
        width: 100%;
        border: 2px solid var(--border);
        border-radius: 10px;
        padding: 10px 16px;
        padding-left: 38px;
        font-size: 14px;
        font-family: inherit;
        box-sizing: border-box;
        transition: border-color 0.2s;
    }

    .search-input:focus {
        outline: none;
        border-color: var(--blue);
    }

    .search-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 16px;
        pointer-events: none;
    }

    .status-select {
        border: 2px solid var(--border);
        border-radius: 10px;
        padding: 10px 16px;
        font-size: 14px;
        font-family: inherit;
        background: white;
        cursor: pointer;
        transition: border-color 0.2s;
        appearance: none;
        background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%235a7a9a' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 8px center;
        background-size: 20px;
        padding-right: 36px;
    }

    .status-select:focus {
        outline: none;
        border-color: var(--blue);
    }

    .search-btn {
        background: var(--blue);
        color: white;
        border: none;
        border-radius: 10px;
        padding: 10px 20px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: opacity 0.2s;
    }

    .search-btn:hover {
        opacity: 0.9;
    }

    .table-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(26, 111, 204, 0.08);
        margin-bottom: 20px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    thead {
        background: #f0f9ff;
    }

    th {
        padding: 14px 20px;
        text-align: left;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--text-muted);
        font-weight: 700;
        border-bottom: 1px solid var(--border);
    }

    td {
        padding: 16px 20px;
        border-bottom: 1px solid #f0f0f0;
    }

    tbody tr:hover {
        background: #f8fbff;
    }

    tbody tr:last-child td {
        border-bottom: none;
    }

    .resident-cell {
        display: flex;
        gap: 12px;
        align-items: center;
    }

    .avatar {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 12px;
        flex-shrink: 0;
    }

    .avatar.pending {
        background: var(--blue-light);
        color: var(--blue);
    }

    .avatar.approved {
        background: var(--green-light);
        color: var(--green);
    }

    .resident-info {
        display: flex;
        flex-direction: column;
    }

    .resident-name {
        font-size: 14px;
        font-weight: 600;
        color: var(--text);
        margin: 0;
    }

    .resident-email {
        font-size: 12px;
        color: var(--text-muted);
        margin: 2px 0 0 0;
    }

    .status-badge {
        display: inline-block;
        border-radius: 20px;
        padding: 4px 14px;
        font-size: 12px;
        font-weight: 700;
    }

    .status-pending {
        background: #fff8e1;
        color: #92400e;
    }

    .status-approved {
        background: var(--green-light);
        color: var(--green-dark);
    }

    .status-rejected {
        background: #fce8e6;
        color: #b91c1c;
    }

    .actions-cell {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
    }

    .action-btn {
        border: 1px solid;
        border-radius: 8px;
        padding: 5px 12px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        background: white;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .approve-btn {
        border-color: var(--green);
        color: var(--green);
    }

    .approve-btn:hover {
        background: var(--green-light);
    }

    .reject-btn {
        border-color: #dc3545;
        color: #dc3545;
    }

    .reject-btn:hover {
        background: #fce8e6;
    }

    .edit-btn {
        border-color: var(--blue);
        color: var(--blue);
    }

    .edit-btn:hover {
        background: var(--blue-light);
    }

    .id-btn {
        border-color: #0f766e;
        color: #0f766e;
    }

    .id-btn:hover {
        background: #e6fffa;
    }

    .delete-btn {
        border-color: #f44336;
        color: #f44336;
    }

    .delete-btn:hover {
        background: #ffebee;
    }

    .empty-message {
        text-align: center;
        padding: 60px 20px;
        color: var(--text-muted);
    }

    .empty-message p {
        font-size: 16px;
        margin: 0;
    }

    .pagination {
        display: flex;
        justify-content: center;
        gap: 8px;
        margin-top: 24px;
    }

    .pagination a,
    .pagination span {
        padding: 8px 12px;
        border: 1px solid var(--border);
        border-radius: 6px;
        text-decoration: none;
        font-size: 13px;
        color: var(--blue);
        cursor: pointer;
        transition: all 0.2s;
    }

    .pagination a:hover {
        background: var(--blue);
        color: white;
        border-color: var(--blue);
    }

    .pagination .active span {
        background: var(--blue);
        color: white;
        border-color: var(--blue);
    }

    .pagination span:not(.page-item) {
        color: var(--text-muted);
    }
</style>

<div class="page-header">
    <h1 class="page-title">Resident Management</h1>
    <a href="{{ route($routePrefix . '.residents.create') }}" class="add-resident-btn">+ Add Resident</a>
</div>

<div class="filter-card">
    <form method="GET" action="{{ route($routePrefix . '.residents.index') }}" class="filter-form">
        <div class="search-wrapper">
            <span class="search-icon">🔍</span>
            <input 
                type="text" 
                name="search" 
                class="search-input" 
                placeholder="Search by name or email..."
                value="{{ request('search') }}"
            >
        </div>

        <select name="status" class="status-select">
            <option value="">All Residents</option>
            <option value="pending" @selected(request('status') === 'pending')>Pending</option>
            <option value="approved" @selected(request('status') === 'approved')>Approved</option>
            <option value="rejected" @selected(request('status') === 'rejected')>Rejected</option>
        </select>

        <button type="submit" class="search-btn">Search</button>
    </form>
</div>

@if($residents->count() > 0)
    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>Resident</th>
                    <th>Phone</th>
                    <th>Status</th>
                    <th>Registered</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($residents as $resident)
                    <tr>
                        <td>
                            <div class="resident-cell">
                                <div class="avatar {{ $resident->status ?? 'pending' }}">
                                    {{ strtoupper(substr($resident->getFullName(), 0, 1)) }}
                                </div>
                                <div class="resident-info">
                                    <p class="resident-name">{{ $resident->getFullName() }}</p>
                                    <p class="resident-email">{{ $resident->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td>{{ $resident->phone ?? 'N/A' }}</td>
                        <td>
                            <span class="status-badge status-{{ $resident->status ?? 'pending' }}">
                                @if($resident->status === 'pending')
                                    Pending
                                @elseif($resident->status === 'approved')
                                    Approved
                                @elseif($resident->status === 'rejected')
                                    Rejected
                                @else
                                    Pending
                                @endif
                            </span>
                        </td>
                        <td>{{ $resident->created_at->format('M d, Y') }}</td>
                        <td>
                            <div class="actions-cell">
                                @if($resident->status === 'pending')
                                    <form method="POST" action="{{ route($routePrefix . '.residents.approve', $resident->id) }}" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="action-btn approve-btn">✓ Approve</button>
                                    </form>
                                    <form method="POST" action="{{ route($routePrefix . '.residents.reject', $resident->id) }}" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="action-btn reject-btn" onclick="return confirm('Are you sure?')">✗ Reject</button>
                                    </form>
                                @endif
                                <a href="{{ route($routePrefix . '.residents.view-id', $resident->id) }}" class="action-btn id-btn">🪪 View ID</a>
                                <a href="{{ route($routePrefix . '.residents.edit', $resident->id) }}" class="action-btn edit-btn">✏️ Edit</a>
                                <form method="POST" action="{{ route($routePrefix . '.residents.destroy', $resident->id) }}" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn delete-btn" onclick="return confirm('Are you sure you want to delete this resident? This action cannot be undone.')">🗑️ Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="pagination">
        {{ $residents->withQueryString()->links() }}
    </div>
@else
    <div class="empty-message">
        <p>📭 No residents found</p>
    </div>
@endif

@endsection
