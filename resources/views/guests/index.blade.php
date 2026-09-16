@extends('layouts.app')

@section('title', 'Guests')

@section('content')
<div class="card shadow-sm">

    <div class="card-header bg-primary text-white p-3 d-flex justify-content-between align-items-center">
        <h5>
            <i class="fas fa-user-friends me-2"></i>Guests List
        </h5>
        <a href="{{ route('guests.create') }}" class="btn btn-light btn-sm text-primary font-weight-bold">
            <i class="fas fa-plus"></i> Add New Guest
        </a>
    </div>
    
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" id="usersTable">
                <thead class="table-dark">
                    <tr>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>ID Number</th>
                        <th>Country</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($guests as $guest)
                    <tr>
                        <td>{{ $guest->full_name }}</td>
                        <td>{{ $guest->phone }}</td>
                        <td>{{ $guest->id_number }}</td>
                        <td>{{ $guest->country ?? '—' }}</td>
                        <td>
                            <!-- VIEW BUTTON-->
                            <button type="button" onclick="triggerView('{{ route('guests.show', $guest) }}')" class="btn btn-sm btn-info text-white py-1" title="View guest Details">
                                <i class="fas fa-eye"></i>
                            </button>

                            <!-- EDIT BUTTON-->
                            <button type="button" onclick="triggerEdit('{{ route('guests.edit', $guest) }}')" class="btn btn-sm btn-warning py-1" title="Edit guest Info">
                                <i class="fas fa-edit"></i>
                            </button>

                            <!-- Laravel Delete Form Component-->
                            <form id="delete-guest-form-{{ $guest->id }}" action="{{ route('guests.destroy', $guest->id) }}" method="POST" class="d-none">
                                @csrf
                                @method('DELETE')
                            </form>

                            <!-- DELETE BUTTON-->
                            <button type="button" onclick="triggerDelete({{ $guest->id }}, '{{ $guest->full_name }}')" class="btn btn-sm btn-danger py-1" title="Delete Guest">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
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
        showPageLoader('We are opening guest profile...');
        window.location.href = url;
    }

    // Edit Action Loading
    function triggerEdit(url) {
        showPageLoader('We are preparing guest update form...');
        window.location.href = url;
    }

    // SweetAlert Delete Action
    function triggerDelete(id, guestName) {
        Swal.fire({
            title: 'Are you sure you want to delete?',
            text: `You will permanently remove ${guestName} from the hotel system!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545', 
            cancelButtonColor: '#6c757d',  
            confirmButtonText: '<i class="fas fa-trash"></i> Yes, Delete!',
            cancelButtonText: 'No, Cancel',
            allowOutsideClick: false,
            customClass: {
                confirmButton: 'btn btn-danger btn-lg px-4 me-2 fw-bold shadow-sm',
                cancelButton: 'btn btn-secondary btn-lg px-4 fw-bold shadow-sm'
            },
            buttonsStyling: false 
        }).then((result) => {
            if (result.isConfirmed) {
                // Loader during the deletion process
                Swal.fire({
                    title: '<span class="text-danger"><i class="fas fa-trash me-2"></i>Deleting...</span>',
                    text: 'Please wait while guest is being deleted from the database.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // submit form of selected guest
                document.getElementById('delete-guest-form-' + id).submit();
            }
        });
    }
</script>
@endsection