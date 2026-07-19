@extends('layouts.app')

@section('title', 'Users & Staff')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-primary text-white p-3 d-flex justify-content-between align-items-center">
        <h5><i class="fas fa-users-cog"></i> Staff Management</h5>
        <a href="{{ route('users.create') }}" class="btn btn-light btn-sm text-primary font-weight-bold">
            <i class="fas fa-plus"></i> Add New User
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover table-bordered align-middle" id="usersTable">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Last Login</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><strong>{{ $user->name }}</strong></td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @foreach($user->roles as $role)
                                <span class="badge bg-{{ $role->name == 'admin' ? 'danger' : ($role->name == 'receptionist' ? 'primary' : 'info') }}">
                                    {{ ucfirst($role->name) }}
                                </span>
                            @endforeach
                        </td>
                        <td>
                            {{ $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') : '---' }}
                        </td>
                        <td class="text-center">
                            <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-secondary" title="View User">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-warning" title="Edit User">
                                <i class="fas fa-edit"></i>
                            </a>
                            @if($user->id != auth()->id())
                                <button type="button" class="btn btn-sm btn-danger"  title="Delete User"
                                        data-bs-toggle="modal" data-bs-target="#deleteModal{{ $user->id }}">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            @else
                                <button class="btn btn-sm btn-secondary" disabled title="Cannot delete yourself">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            @endif
                        </td>
                    </tr>

                    <!-- Delete Modal -->
                    <div class="modal fade" id="deleteModal{{ $user->id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header bg-danger text-white">
                                    <h5 class="modal-title">Confirm Delete</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body text-center">
                                    <p>Are you sure you want to delete <strong>{{ $user->name }}</strong>?</p>
                                    <p class="text-muted small">Email: {{ $user->email }}</p>
                                </div>
                                <div class="modal-footer justify-content-center">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Delete User</button>
                                    </form>
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
@endsection