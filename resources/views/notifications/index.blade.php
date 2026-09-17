@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<style>

    .pagination {
    display: flex;
    gap: 6px;                    
    align-items: center;
    justify-content: center;
    flex-wrap: wrap;
    }

    .pagination .page-item .page-link 
    border-radius: 8px !important;
        min-width: 36px;
        text-align: center;{
        border-radius: 8px !important;
        min-width: 36px;
        text-align: center;
        border: 1px solid #dee2e6;
        color: #495057;
        padding: 0.4rem 0.75rem;
        transition: all 0.2s ease;
    }

    .pagination .page-item.active .page-link {
        background-color: #0d6efd;
        border-color: #0d6efd;
        color: #fff;
    }

    .pagination .page-link:hover {
        background-color: #e9ecef;
        color: #0d6efd;
    }

    .pagination .page-item.disabled .page-link {
        color: #adb5bd;
        background-color: #f8f9fa;
    }
</style>

<div class="container-fluid pt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">All Notifications</h1>

        @if(auth()->user()->unreadNotifications->count() > 0)
            <form action="{{ route('notifications.read-all') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-check2-all"></i> Mark All as Read
                </button>
            </form>
        @endif
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            @forelse($notifications as $notification)
                <div class="border-bottom p-3 {{ $notification->read_at ? '' : 'bg-light' }}">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <h6 class="mb-1">
                                {{ $notification->data['title'] ?? 'Notification' }}
                                @if(!$notification->read_at)
                                    <span class="badge bg-danger ms-1">New</span>
                                @endif
                            </h6>
                            <p class="mb-1 text-muted small">
                                {{ $notification->data['message'] ?? '' }}
                            </p>
                            <small class="text-muted">
                                {{ $notification->created_at->format('d M Y, H:i') }} 
                                ({{ $notification->created_at->diffForHumans() }})
                            </small>
                        </div>

                        <div class="d-flex gap-2">
                            @if(isset($notification->data['url']))
                                <a href="{{ $notification->data['url'] }}" class="btn btn-sm btn-outline-primary">
                                    View
                                </a>
                            @endif

                            @if(!$notification->read_at)
                                <form action="{{ route('notifications.read', $notification->id) }}" method="get">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success">
                                        Mark Read
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-bell-slash fs-1"></i>
                    <p class="mt-3">No notifications found.</p>
                </div>
            @endforelse
        </div>
    </div>

    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2 mt-4">
        <div class="text-muted small">
            Showing {{ $notifications->firstItem() ?? 0 }}
            to {{ $notifications->lastItem() ?? 0 }}
            of {{ $notifications->total() }} results
        </div>

        <div>
            {{ $notifications->onEachSide(1)->links() }}
        </div>
    </div>
</div>
@endsection