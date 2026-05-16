@extends('layouts.app')

@section('title', 'Carousel Settings')

@section('content')
@php
    $role = auth()->user()->role ?? 'guest';
    $routePrefix = $routePrefix ?? request()->segment(1);
    $dashboardRoute = $dashboardRoute ?? ($routePrefix . '.dashboard');
    $indexRoute = $routePrefix . '.carousel-settings.index';
    $settingsUpdateRoute = $routePrefix . '.carousel-settings.settings.update';
    $slidesStoreRoute = $routePrefix . '.carousel-settings.slides.store';
    $slidesUpdateRoute = $routePrefix . '.carousel-settings.slides.update';
    $slidesDestroyRoute = $routePrefix . '.carousel-settings.slides.destroy';
@endphp

<style>
    :root {
        --bg: #f4f7fb;
        --panel: rgba(255, 255, 255, 0.92);
        --panel-border: rgba(148, 163, 184, 0.18);
        --text: #102033;
        --muted: #64748b;
        --accent: #1d4ed8;
        --accent-soft: rgba(29, 78, 216, 0.1);
        --danger: #b42318;
        --success: #067647;
        --shadow: 0 20px 50px rgba(15, 23, 42, 0.08);
    }

    .carousel-admin-shell {
        padding: 18px 0 48px;
    }

    .carousel-hero {
        position: relative;
        overflow: hidden;
        border: 1px solid var(--panel-border);
        border-radius: 28px;
        background:
            radial-gradient(circle at top left, rgba(29, 78, 216, 0.16), transparent 34%),
            radial-gradient(circle at bottom right, rgba(245, 158, 11, 0.18), transparent 28%),
            linear-gradient(180deg, rgba(255,255,255,0.98), rgba(248,250,252,0.96));
        box-shadow: var(--shadow);
        padding: 28px;
        margin-bottom: 24px;
    }

    .hero-top {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        justify-content: space-between;
        align-items: flex-start;
    }

    .hero-copy h1 {
        margin: 0 0 8px;
        font-size: clamp(28px, 4vw, 42px);
        line-height: 1.05;
        letter-spacing: -0.04em;
        color: var(--text);
    }

    .hero-copy p {
        margin: 0;
        max-width: 760px;
        color: var(--muted);
        font-size: 15px;
        line-height: 1.7;
    }

    .hero-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 16px;
    }

    .meta-pill,
    .role-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 14px;
        border-radius: 999px;
        background: white;
        border: 1px solid var(--panel-border);
        color: var(--text);
        font-size: 13px;
        font-weight: 600;
    }

    .role-pill {
        background: var(--accent-soft);
        color: var(--accent);
        border-color: rgba(29, 78, 216, 0.18);
    }

    .hero-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        border: 0;
        border-radius: 14px;
        padding: 12px 18px;
        font-weight: 700;
        font-size: 14px;
        text-decoration: none;
        transition: transform 0.18s ease, box-shadow 0.18s ease, background 0.18s ease;
        cursor: pointer;
    }

    .button:hover {
        transform: translateY(-1px);
    }

    .button.primary {
        color: white;
        background: linear-gradient(135deg, #1d4ed8, #2563eb);
        box-shadow: 0 16px 30px rgba(37, 99, 235, 0.22);
    }

    .button.secondary {
        color: var(--text);
        background: white;
        border: 1px solid var(--panel-border);
    }

    .button.danger {
        color: white;
        background: linear-gradient(135deg, #b42318, #d92d20);
    }

    .grid {
        display: grid;
        gap: 18px;
        grid-template-columns: repeat(12, minmax(0, 1fr));
        margin-bottom: 18px;
    }

    .card {
        background: var(--panel);
        border: 1px solid var(--panel-border);
        border-radius: 22px;
        box-shadow: var(--shadow);
        padding: 22px;
    }

    .card h2 {
        margin: 0 0 8px;
        font-size: 18px;
        letter-spacing: -0.02em;
        color: var(--text);
    }

    .card .subtle {
        margin: 0 0 18px;
        color: var(--muted);
        font-size: 13px;
        line-height: 1.6;
    }

    .span-5 { grid-column: span 5; }
    .span-7 { grid-column: span 7; }
    .span-12 { grid-column: span 12; }

    .form-grid {
        display: grid;
        gap: 14px;
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .field {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .field.full { grid-column: 1 / -1; }

    .field label {
        font-size: 13px;
        font-weight: 700;
        color: var(--text);
    }

    .field input[type="text"],
    .field input[type="url"],
    .field input[type="number"],
    .field textarea {
        width: 100%;
        border-radius: 14px;
        border: 1px solid rgba(148, 163, 184, 0.35);
        background: white;
        padding: 12px 14px;
        font: inherit;
        color: var(--text);
        outline: none;
        transition: border-color 0.18s ease, box-shadow 0.18s ease;
    }

    .field input:focus,
    .field textarea:focus {
        border-color: rgba(29, 78, 216, 0.55);
        box-shadow: 0 0 0 4px rgba(29, 78, 216, 0.08);
    }

    .field textarea { min-height: 110px; resize: vertical; }

    .check-row {
        display: flex;
        gap: 16px;
        flex-wrap: wrap;
    }

    .check-item {
        display: inline-flex;
        gap: 8px;
        align-items: center;
        color: var(--text);
        font-size: 13px;
        font-weight: 600;
    }

    .hint {
        font-size: 12px;
        color: var(--muted);
        margin-top: -2px;
    }

    .alert {
        border-radius: 18px;
        padding: 14px 16px;
        margin-bottom: 18px;
        font-weight: 600;
    }

    .alert.success {
        color: var(--success);
        background: rgba(6, 118, 71, 0.08);
        border: 1px solid rgba(6, 118, 71, 0.16);
    }

    .slide-grid {
        display: grid;
        gap: 16px;
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .slide-card {
        border: 1px solid rgba(148, 163, 184, 0.18);
        border-radius: 18px;
        overflow: hidden;
        background: white;
        box-shadow: 0 8px 20px rgba(15, 23, 42, 0.04);
    }

    .slide-image {
        width: 100%;
        aspect-ratio: 16 / 8;
        object-fit: cover;
        display: block;
        background: #e2e8f0;
    }

    .slide-body {
        padding: 16px;
    }

    .slide-top {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        align-items: flex-start;
        margin-bottom: 10px;
    }

    .slide-title {
        margin: 0;
        color: var(--text);
        font-size: 16px;
        font-weight: 800;
    }

    .slide-slot {
        font-size: 12px;
        font-weight: 800;
        color: var(--accent);
        background: var(--accent-soft);
        border: 1px solid rgba(29, 78, 216, 0.12);
        padding: 6px 10px;
        border-radius: 999px;
        white-space: nowrap;
    }

    .slide-desc {
        margin: 0 0 14px;
        color: var(--muted);
        font-size: 13px;
        line-height: 1.6;
    }

    .slide-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 14px;
    }

    .mini-pill {
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        padding: 6px 10px;
        font-size: 12px;
        font-weight: 700;
        background: #f8fafc;
        color: var(--text);
        border: 1px solid rgba(148, 163, 184, 0.15);
    }

    .mini-pill.enabled {
        color: var(--success);
        background: rgba(6, 118, 71, 0.08);
    }

    .mini-pill.disabled {
        color: #b42318;
        background: rgba(180, 35, 24, 0.08);
    }

    .slide-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .empty-state {
        padding: 28px;
        border: 1px dashed rgba(100, 116, 139, 0.35);
        border-radius: 18px;
        text-align: center;
        color: var(--muted);
        background: rgba(255,255,255,0.7);
    }

    .current-preview {
        display: flex;
        gap: 12px;
        align-items: center;
        margin-bottom: 14px;
    }

    .current-preview img {
        width: 100px;
        height: 62px;
        object-fit: cover;
        border-radius: 12px;
        border: 1px solid rgba(148, 163, 184, 0.25);
    }

    @media (max-width: 1100px) {
        .span-5, .span-7 { grid-column: span 12; }
        .slide-grid { grid-template-columns: 1fr; }
    }

    @media (max-width: 768px) {
        .carousel-hero { padding: 20px; border-radius: 22px; }
        .form-grid { grid-template-columns: 1fr; }
        .hero-actions { width: 100%; }
        .button { width: 100%; }
    }
</style>

<div class="carousel-admin-shell">
    <div class="container">
        <div class="carousel-hero">
            <div class="hero-top">
                <div class="hero-copy">
                    <div class="role-pill">{{ ucfirst($role) }} dashboard</div>
                    <h1>Carousel Management</h1>
                    <p>Upload new homepage slides, replace images, remove stale slides, and adjust carousel playback settings without touching source code.</p>
                    <div class="hero-meta">
                        <span class="meta-pill">Slides: {{ $slides->count() }}</span>
                        <span class="meta-pill">Path: /{{ $routePrefix }}/settings/carousel</span>
                        <span class="meta-pill">Live homepage data</span>
                    </div>
                </div>
                <div class="hero-actions">
                    <a href="{{ route($dashboardRoute) }}" class="button secondary">Back to dashboard</a>
                    <a href="{{ route($indexRoute) }}" class="button primary">Refresh view</a>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert success">{{ session('success') }}</div>
        @endif

        <div class="grid">
            <section class="card span-5">
                <h2>Carousel Settings</h2>
                <p class="subtle">Control autoplay and looping behavior for the homepage carousel.</p>

                <form method="POST" action="{{ route($settingsUpdateRoute) }}">
                    @csrf
                    @method('PUT')

                    <div class="form-grid">
                        <div class="field full">
                            <label for="autoplay_speed">Autoplay speed (milliseconds)</label>
                            <input
                                type="number"
                                id="autoplay_speed"
                                name="autoplay_speed"
                                min="1000"
                                max="30000"
                                value="{{ old('autoplay_speed', $carouselSettings?->autoplay_speed ?? 4000) }}"
                                required
                            >
                            @error('autoplay_speed')
                                <div class="hint" style="color: var(--danger);">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="field full">
                            <div class="check-row">
                                <label class="check-item">
                                    <input type="checkbox" name="autoplay_enabled" value="1" {{ old('autoplay_enabled', $carouselSettings?->autoplay_enabled ?? true) ? 'checked' : '' }}>
                                    Autoplay enabled
                                </label>
                                <label class="check-item">
                                    <input type="checkbox" name="pause_on_hover" value="1" {{ old('pause_on_hover', $carouselSettings?->pause_on_hover ?? true) ? 'checked' : '' }}>
                                    Pause on hover
                                </label>
                                <label class="check-item">
                                    <input type="checkbox" name="loop" value="1" {{ old('loop', $carouselSettings?->loop ?? true) ? 'checked' : '' }}>
                                    Loop slides
                                </label>
                            </div>
                        </div>
                    </div>

                    <div style="margin-top: 18px;">
                        <button type="submit" class="button primary">Save settings</button>
                    </div>
                </form>
            </section>

            <section class="card span-7">
                @if ($editSlide)
                    <h2>Edit Slide #{{ $editSlide->slot }}</h2>
                    <p class="subtle">Replace the image or update the text fields. Leave the image empty to keep the current file.</p>

                    <div class="current-preview">
                        <img src="{{ asset('storage/' . $editSlide->image_path) }}" alt="Current slide image">
                        <div>
                            <div style="font-weight: 800; color: var(--text);">Current image</div>
                            <div class="hint">Uploading a new image will replace this one.</div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route($slidesUpdateRoute, $editSlide) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-grid">
                            <div class="field">
                                <label for="edit_slot">Slot</label>
                                <input type="number" id="edit_slot" name="slot" min="1" max="99" value="{{ old('slot', $editSlide->slot) }}" required>
                                @error('slot')<div class="hint" style="color: var(--danger);">{{ $message }}</div>@enderror
                            </div>

                            <div class="field">
                                <label for="edit_image_path">Replace image</label>
                                <input type="file" id="edit_image_path" name="image_path" accept="image/jpeg,image/png,image/webp">
                                <div class="hint">JPG, PNG, WEBP. Max 5 MB.</div>
                                @error('image_path')<div class="hint" style="color: var(--danger);">{{ $message }}</div>@enderror
                            </div>

                            <div class="field">
                                <label for="edit_title">Title</label>
                                <input type="text" id="edit_title" name="title" maxlength="120" value="{{ old('title', $editSlide->title) }}" placeholder="Optional slide title">
                                @error('title')<div class="hint" style="color: var(--danger);">{{ $message }}</div>@enderror
                            </div>

                            <div class="field">
                                <label for="edit_link_url">Link URL</label>
                                <input type="url" id="edit_link_url" name="link_url" maxlength="255" value="{{ old('link_url', $editSlide->link_url) }}" placeholder="Optional destination URL">
                                @error('link_url')<div class="hint" style="color: var(--danger);">{{ $message }}</div>@enderror
                            </div>

                            <div class="field full">
                                <label for="edit_description">Description</label>
                                <textarea id="edit_description" name="description" maxlength="255" placeholder="Optional slide description">{{ old('description', $editSlide->description) }}</textarea>
                                @error('description')<div class="hint" style="color: var(--danger);">{{ $message }}</div>@enderror
                            </div>

                            <div class="field full">
                                <div class="check-row">
                                    <label class="check-item">
                                        <input type="checkbox" name="enabled" value="1" {{ old('enabled', $editSlide->enabled) ? 'checked' : '' }}>
                                        Enabled
                                    </label>
                                    <label class="check-item">
                                        <input type="checkbox" name="open_in_new_tab" value="1" {{ old('open_in_new_tab', $editSlide->open_in_new_tab) ? 'checked' : '' }}>
                                        Open link in new tab
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div style="display: flex; gap: 10px; flex-wrap: wrap; margin-top: 18px;">
                            <button type="submit" class="button primary">Update slide</button>
                            <a href="{{ route($indexRoute) }}" class="button secondary">Cancel</a>
                        </div>
                    </form>
                @else
                    <h2>Add New Slide</h2>
                    <p class="subtle">Upload a new carousel image and optionally provide title and description text.</p>

                    <form method="POST" action="{{ route($slidesStoreRoute) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-grid">
                            <div class="field">
                                <label for="slot">Slot</label>
                                <input type="number" id="slot" name="slot" min="1" max="99" value="{{ old('slot') }}" required>
                                <div class="hint">Used to order the slides on the homepage.</div>
                                @error('slot')<div class="hint" style="color: var(--danger);">{{ $message }}</div>@enderror
                            </div>

                            <div class="field">
                                <label for="image_path">Image</label>
                                <input type="file" id="image_path" name="image_path" accept="image/jpeg,image/png,image/webp" required>
                                <div class="hint">JPG, PNG, WEBP. Max 5 MB.</div>
                                @error('image_path')<div class="hint" style="color: var(--danger);">{{ $message }}</div>@enderror
                            </div>

                            <div class="field">
                                <label for="title">Title</label>
                                <input type="text" id="title" name="title" maxlength="120" value="{{ old('title') }}" placeholder="Optional slide title">
                                @error('title')<div class="hint" style="color: var(--danger);">{{ $message }}</div>@enderror
                            </div>

                            <div class="field">
                                <label for="link_url">Link URL</label>
                                <input type="url" id="link_url" name="link_url" maxlength="255" value="{{ old('link_url') }}" placeholder="Optional destination URL">
                                @error('link_url')<div class="hint" style="color: var(--danger);">{{ $message }}</div>@enderror
                            </div>

                            <div class="field full">
                                <label for="description">Description</label>
                                <textarea id="description" name="description" maxlength="255" placeholder="Optional slide description">{{ old('description') }}</textarea>
                                @error('description')<div class="hint" style="color: var(--danger);">{{ $message }}</div>@enderror
                            </div>

                            <div class="field full">
                                <div class="check-row">
                                    <label class="check-item">
                                        <input type="checkbox" name="enabled" value="1" {{ old('enabled', true) ? 'checked' : '' }}>
                                        Enabled
                                    </label>
                                    <label class="check-item">
                                        <input type="checkbox" name="open_in_new_tab" value="1" {{ old('open_in_new_tab') ? 'checked' : '' }}>
                                        Open link in new tab
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div style="margin-top: 18px;">
                            <button type="submit" class="button primary">Upload slide</button>
                        </div>
                    </form>
                @endif
            </section>
        </div>

        <section class="card span-12" style="margin-bottom: 0;">
            <h2>Current Slides</h2>
            <p class="subtle">Use edit to replace an image or adjust the text. Use delete to remove a slide from the homepage.
            </p>

            @if ($slides->isEmpty())
                <div class="empty-state">No carousel slides yet. Upload the first one using the form above.</div>
            @else
                <div class="slide-grid">
                    @foreach ($slides as $slide)
                        <article class="slide-card">
                            <img class="slide-image" src="{{ asset('storage/' . $slide->image_path) }}" alt="Slide {{ $slide->slot }}">
                            <div class="slide-body">
                                <div class="slide-top">
                                    <h3 class="slide-title">{{ $slide->title ?: 'Untitled slide' }}</h3>
                                    <span class="slide-slot">Slot {{ $slide->slot }}</span>
                                </div>

                                <p class="slide-desc">{{ $slide->description ?: 'No description provided.' }}</p>

                                <div class="slide-meta">
                                    <span class="mini-pill {{ $slide->enabled ? 'enabled' : 'disabled' }}">
                                        {{ $slide->enabled ? 'Enabled' : 'Disabled' }}
                                    </span>
                                    @if ($slide->link_url)
                                        <span class="mini-pill">Link set</span>
                                    @endif
                                    @if ($slide->open_in_new_tab)
                                        <span class="mini-pill">New tab</span>
                                    @endif
                                </div>

                                <div class="slide-actions">
                                    <a href="{{ route($indexRoute, ['edit' => $slide->id]) }}" class="button secondary">Edit</a>
                                    <form method="POST" action="{{ route($slidesDestroyRoute, $slide) }}" onsubmit="return confirm('Delete this carousel slide?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="button danger">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </section>
    </div>
</div>
@endsection
