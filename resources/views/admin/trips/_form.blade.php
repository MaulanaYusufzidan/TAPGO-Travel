@csrf

<div class="row g-3 mb-3">
    <div class="col-md-6">
        <label class="form-label">Destination</label>
        <select name="destination_id" class="form-select @error('destination_id') is-invalid @enderror" required>
            <option value="">-- Pilih Destinasi --</option>
            @foreach ($destinations as $d)
                <option value="{{ $d->id }}" {{ old('destination_id', $trip->destination_id ?? '') == $d->id ? 'selected' : '' }}>{{ $d->name }}</option>
            @endforeach
        </select>
        @error('destination_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Category</label>
        <select name="category_id" class="form-select">
            <option value="">-- Tanpa Kategori --</option>
            @foreach ($categories as $c)
                <option value="{{ $c->id }}" {{ old('category_id', $trip->category_id ?? '') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Title</label>
    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
           value="{{ old('title', $trip->title ?? '') }}" required>
    @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label class="form-label">Slug <span class="text-muted small">(kosongkan untuk auto-generate)</span></label>
    <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror"
           value="{{ old('slug', $trip->slug ?? '') }}">
    @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label class="form-label">Description</label>
    <textarea name="description" rows="3" class="form-control">{{ old('description', $trip->description ?? '') }}</textarea>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-4">
        <label class="form-label">Meeting Point</label>
        <input type="text" name="meeting_point" class="form-control" value="{{ old('meeting_point', $trip->meeting_point ?? '') }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Duration</label>
        <input type="text" name="duration" class="form-control" placeholder="ex: 3D2N" value="{{ old('duration', $trip->duration ?? '') }}">
    </div>
    <div class="col-md-2">
        <label class="form-label">Min Group</label>
        <input type="number" name="min_group_size" min="1" class="form-control" value="{{ old('min_group_size', $trip->min_group_size ?? '') }}">
    </div>
    <div class="col-md-2">
        <label class="form-label">Max Group</label>
        <input type="number" name="max_group_size" min="1" class="form-control @error('max_group_size') is-invalid @enderror" value="{{ old('max_group_size', $trip->max_group_size ?? '') }}">
        @error('max_group_size') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Base Price (Rp)</label>
    <input type="number" name="base_price" min="0" step="1000" class="form-control @error('base_price') is-invalid @enderror"
           value="{{ old('base_price', $trip->base_price ?? '') }}" required>
    @error('base_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="form-check mb-3">
    <input type="checkbox" name="is_featured" value="1" class="form-check-input" id="is_featured"
           {{ old('is_featured', $trip->is_featured ?? false) ? 'checked' : '' }}>
    <label class="form-check-label" for="is_featured">Featured</label>
</div>

<div class="mb-4">
    <label class="form-label">Status</label>
    <select name="status" class="form-select">
        <option value="draft" {{ old('status', $trip->status ?? 'draft') === 'draft' ? 'selected' : '' }}>Draft</option>
        <option value="published" {{ old('status', $trip->status ?? '') === 'published' ? 'selected' : '' }}>Published</option>
    </select>
</div>

<button type="submit" class="btn btn-primary">Save</button>
<a href="{{ route('admin.trips.index') }}" class="btn btn-outline-secondary">Cancel</a>
