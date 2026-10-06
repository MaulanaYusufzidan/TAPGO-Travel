@php
    $navGroups = [
        'OVERVIEW' => [
            ['route' => 'admin.dashboard', 'match' => 'admin.dashboard', 'label' => 'Dashboard'],
        ],
        'MANAGEMENT' => [
            ['route' => 'admin.bookings.index', 'match' => 'admin.bookings.*', 'label' => 'Bookings'],
            ['route' => 'admin.hotel-bookings.index', 'match' => 'admin.hotel-bookings.*', 'label' => 'Hotel Bookings'],
            ['route' => 'admin.trips.index', 'match' => 'admin.trips.*', 'label' => 'Trips'],
            ['route' => 'admin.destinations.index', 'match' => 'admin.destinations.*', 'label' => 'Destinations'],
            ['route' => 'admin.schedules.index', 'match' => 'admin.schedules.*', 'label' => 'Schedules'],
            ['route' => 'admin.customers.index', 'match' => 'admin.customers.*', 'label' => 'Customers'],
            ['route' => 'admin.reviews.index', 'match' => 'admin.reviews.*', 'label' => 'Reviews'],
            ['route' => 'admin.payments.index', 'match' => 'admin.payments.*', 'label' => 'Payments'],
        ],
    ];
@endphp
<p class="admin-sidebar__brand">TAPGO<span>Travel Admin</span></p>
@foreach($navGroups as $group => $items)
    <p class="admin-sidebar__group">{{ $group }}</p>
    <ul class="admin-sidebar__nav">
        @foreach($items as $item)
            <li>
                <a href="{{ route($item['route']) }}" class="{{ request()->routeIs($item['match']) ? 'active' : '' }}">
                    {{ $item['label'] }}
                </a>
            </li>
        @endforeach
    </ul>
@endforeach
<p class="admin-sidebar__group">SYSTEM</p>
<ul class="admin-sidebar__nav">
    <li><a href="{{ route('admin.settings.edit') }}" class="{{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">Settings</a></li>
</ul>
<a href="{{ url('/') }}" class="admin-sidebar__exit">&larr; Back to site</a>
