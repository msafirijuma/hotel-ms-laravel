@extends('layouts.app')

@section('title', 'Assign Housekeeping Task')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header bg-primary text-white py-3">
            <h5 class="mb-0 fw-bold"><i class="fas fa-broom me-2"></i>Assign Housekeeping Task</h5>
        </div>
        
        <div class="card-body p-4">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form id="housekeepingForm" method="POST">
                @csrf
                
                <!-- Choose Dirty Room -->
                <div class="mb-4">
                    <label class="form-label fw-bold text-dark">Choose Dirty Room <span class="text-danger">*</span></label>
                    <select name="room_id" class="form-select searchable-dropdown" required>
                        <option value="">Choose a dirty room ...</option>
                        @foreach($dirty_rooms as $room)
                            <option value="{{ $room->id }}">
                                No. {{ $room->room_number }} — {{ $room->roomType->name ?? 'Room' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Choose Housekeeper -->
                <div class="mb-4">
                    <label class="form-label fw-bold text-dark">Choose Housekeeper <span class="text-danger">*</span></label>
                    <p class="small text-muted mb-2">
                        <span class="badge bg-success me-2">Current Shift ({{ ucfirst($current_shift) }})</span>
                        current shift housekeepers are highlighted in <span class="fw-bold text-success">green and bold</span> in the dropdown.
                    </p>
                    <select name="assigned_to" class="form-select searchable-dropdown">
                        <option value="">Choose housekeeper...</option>
                        @foreach($housekeepers as $staff)
                            <!-- check if housekeeper belongs to the current shift -->
                            @php
                                $isCurrentShift = str_contains(strtolower($staff->shift), strtolower($current_shift))
                            @endphp
                            <option value="{{ $staff->id }}" class="badge bg-{{ $isCurrentShift ? 'success' : 'secondary' }}">
                                {{ $staff->name }} [{{ ucfirst($staff->shift) }}] — Pending: {{ $staff->pending_tasks }}, In Progress: {{ $staff->in_progress }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Additional Notes -->
                <div class="mb-4">
                    <label class="form-label fw-bold text-dark">Additional Notes (optional)</label>
                    <textarea name="notes" class="form-control" rows="3" placeholder="Example: VIP - balcony very clean"></textarea>
                </div>

                <!-- Auto assign & Manual assign buttons -->
                <div class="d-flex gap-3 justify-content-end border-top pt-3">
                    <button type="button" onclick="submitForm('{{ route('housekeeping.assign.manual') }}')" class="btn btn-primary btn-lg px-5 fw-bold shadow">
                        <i class="fas fa-check me-2"></i> Assign Manually
                    </button>
                    <button type="button" onclick="submitForm('{{ route('housekeeping.assign.auto') }}')" class="btn btn-info btn-lg px-5 fw-bold text-white shadow">
                        <i class="fas fa-robot me-2"></i> Auto-Assign (Least Load)
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')

<script>
$(document).ready(function() {
    $('.searchable-dropdown').select2({
        placeholder: "Tafuta hapa...",
        allowClear: true,
        theme: "bootstrap-5",
        width: '100%',
        minimumInputLength: 0,
        templateResult: function(data) {
            if (!data.id || !data.element) {
                return data.text;
            }
            
            let $option = $('<span>' + data.text + '</span>');
            let isActive = $(data.element).attr('data-active') || $(data.element).data('active');
            
            if (isActive === true || isActive === 'true') {
                $option.css('font-weight', 'bold')
                       .css('color', '#198754'); 
            }
            
            return $option;
        }
    });
});


// Change form to either manual or auto assign based on which button is clicked
function submitForm(actionUrl) {
    const form = document.getElementById('housekeepingForm');
    const housekeeperSelect = form.querySelector('[name="assigned_to"]');
    
    if (actionUrl.includes('auto')) {
        housekeeperSelect.removeAttribute('required');
    } else {
        housekeeperSelect.setAttribute('required', 'required');
    }
    
    form.action = actionUrl;
    form.submit();
}
</script>
@endsection
