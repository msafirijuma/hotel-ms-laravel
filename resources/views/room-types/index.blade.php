@extends('layouts.app')

@section('title', 'Room Types')

@section('content')
<div class="container-fluid">
    
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white p-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-bed me-2"></i>Room Types Management
            </h5>
            <a href="{{ route('room-types.create') }}" class="btn btn-light btn-sm text-primary font-weight-bold">
                <i class="fas fa-plus"></i> Add New Type
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered align-middle" id="roomTypesTable" style="width:100%">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Main Image</th>
                            <th>Type</th>
                            <th>Price per night</th>
                            <th>Adult / Children</th>
                            <th>Details</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($roomTypes as $type)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    @if($type->image)
                                        <img src="{{ asset('storage/' . $type->image) }}" 
                                             class="img-thumbnail rounded shadow-sm" 
                                             style="width: 140px; height: 100px; object-fit: cover;">
                                    @else
                                        <div class="bg-light border rounded d-flex align-items-center justify-content-center text-muted" 
                                             style="width: 120px; height: 80px;">
                                            <small>No image</small>
                                        </div>
                                    @endif
                                </td>
                                <td><strong>{{ $type->name }}</strong></td>
                                <td class="text-success fw-bold">TZS {{ number_format($type->price_per_night, 0) }}</td>
                                <td>{{ $type->max_adults }} / {{ $type->max_children }}</td>
                                <td class="text-muted"><small>{{ Str::limit($type->description, 80) }}</small></td>
                                <td class="text-center">
                                    <div class="d-flex gap-1 justify-content-center">
                                        <!-- Gallery Modal Button 100px-->
                                        <button type="button" class="btn btn-sm btn-info text-white py-1" 
                                                data-bs-toggle="modal" data-bs-target="#galleryModal{{ $type->id }}" 
                                                title="View Gallery">
                                            <i class="fas fa-images"></i>
                                        </button>
        
                                        <!-- 2. EDIT BUTTON -->
                                        <button type="button" onclick="triggerEdit('{{ route('room-types.edit', $type->id) }}')" class="btn btn-sm btn-warning text-dark py-1" title="Edit Room Type">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        <!-- 3. SECURE DELETE FORM (Hidden) -->
                                        <form id="delete-type-form-{{ $type->id }}" action="{{ route('room-types.destroy', $type->id) }}" method="POST" class="d-none">
                                            @csrf
                                            @method('DELETE')
                                        </form>

                                        <!-- 4. DELETE BUTTON -->
                                        <button type="button" onclick="triggerDelete({{ $type->id }}, '{{ addslashes($type->name) }}')" class="btn btn-sm btn-danger py-1" title="Delete Category">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Gallery Modal -->
                            <div class="modal fade" id="galleryModal{{ $type->id }}" tabindex="-1" aria-labelledby="galleryModalLabel{{ $type->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered"> 
                                    <div class="modal-content border-0 shadow-lg">
                                        
                                        <!-- Modal Header  -->
                                        <div class="modal-header bg-light">
                                            <h5 class="modal-title" id="galleryModalLabel{{ $type->id }}">
                                                <i class="fas fa-images text-primary me-2"></i>Gallery: {{ $type->name }}
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        
                                        <!-- Modal Body -->
                                        <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                                            @if($type->images && $type->images->count() > 0)
                                                <div class="row g-3">
                                                    @foreach($type->images as $img)
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
                                                                            <!-- show title for this delete button -->
                                                                            <div class="d-inline-block" title="Huwezi kufuta picha kuu (Primary Image). Badilisha picha nyingine kuwa Primary kwanza ili uweze kuifuta hii!">
                                                                                <button type="button" class="btn btn-secondary btn-sm d-flex align-items-center justify-content-center opacity-50 shadow-none" 
                                                                                        style="width: 34px; height: 34px; border-radius: 6px; pointer-events: none; cursor: not-allowed;">
                                                                                    <i class="fas fa-trash-alt"></i>
                                                                                </button>
                                                                            </div>
                                                                        @else
                                                                            <!-- show title for this delete button -->
                                                                            <button type="button" class="btn btn-danger btn-sm d-flex align-items-center justify-content-center shadow" 
                                                                                    style="width: 34px; height: 34px; border-radius: 6px;" 
                                                                                    onclick="if(confirm('Futa picha hii ya gallery?')) { document.getElementById('delete-gallery-form-{{ $img->id }}').submit(); }"
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
                                            @else
                                                <div class="text-center py-5">
                                                    <i class="fas fa-images text-muted fa-3x mb-3"></i>
                                                    <p class="text-muted">No additional images (gallery) for this room type.</p>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <!-- Modal Footer -->
                                        <div class="modal-footer bg-light">
                                            <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Close</button>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    // Loader function during page navigation
    function showPageLoader(message) {
        Swal.fire({
            title: 'Please wait...',
            text: message,
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
    }

    // View Action Loading
    function triggerView(url) {
        showPageLoader('Opening room type details...');
        window.location.href = url;
    }

    // Edit Action Loading
    function triggerEdit(url) {
        showPageLoader('Loading room types layout...');
        window.location.href = url;
    }

    // Delete Action Loading
    function triggerDelete(id, typeName) {
        Swal.fire({
            title: 'Delete this category?',
            text: `You will completely delete "${typeName}" and all associated structural parameters from the system!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-trash"></i> Yes, Delete Category!',
            cancelButtonText: 'Cancel',
            allowOutsideClick: false,
            customClass: {
                confirmButton: 'btn btn-danger btn-lg px-4 me-2 fw-bold shadow-sm',
                cancelButton: 'btn btn-secondary btn-lg px-4 fw-bold shadow-sm'
            },
            buttonsStyling: false
        }).then((result) => {
            // Loader during the deletion process
            if (result.isConfirmed) {
                showPageLoader('Deleting Room Type from system database...');
                document.getElementById('delete-type-form-' + id).submit();
            }
        });
    }
</script>
@endsection

