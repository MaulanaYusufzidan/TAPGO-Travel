@csrf

<div class="mb-3">
    <label class="form-label">Trip</label>
    <select name="trip_id" class="form-select @error('trip_id') is-invalid @enderror" required>
        <option value="">-- Pilih Trip --</option>
        @foreach ($trips as $t)
            <option value="{{ $t->id }}" {{ old('trip_id', $schedule->trip_id ?? '') == $t->id ? 'selected' : '' }}>{{ $t->title }}</option>
        @endforeach
    </select>
    @error('trip_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="row g-3 mb-3">
    <div class="col-md-4">
        <label class="form-label">Date</label>
        <input type="date" name="date" class="form-control @error('date') is-invalid @enderror"
               value="{{ old('date', isset($schedule) ? $schedule->date->format('Y-m-d') : '') }}" required>
        @error('date') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Departure Time</label>
        <input type="time" name="departure_time" class="form-control" value="{{ old('departure_time', $schedule->departure_time ?? '') }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Return Time</label>
        <input type="time" name="return_time" class="form-control" value="{{ old('return_time', $schedule->return_time ?? '') }}">
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-4">
        <label class="form-label">Capacity</label>
        <input type="number" name="capacity" min="1" class="form-control @error('capacity') is-invalid @enderror"
               value="{{ old('capacity', $schedule->capacity ?? '') }}" required>
        @error('capacity') <div class="invalid-feedback">{{ $message }}</div> @enderror
        @isset($schedule)
            <div class="form-text">Booked saat ini: {{ $schedule->booked_seats }} (tidak bisa diedit manual)</div>
        @endisset
    </div>
    <div class="col-md-4">
        <label class="form-label">Price (Rp)</label>
        <input type="number" name="price" min="0" step="1000" class="form-control @error('price') is-invalid @enderror"
               value="{{ old('price', $schedule->price ?? '') }}" required>
        @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
            @foreach (['available', 'full', 'closed', 'cancelled'] as $status)
                <option value="{{ $status }}" {{ old('status', $schedule->status ?? 'available') === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
            @endforeach
        </select>
    </div>
</div>

<button type="submit" class="btn btn-primary">Save</button>
<a href="{{ route('admin.schedules.index') }}" class="btn btn-outline-secondary">Cancel</a>
