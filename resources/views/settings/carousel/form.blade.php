@php
    $routePrefix = $routePrefix ?? (auth()->check() ? auth()->user()->role : 'admin');
    $isEdit = isset($slide) && $slide->exists;
@endphp

<style>
    .form-container {
        max-width: 900px;
        margin: 0 auto;
    }

    .form-group {
        margin-bottom: 28px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-size: 14px;
        font-weight: 600;
        color: #0f172a;
    }

    .form-group input:not([type="checkbox"]):not([type="file"]),
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid rgba(148, 163, 184, 0.18);
        border-radius: 8px;
        font-size: 14px;
        font-family: inherit;
    }

    .form-group input:not([type="checkbox"]):not([type="file"]):focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #1d4ed8;
        box-shadow: 0 0 0 3px rgba(29, 78, 216, 0.08);
    }

    .form-group textarea {
        resize: vertical;
        min-height: 100px;
    }

    .form-group .help-text {
        margin-top: 6px;
        font-size: 12px;
        color: #64748b;
    }

    .form-group .error-text {
        margin-top: 6px;
        font-size: 12px;
        color: #b42318;
    }

    .checkbox-group {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .checkbox-item {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .checkbox-item input[type="checkbox"] {
        width: 18px;
        height: 18px;
        cursor: pointer;
    }

    .checkbox-item label {
        margin: 0;
        font-weight: 500;
        cursor: pointer;
    }

    /* Multi-Image Upload Styles */
    .images-upload-wrapper {
        background: linear-gradient(135deg, rgba(248,250,252,0.8) 0%, rgba(241,245,249,0.8) 100%);
        border: 2px dashed rgba(29, 78, 216, 0.2);
        border-radius: 12px;
        padding: 32px 20px;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .images-upload-wrapper.drag-over {
        border-color: #1d4ed8;
        background: rgba(29, 78, 216, 0.04);
    }

    .upload-icon {
        font-size: 48px;
        margin-bottom: 12px;
    }

    .upload-text {
        color: #0f172a;
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 4px;
    }

    .upload-subtext {
        color: #64748b;
        font-size: 13px;
        margin-bottom: 16px;
    }

    .upload-button {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 10px 16px;
        background: #1d4ed8;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .upload-button:hover {
        background: #1a3fa8;
    }

    #imageInput {
        display: none;
    }

    /* Image Preview Grid */
    .images-preview {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
        gap: 12px;
        margin-top: 20px;
    }

    .image-item {
        position: relative;
        background: white;
        border: 1px solid rgba(148, 163, 184, 0.18);
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.2s ease;
        cursor: grab;
    }

    .image-item.dragging {
        opacity: 0.5;
        cursor: grabbing;
    }

    .image-item:hover {
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.1);
    }

    .image-preview-img {
        width: 100%;
        height: 120px;
        object-fit: cover;
        background: #f4f7fb;
        display: block;
    }

    .image-info {
        padding: 8px;
        background: white;
        border-top: 1px solid rgba(148, 163, 184, 0.18);
    }

    .image-filename {
        font-size: 11px;
        color: #0f172a;
        font-weight: 600;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-bottom: 3px;
    }

    .image-size {
        font-size: 10px;
        color: #64748b;
    }

    .image-remove {
        position: absolute;
        top: 4px;
        right: 4px;
        width: 28px;
        height: 28px;
        background: rgba(255, 255, 255, 0.95);
        border: 1px solid rgba(180, 35, 24, 0.3);
        border-radius: 6px;
        color: #b42318;
        font-size: 16px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        opacity: 0;
        padding: 0;
    }

    .image-item:hover .image-remove {
        opacity: 1;
    }

    .image-remove:hover {
        background: rgba(180, 35, 24, 0.1);
        border-color: rgba(180, 35, 24, 0.5);
    }

    .image-order-badge {
        position: absolute;
        top: 4px;
        left: 4px;
        width: 28px;
        height: 28px;
        background: rgba(29, 78, 216, 0.9);
        border-radius: 50%;
        color: white;
        font-size: 12px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .images-count {
        font-size: 12px;
        color: #64748b;
        margin-top: 12px;
    }

    /* Existing Images Section */
    .existing-images-section {
        margin-top: 32px;
        padding-top: 28px;
        border-top: 1px solid rgba(148, 163, 184, 0.18);
    }

    .existing-images-section h3 {
        margin: 0 0 16px;
        font-size: 16px;
        font-weight: 600;
        color: #0f172a;
    }

    .existing-images-preview {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
        gap: 12px;
    }

    /* Error Messages */
    .error-message {
        background: rgba(180, 35, 24, 0.08);
        border: 1px solid rgba(180, 35, 24, 0.2);
        color: #b42318;
        padding: 12px;
        border-radius: 8px;
        font-size: 13px;
        margin-top: 8px;
    }

    .button-group {
        display: flex;
        gap: 10px;
        margin-top: 32px;
    }

    .button {
        padding: 12px 20px;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .button.primary {
        background: #1d4ed8;
        color: white;
    }

    .button.primary:hover {
        background: #1a3fa8;
    }

    .button.secondary {
        background: white;
        color: #0f172a;
        border: 1px solid rgba(148, 163, 184, 0.18);
    }

    .button.secondary:hover {
        border-color: #1d4ed8;
    }

    .required {
        color: #b42318;
        font-weight: 700;
    }
</style>

<form method="POST" action="{{ $isEdit ? route($routePrefix . '.carousel-settings.update', $slide) : route($routePrefix . '.carousel-settings.store') }}" enctype="multipart/form-data">
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    <div class="form-container">
        <!-- Slot Selection -->
        <div class="form-group">
            <label for="slot">
                <div class="field-label">
                    Carousel Slot <span class="required">*</span>
                </div>
            </label>
            <select id="slot" name="slot" required>
                <option value="">-- Select Slot --</option>
                @for ($i = 1; $i <= 7; $i++)
                    @php
                        $slotTaken = \App\Models\CarouselSlide::where('slot', $i)->where('id', '!=', $slide?->id)->exists();
                    @endphp
                    <option value="{{ $i }}" {{ old('slot', $slide?->slot) == $i ? 'selected' : '' }} {{ !$isEdit && $slotTaken ? 'disabled' : '' }}>
                        Slot {{ $i }} {{ $slotTaken && !$isEdit ? '(taken)' : '' }}
                    </option>
                @endfor
            </select>
            <div class="help-text">Choose which position this slide should appear in the carousel (1-7).</div>
            @error('slot')
                <div class="error-text">{{ $message }}</div>
            @enderror
        </div>

        <!-- Multi-Image Upload -->
        <div class="form-group">
            <label>
                <div class="field-label">
                    Carousel Images <span class="required">{{ !$isEdit ? '*' : '' }}</span>
                </div>
            </label>
            
            <div class="images-upload-wrapper" id="uploadArea">
                <div class="upload-icon">📸</div>
                <div class="upload-text">Upload Carousel Images</div>
                <div class="upload-subtext">Drag images here or click to browse</div>
                <button type="button" class="upload-button" onclick="document.getElementById('imageInput').click()">
                    📁 Select Images
                </button>
                <input type="file" id="imageInput" name="images[]" multiple accept="image/jpeg,image/jpg,image/png,image/webp" {{ !$isEdit ? 'required' : '' }}>
                <div class="help-text" style="margin-top: 12px;">JPG, JPEG, PNG, or WEBP. Up to 10 images. Max 5 MB each.</div>
                <div id="uploadError" class="error-message" style="display: none;"></div>
            </div>

            <!-- Image Previews -->
            <div id="previewContainer" style="display: none;">
                <div style="margin-top: 24px;">
                    <h3 style="margin: 0 0 12px; font-size: 14px; font-weight: 600; color: #0f172a;">Selected Images</h3>
                    <div class="images-preview" id="imagePreviewsGrid"></div>
                    <div class="images-count">
                        <span id="imageCount">0</span> image(s) selected
                    </div>
                </div>
            </div>

            @if ($isEdit && $slide->images->count() > 0)
                <div class="existing-images-section">
                    <h3>Current Images</h3>
                    <div class="existing-images-preview" id="existingImagesGrid">
                        @foreach ($slide->images as $image)
                            <div class="image-item" data-image-id="{{ $image->id }}">
                                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($image->image_path) }}" alt="Carousel image" class="image-preview-img">
                                <div class="image-order-badge">{{ $loop->iteration }}</div>
                                <button type="button" class="image-remove" data-image-id="{{ $image->id }}" onclick="deleteExistingImage(event, {{ $image->id }})">✕</button>
                                <input type="hidden" name="existing_images[]" value="{{ $image->id }}">
                                <div class="image-info">
                                    <div class="image-filename" title="{{ basename($image->image_path) }}">
                                        {{ Str::limit(basename($image->image_path), 15) }}
                                    </div>
                                    <div class="image-size">
                                        @php
                                            $filePath = \Illuminate\Support\Facades\Storage::disk('public')->path($image->image_path);
                                            $fileSize = file_exists($filePath) ? filesize($filePath) : 0;
                                            $fileSizeKb = round($fileSize / 1024, 1);
                                        @endphp
                                        {{ $fileSizeKb }} KB
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Title -->
        <div class="form-group">
            <label for="title">Slide Title</label>
            <input type="text" id="title" name="title" maxlength="120" value="{{ old('title', $slide?->title) }}" placeholder="Optional: Give this slide a title">
            <div class="help-text">Up to 120 characters.</div>
            @error('title')
                <div class="error-text">{{ $message }}</div>
            @enderror
        </div>

        <!-- Description -->
        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" maxlength="255" placeholder="Optional: Add a description for this slide">{{ old('description', $slide?->description) }}</textarea>
            <div class="help-text">Up to 255 characters.</div>
            @error('description')
                <div class="error-text">{{ $message }}</div>
            @enderror
        </div>

        <!-- Link URL -->
        <div class="form-group">
            <label for="link_url">Destination URL</label>
            <input type="url" id="link_url" name="link_url" maxlength="255" value="{{ old('link_url', $slide?->link_url) }}" placeholder="Optional: https://example.com">
            <div class="help-text">Where users go when they click this slide.</div>
            @error('link_url')
                <div class="error-text">{{ $message }}</div>
            @enderror
        </div>

        <!-- Options -->
        <div class="form-group">
            <label>Options</label>
            <div class="checkbox-group">

                <div class="checkbox-item">
                    <input type="checkbox" id="open_in_new_tab" name="open_in_new_tab" value="1" {{ old('open_in_new_tab', $slide?->open_in_new_tab ?? false) ? 'checked' : '' }}>
                    <label for="open_in_new_tab">Open link in new tab</label>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="button-group">
            <button type="submit" class="button primary">
                {{ $isEdit ? 'Update Slide' : 'Create Slide' }}
            </button>
            <a href="{{ route($routePrefix . '.carousel-settings.index') }}" class="button secondary">Cancel</a>
        </div>
    </div>
</form>

<script>
(function () {
    const uploadArea = document.getElementById('uploadArea');
    const imageInput = document.getElementById('imageInput');
    const previewContainer = document.getElementById('previewContainer');
    const imagePreviewsGrid = document.getElementById('imagePreviewsGrid');
    const imageCount = document.getElementById('imageCount');
    const uploadError = document.getElementById('uploadError');

    const MAX_FILES = 10;
    const MAX_FILE_SIZE = 5 * 1024 * 1024; // 5 MB
    const VALID_TYPES = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];

    let selectedFiles = [];
    let dragCounter = 0;

    // Drag and drop handlers
    uploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        dragCounter++;
        uploadArea.classList.add('drag-over');
    });

    uploadArea.addEventListener('dragleave', () => {
        dragCounter--;
        if (dragCounter === 0) {
            uploadArea.classList.remove('drag-over');
        }
    });

    uploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadArea.classList.remove('drag-over');
        dragCounter = 0;

        const files = e.dataTransfer.files;
        handleFiles(files);
    });

    imageInput.addEventListener('change', (e) => {
        handleFiles(e.target.files);
    });

    function handleFiles(files) {
        uploadError.style.display = 'none';
        uploadError.textContent = '';

        if (files.length + selectedFiles.length > MAX_FILES) {
            showError(`Maximum ${MAX_FILES} files allowed. You have ${selectedFiles.length} already selected.`);
            return;
        }

        const newFiles = Array.from(files);
        let hasError = false;

        for (const file of newFiles) {
            if (!VALID_TYPES.includes(file.type)) {
                showError(`${file.name} has invalid file type. Only JPG, JPEG, PNG, and WEBP are allowed.`);
                hasError = true;
                continue;
            }

            if (file.size > MAX_FILE_SIZE) {
                showError(`${file.name} is too large. Maximum size is 5 MB.`);
                hasError = true;
                continue;
            }

            selectedFiles.push(file);
        }

        if (!hasError) {
            uploadError.style.display = 'none';
        }

        updatePreviews();
    }

    function updatePreviews() {
        imagePreviewsGrid.innerHTML = '';
        imageCount.textContent = selectedFiles.length;

        if (selectedFiles.length === 0) {
            previewContainer.style.display = 'none';
            imageInput.value = '';
            return;
        }

        previewContainer.style.display = 'block';

        selectedFiles.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = (e) => {
                const fileSizeKb = (file.size / 1024).toFixed(1);
                const previewHTML = `
                    <div class="image-item" draggable="true" data-index="${index}">
                        <img src="${e.target.result}" alt="Preview" class="image-preview-img">
                        <div class="image-order-badge">${index + 1}</div>
                        <button type="button" class="image-remove" onclick="removeImage(${index})" style="border:none;background:none;padding:0;">✕</button>
                        <div class="image-info">
                            <div class="image-filename" title="${file.name}">
                                ${file.name.substring(0, 15)}${file.name.length > 15 ? '...' : ''}
                            </div>
                            <div class="image-size">${fileSizeKb} KB</div>
                        </div>
                    </div>
                `;
                imagePreviewsGrid.insertAdjacentHTML('beforeend', previewHTML);
                attachDragHandlers();
            };
            reader.readAsDataURL(file);
        });

        // Update the file input with new files
        const dataTransfer = new DataTransfer();
        selectedFiles.forEach(file => dataTransfer.items.add(file));
        imageInput.files = dataTransfer.files;
    }

    function removeImage(index) {
        selectedFiles.splice(index, 1);
        updatePreviews();
    }

    function deleteExistingImage(e, imageId) {
        e.preventDefault();
        if (confirm('Are you sure you want to delete this image?')) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'delete_images[]';
            input.value = imageId;
            document.querySelector('form').appendChild(input);

            document.querySelector(`[data-image-id="${imageId}"]`).remove();
        }
    }

    function showError(message) {
        uploadError.textContent = message;
        uploadError.style.display = 'block';
    }

    function attachDragHandlers() {
        const items = document.querySelectorAll('.images-preview .image-item');
        items.forEach(item => {
            item.addEventListener('dragstart', handleDragStart);
            item.addEventListener('dragend', handleDragEnd);
            item.addEventListener('dragover', handleDragOver);
            item.addEventListener('drop', handleDrop);
        });
    }

    let draggedElement = null;

    function handleDragStart(e) {
        draggedElement = this;
        this.classList.add('dragging');
    }

    function handleDragEnd(e) {
        this.classList.remove('dragging');
    }

    function handleDragOver(e) {
        e.preventDefault();
    }

    function handleDrop(e) {
        e.preventDefault();
        if (draggedElement !== this && draggedElement.parentNode === this.parentNode) {
            const allItems = [...this.parentNode.children];
            const draggedIndex = allItems.indexOf(draggedElement);
            const targetIndex = allItems.indexOf(this);

            if (draggedIndex < targetIndex) {
                this.parentNode.insertBefore(draggedElement, this.nextSibling);
            } else {
                this.parentNode.insertBefore(draggedElement, this);
            }

            // Reorder selectedFiles array
            const temp = selectedFiles[draggedIndex];
            selectedFiles.splice(draggedIndex, 1);
            selectedFiles.splice(targetIndex, 0, temp);

            updatePreviews();
        }
    }

    window.removeImage = removeImage;
    window.deleteExistingImage = deleteExistingImage;
})();
</script>
