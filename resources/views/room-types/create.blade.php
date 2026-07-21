@extends('layouts.app')

@section('title', 'Add New Room Type')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-primary text-white p-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 text-light">Add New Room Type</h5>
        <a href="{{ route('rooms.index') }}" class="btn btn-light btn-sm text-primary font-weight-bold">
            <i class="fas fa-arrow-left"></i> Back to Room Types
        </a>
    </div>
    <div class="card-body">
        <form action="{{ route('room-types.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row g-4">
                        <!-- Left Column -->
                        <div class="col-lg-6">
                            <h5 class="mb-3 text-primary">Basic Information</h5>
                            <div class="mb-3">
                                <label class="form-label">Type Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" value="<?= $_POST['name'] ?? '' ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Price Per Night (TZS) <span class="text-danger">*</span></label>
                                <input type="number" name="price_per_night" class="form-control" min="1000" step="1000" value="<?= $_POST['price_per_night'] ?? '' ?>" required>
                            </div>
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <label class="form-label">Max Adults <span class="text-danger">*</span></label>
                                    <input type="number" name="max_adults" class="form-control" min="1" value="<?= $_POST['max_adults'] ?? '2' ?>" required>
                                </div>
                                <div class="col-6 mb-3">
                                    <label class="form-label">Max Children</label>
                                    <input type="number" name="max_children" class="form-control" min="0" value="<?= $_POST['max_children'] ?? '0' ?>">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control" rows="5"><?= $_POST['description'] ?? '' ?></textarea>
                            </div>
                        </div>

                        <!-- Right Column - Images -->
                        <div class="col-lg-6">
                            <h5 class="mb-3 text-primary">Room Images</h5>
                            <div class="mb-4">
                                <label class="form-label">Main Image (Primary)</label>
                                <input type="file" name="main_image" class="form-control" accept="image/*">
                                <small class="text-muted">Appears in main list • JPG, PNG, WebP • Max 5MB</small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Additional Image (Gallery)</label>
                                <input type="file" name="gallery[]" class="form-control" multiple accept="image/*">
                                <small class="text-muted">Additional images for gallery view • JPG, PNG, WebP • Max 5MB each</small>
                            </div>
                        </div>
                    </div>

                    <div class="text-end mt-5">
                        <button type="submit" class="btn btn-success btn-lg px-5 shadow">
                            <i class="fas fa-save me-2"></i> Save Room Type
                        </button>
                    </div>
        </form>
    </div>
</div>
@endsection