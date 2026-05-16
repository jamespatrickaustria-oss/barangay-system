@extends('layouts.app')

@section('title', 'Carousel Management')

@section('content')
@php
    $routePrefix = $routePrefix ?? (auth()->check() ? auth()->user()->role : 'admin');
    $dashboardRoute = $dashboardRoute ?? ($routePrefix . '.dashboard');
@endphp

<style>
    :root {
        --bg: #f4f7fb;
        --panel: rgba(255, 255, 255, 0.95);
        --panel-border: rgba(148, 163, 184, 0.18);
        --text: #0f172a;
        --muted: #64748b;
        --accent: #1d4ed8;
        --accent-soft: rgba(29, 78, 216, 0.08);
        --success: #067647;
        --danger: #b42318;
        --shadow: 0 22px 60px rgba(15, 23, 42, 0.08);
    }

    .carousel-shell {
        padding: 18px 0 44px;
    }

    .hero {
        position: relative;
        overflow: hidden;
        border-radius: 28px;
        padding: 28px;
        border: 1px solid var(--panel-border);
        background:
            radial-gradient(circle at top left, rgba(29, 78, 216, 0.16), transparent 34%),
            radial-gradient(circle at bottom right, rgba(245, 158, 11, 0.16), transparent 30%),
            linear-gradient(180deg, rgba(255,255,255,0.98), rgba(248,250,252,0.96));
        box-shadow: var(--shadow);
        margin-bottom: 20px;
    }

    .hero-top {
        display: flex;
        flex-wrap: wrap;
        gap: 18px;
        justify-content: space-between;
        align-items: flex-start;
    }

    .hero h1 {
        margin: 0 0 10px;
        font-size: clamp(30px, 4vw, 44px);
        line-height: 1.04;
        letter-spacing: -0.05em;
        color: var(--text);
    }

    .hero p {
        margin: 0;
        color: var(--muted);
        font-size: 15px;
        line-height: 1.7;
        max-width: 820px;
    }

    .pills {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 16px;
    }

    .pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 14px;
        border-radius: 999px;
        border: 1px solid var(--panel-border);
        background: white;
        color: var(--text);
        font-size: 13px;
        font-weight: 700;
    }

    .pill.accent {
        color: var(--accent);
        background: var(--accent-soft);
        border-color: rgba(29, 78, 216, 0.16);
    }

    .hero-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .button {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 20px;
        border-radius: 10px;
        border: none;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .button.primary {
        background: var(--accent);
        color: white;
    }

    .button.primary:hover {
        background: #1a3fa8;
    }

    .button.secondary {
        background: white;
        color: var(--text);
        border: 1px solid var(--panel-border);
    }

    .button.secondary:hover {
        border-color: var(--accent);
    }

    .card {
        background: white;
        border: 1px solid var(--panel-border);
        border-radius: 18px;
        padding: 24px;
        margin-bottom: 20px;
        box-shadow: var(--shadow);
    }

    .card h2 {
        margin: 0 0 8px;
        font-size: 22px;
        color: var(--text);
    }

    .card > .subtle {
        color: var(--muted);
        font-size: 14px;
        margin: 0 0 16px;
    }

    .settings-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 16px;
    }

    .field {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .field label {
        font-size: 13px;
        font-weight: 700;
        color: var(--text);
    }

    .field input,
    .field select,
    .field textarea {
        padding: 10px 12px;
        border: 1px solid var(--panel-border);
        border-radius: 8px;
        font-size: 14px;
        font-family: inherit;
    }

    .field input:focus,
    .field select:focus,
    .field textarea:focus {
        outline: none;
        border-color: var(--accent);
        box-shadow: 0 0 0 3px var(--accent-soft);
    }

    .checks {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .check {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        cursor: pointer;
    }

    .check input[type="checkbox"] {
        width: 18px;
        height: 18px;
        cursor: pointer;
    }

    .hint {
        font-size: 12px;
        color: var(--muted);
    }

    .hint.error {
        color: var(--danger);
    }

    .message {
        padding: 14px 16px;
        border-radius: 10px;
        font-size: 14px;
        margin-bottom: 16px;
    }

    .message.success {
        background: rgba(6, 118, 71, 0.08);
        color: var(--success);
        border: 1px solid rgba(6, 118, 71, 0.2);
    }

    .message.error {
        background: rgba(180, 35, 24, 0.08);
        color: var(--danger);
        border: 1px solid rgba(180, 35, 24, 0.2);
    }

    .slides-table {
        width: 100%;
        border-collapse: collapse;
    }

    .slides-table th {
        background: var(--bg);
        padding: 12px 16px;
        text-align: left;
        font-size: 12px;
        font-weight: 700;
        color: var(--text);
        border-bottom: 1px solid var(--panel-border);
    }

    .slides-table td {
        padding: 12px 16px;
        border-bottom: 1px solid var(--panel-border);
    }

    .slides-table tr:last-child td {
        border-bottom: none;
    }

    .slide-preview {
        width: 60px;
        height: 60px;
        border-radius: 8px;
        object-fit: cover;
        background: var(--bg);
    }

    .slide-info {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .slide-title {
        font-weight: 600;
        color: var(--text);
    }

    .slide-desc {
        font-size: 13px;
        color: var(--muted);
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-badge.enabled {
        background: rgba(6, 118, 71, 0.12);
        color: var(--success);
    }

    .status-badge.disabled {
        background: rgba(148, 163, 184, 0.12);
        color: var(--muted);
    }

    .actions {
        display: flex;
        gap: 8px;
    }

    .action-btn {
        padding: 8px 12px;
        border: none;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .action-btn.edit {
        background: var(--accent-soft);
        color: var(--accent);
    }

    .action-btn.edit:hover {
        background: rgba(29, 78, 216, 0.16);
    }

    .action-btn.delete {
        background: rgba(180, 35, 24, 0.12);
        color: var(--danger);
    }

    .action-btn.delete:hover {
        background: rgba(180, 35, 24, 0.2);
    }

    .pagination {
        display: flex;
        justify-content: center;
        gap: 8px;
        margin-top: 20px;
    }

    .pagination a,
    .pagination span {
        padding: 8px 12px;
        border-radius: 6px;
        border: 1px solid var(--panel-border);
        font-size: 14px;
        text-decoration: none;
        color: var(--text);
    }

    .pagination a:hover {
        background: var(--bg);
    }

    .pagination .active {
        background: var(--accent);
        color: white;
        border-color: var(--accent);
    }

    .layout {
        display: grid;
        grid-template-columns: 1fr;
        gap: 20px;
    }
</style>

<div class="carousel-shell">
    <div class="container">
        <div class="hero">
            <div class="hero-top">
                <div>
                    <h1>Carousel Management</h1>
                    <p>Manage your homepage carousel slides. Create new slides, edit existing ones, or delete slides you no longer need.</p>
                    <div class="pills">
                        <span class="pill accent">{{ ucfirst($routePrefix) }} panel</span>
                        <span class="pill">7 slide limit</span>
                        <span class="pill">Individual CRUD</span>
                    </div>
                </div>
                <div class="hero-actions">
                    <a href="{{ route($dashboardRoute) }}" class="button secondary">Back to dashboard</a>
                    <a href="{{ route($routePrefix . '.carousel-settings.create') }}" class="button primary">+ New Slide</a>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="message success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="message error">{{ session('error') }}</div>
        @endif

        <div class="layout">
            <!-- Carousel Settings -->
            <div class="card">
                <h2>Carousel Settings</h2>
                <p class="subtle">Configure autoplay behavior and other carousel options.</p>

                <form action="{{ route($routePrefix . '.carousel-settings.update-settings') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="settings-grid">
                        <div class="field">
                            <label for="autoplay_speed">Autoplay Speed (ms)</label>
                            <input type="number" id="autoplay_speed" name="autoplay_speed" min="1000" max="30000" step="100" value="{{ old('autoplay_speed', $carouselSettings?->autoplay_speed ?? 4000) }}" required>
                            <div class="hint">Milliseconds between slide changes.</div>
                            @error('autoplay_speed')<div class="hint error">{{ $message }}</div>@enderror
                        </div>

                        <div class="checks">
                            <label class="check">
                                <input type="checkbox" name="autoplay_enabled" value="1" {{ old('autoplay_enabled', $carouselSettings?->autoplay_enabled ?? true) ? 'checked' : '' }}>
                                Enable autoplay
                            </label>
                            <label class="check">
                                <input type="checkbox" name="pause_on_hover" value="1" {{ old('pause_on_hover', $carouselSettings?->pause_on_hover ?? true) ? 'checked' : '' }}>
                                Pause on hover
                            </label>
                            <label class="check">
                                <input type="checkbox" name="loop" value="1" {{ old('loop', $carouselSettings?->loop ?? true) ? 'checked' : '' }}>
                                Loop slides
                            </label>
                        </div>
                    </div>

                    <div style="margin-top: 16px;">
                        <button type="submit" class="button primary">Update Settings</button>
                    </div>
                </form>
            </div>

            <!-- Carousel Slides -->
            <div class="card">
                <h2>Carousel Slides</h2>
                <p class="subtle">You have {{ $slides->total() }} slide(s). Maximum 7 slides allowed.</p>

                @if ($slides->count() > 0)
                    <table class="slides-table">
                        <thead>
                            <tr>
                                <th>Preview</th>
                                <th>Order</th>
                                <th>Title</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($slides as $slide)
                                <tr>
                                    <td>
                                        @if ($slide->image_path)
                                            <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($slide->image_path) }}" alt="{{ $slide->title ?? 'Slide' }}" class="slide-preview">
                                        @else
                                            <div class="slide-preview" style="background: var(--bg); display: flex; align-items: center; justify-content: center; color: var(--muted);">No image</div>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>Slot {{ $slide->slot }}</strong>
                                    </td>
                                    <td>
                                        <div class="slide-info">
                                            <span class="slide-title">{{ $slide->title ?? '(no title)' }}</span>
                                            @if ($slide->link_url)
                                                <span class="slide-desc">Link: {{ Str::limit($slide->link_url, 30) }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="status-badge {{ $slide->enabled ? 'enabled' : 'disabled' }}">
                                            {{ $slide->enabled ? 'Enabled' : 'Disabled' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="actions">
                                            <a href="{{ route($routePrefix . '.carousel-settings.edit', $slide) }}" class="action-btn edit">Edit</a>
                                            <form method="POST" action="{{ route($routePrefix . '.carousel-settings.destroy', $slide) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this slide?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="action-btn delete">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if ($slides->hasPages())
                        <div class="pagination">
                            {{ $slides->links() }}
                        </div>
                    @endif
                @else
                    <div style="padding: 40px 20px; text-align: center; color: var(--muted);">
                        <p style="margin: 0 0 16px; font-size: 15px;">No carousel slides yet. Create your first slide to get started!</p>
                        <a href="{{ route($routePrefix . '.carousel-settings.create') }}" class="button primary">Create First Slide</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
