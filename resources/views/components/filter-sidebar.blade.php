@props(['amenities' => collect(), 'hotelTypes' => collect(), 'filters' => []])
@php
    $selectedAmenities = array_map('intval', $filters['amenities'] ?? []);
    $minRating = $filters['min_rating'] ?? '';
@endphp
<aside class="filter-sidebar">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <strong>Filter by</strong>
        <a href="{{ route('hotels.index') }}" class="btn btn-link btn-sm p-0">Clear all</a>
    </div>

    <div class="filter-group">
        <h3>Price range (per night)</h3>
        <div class="d-flex gap-2">
            <input type="number" min="0" name="min_price" class="form-control form-control-sm" placeholder="Min" value="{{ $filters['min_price'] ?? '' }}">
            <input type="number" min="0" name="max_price" class="form-control form-control-sm" placeholder="Max" value="{{ $filters['max_price'] ?? '' }}">
        </div>
    </div>

    <div class="filter-group">
        <h3>Guest rating</h3>
        <label><input type="radio" name="min_rating" value="" @checked($minRating === '')> Any rating</label>
        @foreach([9 => '9+ Exceptional', 8 => '8+ Excellent', 7 => '7+ Very good'] as $value => $label)
            <label><input type="radio" name="min_rating" value="{{ $value }}" @checked((string) $minRating === (string) $value)> {{ $label }}</label>
        @endforeach
    </div>

    <div class="filter-group">
        <h3>Property type</h3>
        <select name="hotel_type" class="form-select form-select-sm">
            <option value="">All types</option>
            @foreach($hotelTypes as $type)
                <option value="{{ $type }}" @selected(($filters['hotel_type'] ?? '') === $type)>{{ $type }}</option>
            @endforeach
        </select>
    </div>

    <div class="filter-group">
        <h3>Popular filters</h3>
        <label><input type="checkbox" name="breakfast" value="1" @checked(!empty($filters['breakfast']))> Breakfast included</label>
        <label><input type="checkbox" name="free_cancellation" value="1" @checked(!empty($filters['free_cancellation']))> Free cancellation</label>
    </div>

    <div class="filter-group">
        <h3>Amenities</h3>
        @foreach($amenities as $amenity)
            <label><input type="checkbox" name="amenities[]" value="{{ $amenity->id }}" @checked(in_array($amenity->id, $selectedAmenities, true))> {{ $amenity->name }}</label>
        @endforeach
    </div>

    <button type="submit" class="btn btn-primary btn-sm w-100 mt-2">Apply filters</button>
</aside>
