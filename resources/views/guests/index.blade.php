@extends('layouts.app')

@section('title', 'Guests')

@section('content')
<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5>Guests List</h5>
        <a href="{{ route('guests.create') }}" class="btn btn-primary btn-sm">Add New Guest</a>
    </div>
    <div class="card-body">
        <table class="table table-striped">
            <thead>
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
                    <td>{{ $guest->country }}</td>
                    <td>
                        <a href="{{ route('guests.edit', $guest) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('guests.destroy', $guest) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this guest?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection