@extends('layouts.app')

@section('title', 'Edit Room Type')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3">
            <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Edit Room Type: {{ $roomType->type_name }}</h5>
            <a href="{{ route('room-types.index') }}" class="btn btn-light btn-sm text-primary font-weight-bold">
                <i class="fas fa-arrow-left"></i> Back to Room Types
            </a>
        </div>
        
        <div class="card-body">
            <!-- Error handling -->
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('room-types.update', $roomType->id) }}" method="POST" enctype="multipart/form-data" onsubmit="triggerSaveSettings(event)">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <!-- Left Side: Basic Details -->
                    <div class="col-lg-6">
                        <h5 class="mb-3 text-primary border-bottom pb-2">Basic Details</h5>
                        
                        <div class="mb-3">
                            <label class="form-label font-weight-bold">Type Name</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $roomType->name) }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label font-weight-bold">Price per night (TZS)</label>
                            <input type="number" name="price_per_night" class="form-control" value="{{ old('price_per_night', $roomType->price_per_night) }}" required>
                        </div>
                        
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label font-weight-bold">Watu Wazima Max</label>
                                <input type="number" name="max_adults" class="form-control" value="{{ old('max_adults', $roomType->max_adults) }}" required>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label font-weight-bold">Watoto Max</label>
                                <input type="number" name="max_children" class="form-control" value="{{ old('max_children', $roomType->max_children) }}">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label font-weight-bold">Description</label>
                            <textarea name="description" class="form-control" rows="6">{{ old('description', $roomType->description) }}</textarea>
                        </div>
                    </div>

                    <!-- Left Side: Room Image and Gallery -->
                    <div class="col-lg-6">
                        <h5 class="mb-3 text-primary border-bottom pb-2">Room Images</h5>
                        
                        <div class="mb-4">
                            <label class="form-label font-weight-bold">Main Image</label>
                            <div class="text-center p-2 border rounded bg-light">
                                @if($roomType->image)
                                    <img src="{{ asset('storage/' . $roomType->image) }}" class="img-fluid rounded shadow-sm" style="max-height: 220px; object-fit: cover;">
                                @else
                                    <p class="text-muted my-4">No main image</p>
                                @endif
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label font-weight-bold">Change Main Image</label>
                            <input type="file" name="main_image" class="form-control" accept="image/*">
                        </div>

                        <div class="mb-4">
                            <label class="form-label font-weight-bold">Add additional images (Gallery)</label>
                            <input type="file" name="gallery[]" class="form-control" multiple accept="image/*">
                            <small class="text-muted d-block mt-1">You can select multiple images at once (max 10)</small>
                        </div>

                        <!-- All images existing in gallery -->
                        @if($roomType->images && $roomType->images->count() > 0)
                        <div class="mt-4">
                            <label class="form-label font-weight-bold text-secondary">All images in the gallery ({{ $roomType->images->count() }})</label>
                            <div class="row g-3">
                                @foreach($roomType->images as $img)
                                    <div class="col-md-4 col-sm-6 col-12">
                                        <div class="position-relative overflow-hidden rounded-3 shadow-sm border" style="height: 160px; width: 100%;">
                                            <img src="{{ asset('storage/' . $img->image_path) }}" 
                                                style="height: 100%; width: 100%; object-fit: cover;" 
                                                alt="Gallery Image">
                                            @if($img->is_primary)
                                                <span class="badge bg-success position-absolute top-0 start-0 m-2 shadow">Primary</span>
                                            @endif

                                            <div class="position-absolute top-0 end-0 h-100 d-flex flex-column justify-content-between align-items-center p-2 gap-2" style="z-index: 10;">
                                                <form id="delete-gallery-form-{{ $img->id }}" action="{{ route('room-types.gallery.destroy', $img->id) }}" method="POST" class="m-0 border-0 p-0">
                                                    @csrf
                                                    @method('DELETE')
                                                    
                                                    @if($img->is_primary)
                                                        <!-- Primary image  -->
                                                        <div class="d-inline-block" title="You cannot delete this image as it used as a primary image!">
                                                            <button type="button" class="btn btn-secondary btn-sm d-flex align-items-center justify-content-center opacity-50 shadow-none" 
                                                                    style="width: 34px; height: 34px; border-radius: 6px; pointer-events: none; cursor: not-allowed;">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </button>
                                                        </div>
                                                    @else
                                                        <!-- Additional image -->
                                                        <button type="button" class="btn btn-danger btn-sm d-flex align-items-center justify-content-center shadow" 
                                                                style="width: 34px; height: 34px; border-radius: 6px;" 
                                                                onclick="if(confirm('Delete this image from gallery?')) { document.getElementById('delete-gallery-form-{{ $img->id }}').submit(); }"
                                                                title="Delete this image">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    @endif
                                                </form>

                                                @if(!$img->is_primary)
                                                    <a href="{{ route('room-types.gallery.primary', $img->id) }}" 
                                                    class="btn btn-success btn-sm d-flex align-items-center justify-content-center shadow" style="width: 34px; height: 34px; border-radius: 6px;"
                                                    title="Set as primary image">
                                                        <i class="fas fa-star"></i>
                                                    </a>
                                                @else
                                                    <button class="btn btn-success btn-sm d-flex align-items-center justify-content-center shadow border border-white" style="width: 34px; height: 34px; border-radius: 6px;" disabled title="Active Primary">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                @endif
                                                
                                            </div>

                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="text-end mt-5 border-top pt-3">
                    <button type="submit" class="btn btn-warning btn-lg px-5 shadow text-dark font-weight-bold">
                        <i class="fas fa-save me-2"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')

<script>
    // Triggering Save setting form
    function triggerSaveSettings(event) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Updating room type...',
                text: 'Please wait while a room type is being updated.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        }
    }
</script>
    
@endsection
