@extends('layouts.app')

@section('title', 'Barangay Officials')

@section('content')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<style>
    :root {
        --blue: #1a6fcc;
        --green: #3a8a3f;
        --blue-light: #daf0fa;
        --surface: #f0f9ff;
        --text: #0d1b2a;
        --text-muted: #5a7a9a;
        --border: #c8e4f8;
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

    .add-btn {
        background: var(--green);
        color: white;
        border: none;
        border-radius: 10px;
        padding: 10px 22px;
        font-weight: 600;
        font-size: 14px;
        text-decoration: none;
        cursor: pointer;
        transition: opacity 0.2s;
        display: inline-block;
    }

    .add-btn:hover {
        opacity: 0.9;
    }

    .table-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(26, 111, 204, 0.08);
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

    .official-cell {
        display: flex;
        gap: 12px;
        align-items: center;
    }

    .avatar {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--blue), var(--green));
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 16px;
        flex-shrink: 0;
    }

    .official-info {
        display: flex;
        flex-direction: column;
    }

    .official-name {
        font-size: 14px;
        font-weight: 600;
        color: var(--text);
        margin: 0;
    }

    .official-email {
        font-size: 12px;
        color: var(--text-muted);
        margin: 2px 0 0 0;
    }

    .delete-btn {
        border: 1.5px solid #dc3545;
        color: #dc3545;
        background: transparent;
        border-radius: 8px;
        padding: 7px 16px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }

    .delete-btn:hover {
        background: #fce8e6;
    }

    .empty-message {
        text-align: center;
        padding: 60px 20px;
        color: var(--text-muted);
    }

    .empty-message p {
        margin: 0;
        font-size: 16px;
    }

    /* Modal Styles */
    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 200;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-card {
        background: white;
        border-radius: 20px;
        padding: 40px;
        max-width: 400px;
        width: 90%;
        text-align: center;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
    }

    .modal-emoji {
        font-size: 48px;
        margin-bottom: 16px;
        display: block;
    }

    .modal-title {
        font-size: 22px;
        font-weight: 700;
        color: #dc3545;
        margin: 0 0 8px 0;
    }

    .modal-text {
        font-size: 14px;
        color: var(--text-muted);
        margin: 0 0 28px 0;
        line-height: 1.6;
    }

    .modal-buttons {
        display: flex;
        gap: 12px;
        justify-content: center;
    }

    .modal-btn {
        border: 2px solid;
        border-radius: 10px;
        padding: 10px 24px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.2s;
        font-family: inherit;
    }

    .modal-cancel {
        border-color: var(--border);
        color: var(--text-muted);
        background: white;
    }

    .modal-cancel:hover {
        background: #f8f9fa;
    }

    .modal-confirm {
        background: #dc3545;
        color: white;
        border-color: #dc3545;
    }

    .modal-confirm:hover {
        opacity: 0.9;
    }

    [x-cloak] {
        display: none;
    }
</style>

<div class="page-header">
    <h1 class="page-title">Barangay Officials</h1>
    <a href="{{ route('admin.officials.create') }}" class="add-btn">+ Add Official</a>
</div>

<div x-data="{ showModal: false, selectedName: '', selectedId: null, deleteForm: null }" x-cloak>
    @if($officials->count() > 0)
        <div class="table-card">
            <table>
                <thead>
                    <tr>
                        <th>Official</th>
                        <th>Phone</th>
                        <th>Date Added</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($officials as $official)
                        <tr>
                            <td>
                                <div class="official-cell">
                                    <div class="avatar">{{ strtoupper(substr($official->name, 0, 1)) }}</div>
                                    <div class="official-info">
                                        <p class="official-name">{{ $official->name }}</p>
                                        <p class="official-email">{{ $official->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $official->phone ?? 'N/A' }}</td>
                            <td>{{ $official->created_at->format('M d, Y') }}</td>
                            <td>
                                <button 
                                    @click="selectedName='{{ $official->getFullName() }}'; selectedId={{ $official->id }}; showModal=true"
                                    class="delete-btn"
                                >
                                    Remove
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="empty-message">
            <p>No officials registered yet</p>
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    <template x-if="showModal">
        <div class="modal-overlay" @click.self="showModal=false">
            <div class="modal-card">
                <span class="modal-emoji">⚠️</span>
                <h2 class="modal-title">Remove Official</h2>
                <p class="modal-text">
                    Are you sure you want to remove <strong x-text="selectedName"></strong>? This cannot be undone.
                </p>
                <div class="modal-buttons">
                    <button @click="showModal=false" class="modal-btn modal-cancel">No</button>
                    <form 
                        :action="`{{ route('admin.officials.destroy', '__ID__') }}`.replace('__ID__', selectedId)"
                        method="POST"
                        style="display: inline;"
                    >
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="modal-btn modal-confirm">Yes</button>
                    </form>
                </div>
            </div>
        </div>
    </template>
</div>

@endsection
