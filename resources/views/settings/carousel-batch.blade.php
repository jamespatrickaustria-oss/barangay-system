@extends('layouts.app')

@section('title', 'Carousel Management')

@section('content')
@php
    $routePrefix = $routePrefix ?? request()->segment(1);
    $dashboardRoute = $dashboardRoute ?? ($routePrefix . '.dashboard');
    $updateRoute = $routePrefix . '.carousel-settings.update';
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
        justify-content: center;
        gap: 8px;
        border: 0;
        border-radius: 14px;
        padding: 12px 18px;
        font-weight: 800;
        font-size: 14px;
        text-decoration: none;
        cursor: pointer;
        transition: transform 0.18s ease, box-shadow 0.18s ease, background 0.18s ease;
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

    .message {
        border-radius: 18px;
        padding: 14px 16px;
        margin-bottom: 18px;
        font-weight: 700;
        display: none;
    }

    .message.success {
        display: block;
        color: var(--success);
        background: rgba(6, 118, 71, 0.08);
        border: 1px solid rgba(6, 118, 71, 0.18);
    }

    .message.error {
        display: block;
        color: var(--danger);
        background: rgba(180, 35, 24, 0.08);
        border: 1px solid rgba(180, 35, 24, 0.18);
    }

    .layout {
        display: grid;
        grid-template-columns: 360px minmax(0, 1fr);
        gap: 18px;
        margin-bottom: 18px;
    }

    .card {
        background: var(--panel);
        border: 1px solid var(--panel-border);
        border-radius: 24px;
        box-shadow: var(--shadow);
        padding: 22px;
    }

    .card h2 {
        margin: 0 0 8px;
        font-size: 18px;
        letter-spacing: -0.03em;
        color: var(--text);
    }

    .subtle {
        margin: 0 0 18px;
        color: var(--muted);
        font-size: 13px;
        line-height: 1.65;
    }

    .field {
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-bottom: 14px;
    }

    .field label {
        font-size: 13px;
        font-weight: 800;
        color: var(--text);
    }

    .field input[type="number"],
    .field input[type="text"],
    .field input[type="url"],
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
        border-color: rgba(29, 78, 216, 0.5);
        box-shadow: 0 0 0 4px rgba(29, 78, 216, 0.08);
    }

    .field textarea {
        min-height: 108px;
        resize: vertical;
    }

    .checks {
        display: flex;
        flex-wrap: wrap;
        gap: 14px;
        margin-top: 4px;
    }

    .check {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: var(--text);
        font-size: 13px;
        font-weight: 700;
    }

    .hint {
        font-size: 12px;
        color: var(--muted);
    }

    .hint.error {
        color: var(--danger);
    }

    .settings-grid {
        display: grid;
        gap: 12px;
        grid-template-columns: 1fr;
    }

    .slides-panel {
        padding: 22px;
    }

    .slides-header {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
        align-items: flex-start;
        margin-bottom: 16px;
    }

    .slides-header .title-wrap h2 {
        margin: 0 0 6px;
        font-size: 20px;
        color: var(--text);
    }

    .slides-header .title-wrap p {
        margin: 0;
        color: var(--muted);
        font-size: 13px;
        line-height: 1.65;
    }

    .progress-wrap {
        margin-top: 16px;
        display: none;
    }

    .progress-wrap.active {
        display: block;
    }

    .progress-bar-shell {
        width: 100%;
        height: 12px;
        background: #e2e8f0;
        border-radius: 999px;
        overflow: hidden;
        border: 1px solid rgba(148, 163, 184, 0.18);
    }

    .progress-bar-fill {
        width: 0%;
        height: 100%;
        background: linear-gradient(90deg, #1d4ed8, #60a5fa);
        transition: width 0.1s linear;
    }

    .progress-label {
        margin-top: 8px;
        font-size: 12px;
        color: var(--muted);
        font-weight: 700;
    }

    .slides-grid {
        display: grid;
        gap: 14px;
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .slide-card {
        border-radius: 22px;
        border: 1px solid rgba(148, 163, 184, 0.18);
        background: white;
        overflow: hidden;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.04);
        display: flex;
        flex-direction: column;
        min-height: 100%;
    }

    .slide-card.marked-delete {
        border-color: rgba(180, 35, 24, 0.35);
        box-shadow: 0 0 0 3px rgba(180, 35, 24, 0.06);
    }

    .slide-media {
        position: relative;
        aspect-ratio: 16 / 8;
        background: linear-gradient(135deg, #e2e8f0, #f8fafc);
        border-bottom: 1px solid rgba(148, 163, 184, 0.16);
        overflow: hidden;
    }

    .slide-media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .slide-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 8px;
        color: var(--muted);
        font-weight: 700;
        font-size: 13px;
        letter-spacing: 0.02em;
    }

    .slide-body {
        padding: 16px;
    }

    .slide-top {
        display: flex;
        justify-content: space-between;
        gap: 10px;
        align-items: flex-start;
        margin-bottom: 12px;
    }

    .order-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border-radius: 999px;
        background: var(--accent-soft);
        color: var(--accent);
        border: 1px solid rgba(29, 78, 216, 0.14);
        padding: 7px 10px;
        font-size: 12px;
        font-weight: 800;
        white-space: nowrap;
    }

    .slide-actions-top {
        display: flex;
        gap: 8px;
        align-items: center;
        flex-wrap: wrap;
        justify-content: flex-end;
    }

    .mini-button {
        border: 1px solid rgba(148, 163, 184, 0.24);
        background: #fff;
        color: var(--text);
        border-radius: 10px;
        padding: 8px 10px;
        font-size: 12px;
        font-weight: 800;
        cursor: pointer;
    }

    .mini-button:hover {
        border-color: rgba(29, 78, 216, 0.3);
    }

    .mini-button.danger {
        color: var(--danger);
        border-color: rgba(180, 35, 24, 0.2);
    }

    .slide-body .field {
        margin-bottom: 12px;
    }

    .slide-footer {
        display: flex;
        justify-content: space-between;
        gap: 10px;
        align-items: center;
        flex-wrap: wrap;
        margin-top: 10px;
    }

    .delete-wrap {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        font-weight: 700;
        color: var(--danger);
    }

    .actions-row {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        align-items: center;
        flex-wrap: wrap;
        margin-top: 16px;
    }

    .footer-note {
        font-size: 12px;
        color: var(--muted);
        line-height: 1.6;
    }

    @media (max-width: 1200px) {
        .layout {
            grid-template-columns: 1fr;
        }

        .slides-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="carousel-shell">
    <div class="container">
        <div class="hero">
            <div class="hero-top">
                <div>
                    <h1>Carousel Management</h1>
                    <p>Manage the homepage carousel directly from the dashboard. Select up to 7 images, preview them before upload, reorder the display sequence, and save everything with one update.</p>
                    <div class="pills">
                        <span class="pill accent">{{ ucfirst($routePrefix) }} panel</span>
                        <span class="pill">7 image limit</span>
                        <span class="pill">Live preview</span>
                        <span class="pill">Single update action</span>
                    </div>
                </div>
                <div class="hero-actions">
                    <a href="{{ route($dashboardRoute) }}" class="button secondary">Back to dashboard</a>
                    <a href="{{ route($routePrefix . '.carousel-settings.index') }}" class="button primary">Refresh</a>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div id="serverMessage" class="message success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div id="serverMessage" class="message error">
                Please fix the highlighted fields and try again.
            </div>
        @endif

        <form id="carouselForm" method="POST" action="{{ route($updateRoute) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="layout">
                <section class="card">
                    <h2>Carousel Settings</h2>
                    <p class="subtle">These settings are saved together with the slide changes.</p>

                    <div class="settings-grid">
                        <div class="field">
                            <label for="autoplay_speed">Autoplay speed</label>
                            <input type="number" id="autoplay_speed" name="autoplay_speed" min="1000" max="30000" value="{{ old('autoplay_speed', $carouselSettings?->autoplay_speed ?? 4000) }}" required>
                            <div class="hint">Milliseconds between slide changes.</div>
                            @error('autoplay_speed')<div class="hint error">{{ $message }}</div>@enderror
                        </div>

                        <div class="checks">
                            <label class="check"><input type="checkbox" name="autoplay_enabled" value="1" {{ old('autoplay_enabled', $carouselSettings?->autoplay_enabled ?? true) ? 'checked' : '' }}> Autoplay</label>
                            <label class="check"><input type="checkbox" name="pause_on_hover" value="1" {{ old('pause_on_hover', $carouselSettings?->pause_on_hover ?? true) ? 'checked' : '' }}> Pause on hover</label>
                            <label class="check"><input type="checkbox" name="loop" value="1" {{ old('loop', $carouselSettings?->loop ?? true) ? 'checked' : '' }}> Loop</label>
                        </div>
                    </div>
                </section>

                <section class="card slides-panel">
                    <div class="slides-header">
                        <div class="title-wrap">
                            <h2>Homepage Slides</h2>
                            <p>Use the arrows to reorder. Leave a file input empty to keep the current image. Check delete only if you want to remove that slide entirely.</p>
                        </div>
                    </div>

                    <div id="uploadProgress" class="progress-wrap" aria-live="polite">
                        <div class="progress-bar-shell">
                            <div id="uploadProgressBar" class="progress-bar-fill"></div>
                        </div>
                        <div id="uploadProgressLabel" class="progress-label">Preparing upload...</div>
                    </div>

                    <div id="ajaxMessage" class="message"></div>

                    <div id="slidesGrid" class="slides-grid">
                        @foreach ($slides as $index => $slide)
                            <article class="slide-card {{ old("slides.$index.delete", false) ? 'marked-delete' : '' }}" data-slide-card data-index="{{ $index }}">
                                <div class="slide-media">
                                    <img
                                        data-preview-target
                                        src="{{ $slide['preview_url'] ?: asset('images/carousel/slide' . $slide['slot'] . '.svg') }}"
                                        alt="Carousel slide preview {{ $slide['slot'] }}"
                                    >
                                </div>

                                <div class="slide-body">
                                    <div class="slide-top">
                                        <span class="order-pill" data-order-pill>Order {{ $slide['slot'] }}</span>
                                        <div class="slide-actions-top">
                                            <button type="button" class="mini-button" data-move="up">Move up</button>
                                            <button type="button" class="mini-button" data-move="down">Move down</button>
                                        </div>
                                    </div>

                                    <input type="hidden" name="slides[{{ $index }}][id]" value="{{ old("slides.$index.id", $slide['id']) }}" data-slide-id>
                                    <input type="hidden" name="slides[{{ $index }}][slot]" value="{{ old("slides.$index.slot", $slide['slot']) }}" data-slide-slot>

                                    <div class="field">
                                        <label>Image</label>
                                        <input type="file" name="slides[{{ $index }}][image]" accept="image/jpeg,image/jpg,image/png,image/webp" data-image-input>
                                        <div class="hint">JPG, JPEG, PNG, WEBP. Max 5 MB.</div>
                                    </div>

                                    <div class="field">
                                        <label>Title</label>
                                        <input type="text" name="slides[{{ $index }}][title]" maxlength="120" value="{{ old("slides.$index.title", $slide['title']) }}" placeholder="Optional title">
                                    </div>

                                    <div class="field">
                                        <label>Description</label>
                                        <textarea name="slides[{{ $index }}][description]" maxlength="255" placeholder="Optional description">{{ old("slides.$index.description", $slide['description']) }}</textarea>
                                    </div>

                                    <div class="field">
                                        <label>Link URL</label>
                                        <input type="url" name="slides[{{ $index }}][link_url]" maxlength="255" value="{{ old("slides.$index.link_url", $slide['link_url']) }}" placeholder="Optional destination">
                                    </div>

                                    <div class="checks">
                                        <label class="check"><input type="checkbox" name="slides[{{ $index }}][enabled]" value="1" {{ old("slides.$index.enabled", $slide['enabled']) ? 'checked' : '' }}> Enabled</label>
                                        <label class="check"><input type="checkbox" name="slides[{{ $index }}][open_in_new_tab]" value="1" {{ old("slides.$index.open_in_new_tab", $slide['open_in_new_tab']) ? 'checked' : '' }}> Open in new tab</label>
                                    </div>

                                    <div class="slide-footer">
                                        <label class="delete-wrap">
                                            <input type="checkbox" name="slides[{{ $index }}][delete]" value="1" {{ old("slides.$index.delete", false) ? 'checked' : '' }} data-delete-toggle>
                                            Delete slide
                                        </label>
                                        <span class="hint">Keep image unless replaced or deleted.</span>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    <div class="actions-row">
                        <div class="footer-note">The grid is limited to 7 slots, so the homepage never accepts more than 7 carousel images.</div>
                        <button id="submitButton" type="submit" class="button primary">Update Carousel</button>
                    </div>
                </section>
            </div>
        </form>
    </div>
</div>

<script>
(function () {
    const form = document.getElementById('carouselForm');
    const slidesGrid = document.getElementById('slidesGrid');
    const messageBox = document.getElementById('ajaxMessage');
    const progressWrap = document.getElementById('uploadProgress');
    const progressBar = document.getElementById('uploadProgressBar');
    const progressLabel = document.getElementById('uploadProgressLabel');
    const submitButton = document.getElementById('submitButton');

    const validMimeTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];

    function showMessage(type, text) {
        messageBox.className = 'message ' + type;
        messageBox.textContent = text;
        messageBox.style.display = 'block';
    }

    function clearMessage() {
        messageBox.className = 'message';
        messageBox.textContent = '';
        messageBox.style.display = 'none';
    }

    function updateOrderLabels() {
        const cards = [...slidesGrid.querySelectorAll('[data-slide-card]')];

        cards.forEach((card, index) => {
            const slotInput = card.querySelector('[data-slide-slot]');
            const orderPill = card.querySelector('[data-order-pill]');

            if (slotInput) {
                slotInput.value = index + 1;
            }

            if (orderPill) {
                orderPill.textContent = 'Order ' + (index + 1);
            }
        });
    }

    function setPreview(card, file) {
        const preview = card.querySelector('[data-preview-target]');
        const deleteToggle = card.querySelector('[data-delete-toggle]');
        const url = URL.createObjectURL(file);

        preview.src = url;
        preview.onload = function () {
            URL.revokeObjectURL(url);
        };

        if (deleteToggle && deleteToggle.checked) {
            deleteToggle.checked = false;
            card.classList.remove('marked-delete');
        }
    }

    function refreshCardState(card) {
        const deleteToggle = card.querySelector('[data-delete-toggle]');
        const fileInput = card.querySelector('[data-image-input]');

        if (!deleteToggle) {
            return;
        }

        if (deleteToggle.checked) {
            card.classList.add('marked-delete');
            if (fileInput) {
                fileInput.value = '';
            }
        } else {
            card.classList.remove('marked-delete');
        }
    }

    slidesGrid.querySelectorAll('[data-slide-card]').forEach((card) => {
        const fileInput = card.querySelector('[data-image-input]');
        const deleteToggle = card.querySelector('[data-delete-toggle]');
        const moveUp = card.querySelector('[data-move="up"]');
        const moveDown = card.querySelector('[data-move="down"]');

        if (fileInput) {
            fileInput.addEventListener('change', function () {
                const file = this.files && this.files[0];

                if (!file) {
                    return;
                }

                if (!validMimeTypes.includes(file.type)) {
                    showMessage('error', 'Only JPG, JPEG, PNG, and WEBP files are allowed.');
                    this.value = '';
                    return;
                }

                if (file.size > 5 * 1024 * 1024) {
                    showMessage('error', 'Each image must be 5 MB or smaller.');
                    this.value = '';
                    return;
                }

                clearMessage();
                setPreview(card, file);
            });
        }

        if (deleteToggle) {
            deleteToggle.addEventListener('change', function () {
                refreshCardState(card);
            });
        }

        if (moveUp) {
            moveUp.addEventListener('click', function () {
                const previous = card.previousElementSibling;

                if (previous) {
                    slidesGrid.insertBefore(card, previous);
                    updateOrderLabels();
                }
            });
        }

        if (moveDown) {
            moveDown.addEventListener('click', function () {
                const next = card.nextElementSibling;

                if (next) {
                    slidesGrid.insertBefore(next, card);
                    updateOrderLabels();
                }
            });
        }

        refreshCardState(card);
    });

    updateOrderLabels();

    form.addEventListener('submit', function (event) {
        event.preventDefault();
        clearMessage();

        const files = [...form.querySelectorAll('[data-image-input]')]
            .map((input) => input.files && input.files[0])
            .filter(Boolean);

        if (files.length > 7) {
            showMessage('error', 'You can upload up to 7 images only.');
            return;
        }

        const formData = new FormData(form);
        const xhr = new XMLHttpRequest();

        submitButton.disabled = true;
        submitButton.textContent = 'Uploading...';
        progressWrap.classList.add('active');
        progressBar.style.width = '0%';
        progressLabel.textContent = 'Starting upload...';

        xhr.open('POST', form.action, true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

        xhr.upload.addEventListener('progress', function (event) {
            if (!event.lengthComputable) {
                progressLabel.textContent = 'Uploading...';
                return;
            }

            const percent = Math.round((event.loaded / event.total) * 100);
            progressBar.style.width = percent + '%';
            progressLabel.textContent = percent + '% uploaded';
        });

        xhr.onload = function () {
            submitButton.disabled = false;
            submitButton.textContent = 'Update Carousel';

            if (xhr.status >= 200 && xhr.status < 300) {
                let response = {};

                try {
                    response = JSON.parse(xhr.responseText || '{}');
                } catch (error) {
                    response = {};
                }

                showMessage('success', response.message || 'Carousel updated successfully.');
                progressLabel.textContent = 'Done';
                progressBar.style.width = '100%';

                setTimeout(function () {
                    window.location.href = response.redirect || window.location.href;
                }, 500);

                return;
            }

            let errorMessage = 'Unable to update carousel.';

            try {
                const payload = JSON.parse(xhr.responseText || '{}');
                if (payload.message) {
                    errorMessage = payload.message;
                }
                if (payload.errors) {
                    const firstKey = Object.keys(payload.errors)[0];
                    if (firstKey && payload.errors[firstKey][0]) {
                        errorMessage = payload.errors[firstKey][0];
                    }
                }
            } catch (error) {
                // Keep default error message.
            }

            showMessage('error', errorMessage);
            progressLabel.textContent = 'Failed';
            progressBar.style.width = '0%';
        };

        xhr.onerror = function () {
            submitButton.disabled = false;
            submitButton.textContent = 'Update Carousel';
            progressWrap.classList.remove('active');
            showMessage('error', 'Network error while uploading carousel changes.');
        };

        xhr.send(formData);
    });
})();
</script>
@endsection
