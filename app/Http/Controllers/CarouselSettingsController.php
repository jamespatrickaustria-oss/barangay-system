<?php

namespace App\Http\Controllers;

use App\Models\CarouselImage;
use App\Models\CarouselSetting;
use App\Models\CarouselSlide;
use App\Services\ImageUploadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CarouselSettingsController extends Controller
{
    public function __construct(private readonly ImageUploadService $imageUploadService)
    {
        $this->imageUploadService->ensureDirectoriesExist();
    }

    /**
     * Display list of carousel slides (index view)
     */
    public function index(Request $request): View
    {
        $this->authorizeRole();

        $slides = CarouselSlide::query()
            ->orderBy('slot')
            ->paginate(10);

        $carouselSettings = CarouselSetting::query()->firstOrNew([]);

        return view('settings.carousel.index', [
            'slides' => $slides,
            'carouselSettings' => $carouselSettings,
            'routePrefix' => $this->routePrefix(),
            'dashboardRoute' => $this->routePrefix() . '.dashboard',
        ]);
    }

    /**
     * Show form to create new carousel slide
     */
    public function create(): View
    {
        $this->authorizeRole();

        return view('settings.carousel.create', [
            'routePrefix' => $this->routePrefix(),
            'slide' => null,
        ]);
    }

    /**
     * Store new carousel slide
     */
    public function store(Request $request): RedirectResponse | JsonResponse
    {
        $this->authorizeRole();

        $validated = $request->validate([
            'slot' => ['required', 'integer', 'min:1', 'max:7', 'unique:carousel_slides,slot'],
            'title' => ['nullable', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:255'],
            'link_url' => ['nullable', 'url', 'max:255'],
            'images' => ['required', 'array', 'min:1', 'max:10'],
            'images.*' => ['required', 'file', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'enabled' => ['sometimes', 'boolean'],
            'open_in_new_tab' => ['sometimes', 'boolean'],
        ]);

        try {
            DB::transaction(function () use ($request, $validated): void {
                // Create the carousel slide
                $slide = CarouselSlide::create([
                    'slot' => (int) $validated['slot'],
                    'image_path' => null, // Will be set from first image
                    'title' => $validated['title'] ?? null,
                    'description' => $validated['description'] ?? null,
                    'link_url' => $validated['link_url'] ?? null,
                    'enabled' => (bool) ($validated['enabled'] ?? true),
                    'open_in_new_tab' => (bool) ($validated['open_in_new_tab'] ?? false),
                ]);

                // Store all images
                $imageFiles = $request->file('images', []);
                foreach ($imageFiles as $index => $imageFile) {
                    if ($imageFile) {
                        $imagePath = $this->imageUploadService->store($imageFile, 'uploads/carousel');
                        
                        CarouselImage::create([
                            'carousel_slide_id' => $slide->id,
                            'image_path' => $imagePath,
                            'sort_order' => $index,
                        ]);

                        // Set first image as primary
                        if ($index === 0) {
                            $slide->update(['image_path' => $imagePath]);
                        }
                    }
                }
            });

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Carousel slide created successfully with ' . count($validated['images']) . ' image(s).',
                    'redirect' => route($this->routeName('index')),
                ]);
            }

            return redirect()
                ->route($this->routeName('index'))
                ->with('success', 'Carousel slide created successfully with ' . count($validated['images']) . ' image(s).');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Error creating slide: ' . $e->getMessage(),
                ], 422);
            }

            return back()
                ->withInput()
                ->with('error', 'Error creating slide: ' . $e->getMessage());
        }
    }

    /**
     * Show form to edit carousel slide
     */
    public function edit(CarouselSlide $carouselSlide): View
    {
        $this->authorizeRole();

        return view('settings.carousel.edit', [
            'slide' => $carouselSlide,
            'routePrefix' => $this->routePrefix(),
        ]);
    }

    /**
     * Update carousel slide
     */
    public function update(Request $request, CarouselSlide $carouselSlide): RedirectResponse | JsonResponse
    {
        $this->authorizeRole();

        $validated = $request->validate([
            'slot' => ['required', 'integer', 'min:1', 'max:7', 'unique:carousel_slides,slot,' . $carouselSlide->id],
            'title' => ['nullable', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:255'],
            'link_url' => ['nullable', 'url', 'max:255'],
            'images' => ['nullable', 'array', 'max:10'],
            'images.*' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'delete_images' => ['nullable', 'array'],
            'delete_images.*' => ['nullable', 'integer'],
            'image_order' => ['nullable', 'array'],
            'enabled' => ['sometimes', 'boolean'],
            'open_in_new_tab' => ['sometimes', 'boolean'],
        ]);

        try {
            DB::transaction(function () use ($request, $validated, $carouselSlide): void {
                // Delete images marked for deletion
                $deleteImages = $validated['delete_images'] ?? [];
                foreach ($deleteImages as $imageId) {
                    $image = CarouselImage::find($imageId);
                    if ($image) {
                        $this->imageUploadService->delete($image->image_path);
                        $image->delete();
                    }
                }

                // Add new images
                $newImages = $request->file('images', []);
                $maxSortOrder = $carouselSlide->images()->max('sort_order') ?? -1;
                foreach ($newImages as $imageFile) {
                    if ($imageFile) {
                        $maxSortOrder++;
                        $imagePath = $this->imageUploadService->store($imageFile, 'uploads/carousel');
                        
                        CarouselImage::create([
                            'carousel_slide_id' => $carouselSlide->id,
                            'image_path' => $imagePath,
                            'sort_order' => $maxSortOrder,
                        ]);
                    }
                }

                // Update image order if provided
                if (!empty($validated['image_order'])) {
                    foreach ($validated['image_order'] as $order => $imageId) {
                        CarouselImage::where('id', $imageId)
                            ->where('carousel_slide_id', $carouselSlide->id)
                            ->update(['sort_order' => $order]);
                    }
                }

                // Update slide properties
                $carouselSlide->update([
                    'slot' => (int) $validated['slot'],
                    'title' => $validated['title'] ?? null,
                    'description' => $validated['description'] ?? null,
                    'link_url' => $validated['link_url'] ?? null,
                    'enabled' => (bool) ($validated['enabled'] ?? true),
                    'open_in_new_tab' => (bool) ($validated['open_in_new_tab'] ?? false),
                ]);

                // Update primary image_path to first image if no images left
                $firstImage = $carouselSlide->images()->first();
                if ($firstImage) {
                    $carouselSlide->update(['image_path' => $firstImage->image_path]);
                }
            });

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Carousel slide updated successfully.',
                    'redirect' => route($this->routeName('index')),
                ]);
            }

            return redirect()
                ->route($this->routeName('index'))
                ->with('success', 'Carousel slide updated successfully.');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Error updating slide: ' . $e->getMessage(),
                ], 422);
            }

            return back()
                ->withInput()
                ->with('error', 'Error updating slide: ' . $e->getMessage());
        }
    }

    /**
     * Delete carousel slide
     */
    public function destroy(Request $request, CarouselSlide $carouselSlide): RedirectResponse | JsonResponse
    {
        $this->authorizeRole();

        try {
            DB::transaction(function () use ($carouselSlide): void {
                // Delete all images associated with this slide
                foreach ($carouselSlide->images as $image) {
                    $this->imageUploadService->delete($image->image_path);
                    $image->delete();
                }

                // Delete primary image if exists
                if ($carouselSlide->image_path) {
                    $this->imageUploadService->delete($carouselSlide->image_path);
                }

                // Delete the slide
                $carouselSlide->delete();
            });

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Carousel slide deleted successfully.',
                    'redirect' => route($this->routeName('index')),
                ]);
            }

            return redirect()
                ->route($this->routeName('index'))
                ->with('success', 'Carousel slide deleted successfully.');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Error deleting slide: ' . $e->getMessage(),
                ], 422);
            }

            return back()->with('error', 'Error deleting slide: ' . $e->getMessage());
        }
    }

    /**
     * Update carousel settings (autoplay, speed, etc.)
     */
    public function updateSettings(Request $request): RedirectResponse | JsonResponse
    {
        $this->authorizeRole();

        $validated = $request->validate([
            'autoplay_enabled' => ['sometimes', 'boolean'],
            'autoplay_speed' => ['required', 'integer', 'min:1000', 'max:30000'],
            'pause_on_hover' => ['sometimes', 'boolean'],
            'loop' => ['sometimes', 'boolean'],
        ]);

        try {
            $settings = CarouselSetting::query()->firstOrNew([]);
            $settings->fill([
                'autoplay_enabled' => (bool) ($validated['autoplay_enabled'] ?? false),
                'autoplay_speed' => (int) $validated['autoplay_speed'],
                'pause_on_hover' => (bool) ($validated['pause_on_hover'] ?? false),
                'loop' => (bool) ($validated['loop'] ?? false),
            ]);
            $settings->save();

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Carousel settings updated successfully.',
                ]);
            }

            return back()->with('success', 'Carousel settings updated successfully.');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Error updating settings: ' . $e->getMessage(),
                ], 422);
            }

            return back()->with('error', 'Error updating settings: ' . $e->getMessage());
        }
    }

    /**
     * Batch update carousel (old method - kept for compatibility)
     */
    public function updateCarousel(Request $request): JsonResponse
    {
        $this->authorizeRole();

        $validated = $request->validate([
            'autoplay_enabled' => ['sometimes', 'boolean'],
            'autoplay_speed' => ['required', 'integer', 'min:1000', 'max:30000'],
            'pause_on_hover' => ['sometimes', 'boolean'],
            'loop' => ['sometimes', 'boolean'],
            'slides' => ['required', 'array', 'max:7'],
            'slides.*.id' => ['nullable', 'integer'],
            'slides.*.slot' => ['required', 'integer', 'min:1', 'max:7'],
            'slides.*.title' => ['nullable', 'string', 'max:120'],
            'slides.*.description' => ['nullable', 'string', 'max:255'],
            'slides.*.link_url' => ['nullable', 'url', 'max:255'],
            'slides.*.open_in_new_tab' => ['sometimes', 'boolean'],
            'slides.*.enabled' => ['sometimes', 'boolean'],
            'slides.*.delete' => ['sometimes', 'boolean'],
            'slides.*.image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        DB::transaction(function () use ($request, $validated): void {
            $settings = CarouselSetting::query()->firstOrNew([]);
            $settings->fill([
                'autoplay_enabled' => (bool) ($validated['autoplay_enabled'] ?? false),
                'autoplay_speed' => (int) $validated['autoplay_speed'],
                'pause_on_hover' => (bool) ($validated['pause_on_hover'] ?? false),
                'loop' => (bool) ($validated['loop'] ?? false),
            ]);
            $settings->save();

            $existingSlides = CarouselSlide::query()->get()->keyBy('id');

            foreach ($validated['slides'] as $index => $slideData) {
                $slot = (int) $slideData['slot'];
                $slideId = isset($slideData['id']) ? (int) $slideData['id'] : null;
                $existingSlide = $slideId ? $existingSlides->get($slideId) : null;
                $shouldDelete = $request->boolean("slides.{$index}.delete");
                $uploadedFile = $request->file("slides.{$index}.image");

                if ($shouldDelete) {
                    if ($existingSlide) {
                        $this->imageUploadService->delete($existingSlide->image_path);
                        $existingSlide->delete();
                    }
                    continue;
                }

                $imagePath = $existingSlide?->image_path;

                if ($uploadedFile) {
                    $newImagePath = $this->imageUploadService->store($uploadedFile, 'uploads/carousel');
                    if ($existingSlide && $existingSlide->image_path) {
                        $this->imageUploadService->delete($existingSlide->image_path);
                    }
                    $imagePath = $newImagePath;
                }

                if (! $existingSlide && empty($imagePath)) {
                    continue;
                }

                $slide = $existingSlide ?? new CarouselSlide();
                $slide->slot = $slot;
                $slide->image_path = $imagePath;
                $slide->title = $slideData['title'] ?? null;
                $slide->description = $slideData['description'] ?? null;
                $slide->link_url = $slideData['link_url'] ?? null;
                $slide->open_in_new_tab = (bool) ($slideData['open_in_new_tab'] ?? false);
                $slide->enabled = (bool) ($slideData['enabled'] ?? false);
                $slide->save();
            }

            $validSlots = collect($validated['slides'])
                ->pluck('slot')
                ->map(fn ($slot) => (int) $slot)
                ->all();

            CarouselSlide::query()
                ->whereNotIn('slot', $validSlots)
                ->delete();
        });

        return response()->json([
            'message' => 'Carousel updated successfully.',
            'redirect' => route($this->routeName('index')),
        ]);
    }

    /**
     * Reorder carousel images (AJAX)
     */
    public function reorderImages(Request $request): JsonResponse
    {
        $this->authorizeRole();

        $validated = $request->validate([
            'slide_id' => ['required', 'integer', 'exists:carousel_slides,id'],
            'image_order' => ['required', 'array'],
            'image_order.*' => ['required', 'integer'],
        ]);

        try {
            $slide = CarouselSlide::findOrFail($validated['slide_id']);

            DB::transaction(function () use ($slide, $validated): void {
                foreach ($validated['image_order'] as $order => $imageId) {
                    CarouselImage::where('id', $imageId)
                        ->where('carousel_slide_id', $slide->id)
                        ->update(['sort_order' => $order]);
                }
            });

            return response()->json([
                'message' => 'Images reordered successfully.',
                'success' => true,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error reordering images: ' . $e->getMessage(),
                'success' => false,
            ], 422);
        }
    }

    /**
     * Delete single carousel image (AJAX)
     */
    public function deleteImage(Request $request, CarouselImage $carouselImage): JsonResponse
    {
        $this->authorizeRole();

        try {
            $slide = $carouselImage->slide;

            DB::transaction(function () use ($carouselImage): void {
                $this->imageUploadService->delete($carouselImage->image_path);
                $carouselImage->delete();
            });

            // Update slide's primary image if needed
            $firstImage = $slide->images()->first();
            if ($firstImage) {
                $slide->update(['image_path' => $firstImage->image_path]);
            } else {
                $slide->update(['image_path' => null]);
            }

            return response()->json([
                'message' => 'Image deleted successfully.',
                'success' => true,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error deleting image: ' . $e->getMessage(),
                'success' => false,
            ], 422);
        }
    }

    private function authorizeRole(): void
    {
        abort_unless(auth()->check() && in_array(auth()->user()->role, ['admin', 'official'], true), 403);
    }

    private function routePrefix(): string
    {
        return (string) request()->segment(1);
    }

    private function routeName(string $suffix): string
    {
        return $this->routePrefix() . '.carousel-settings.' . $suffix;
    }
}
