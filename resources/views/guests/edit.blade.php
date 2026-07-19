@extends('layouts.app')

@section('title', 'Edit Guest')

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h5>Edit Guest: {{ $guest->full_name }}</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('guests.update', $guest) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="full_name" value="{{ $guest->full_name }}" class="form-control @error('full_name') is-invalid @enderror" required>
                    @error('full_name') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Phone Number</label>
                    <input type="text" name="phone" value="{{ $guest->phone }}" class="form-control @error('phone') is-invalid @enderror" required>
                    @error('phone') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">ID Number</label>
                    <input type="text" name="id_number" value="{{ $guest->id_number }}" class="form-control @error('id_number') is-invalid @enderror" required>
                    @error('id_number') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" value="{{ $guest->email }}" class="form-control @error('email') is-invalid @enderror">
                    @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Address</label>
                <textarea name="address" class="form-control" rows="2">{{ $guest->address }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Country</label>
                <input type="text" name="country" value="{{ $guest->country }}" class="form-control">
            </div>

            <button type="submit" class="btn btn-primary">Update Guest</button>
            <a href="{{ route('guests.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection