@csrf

<div class="mb-3">
    <label class="form-label">Name</label>
    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
           value="{{ old('name', $destination->name ?? '') }}" required>
    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label class="form-label">Slug <span class="text-muted small">(kosongkan untuk auto-generate dari Name)</span></label>
    <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror"
           value="{{ old('slug', $destination->slug ?? '') }}">
    @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label class="form-label">Location</label>
    <input type="text" name="location" class="form-control"
           value="{{ old('location', $destination->location ?? '') }}">
</div>

<div class="mb-3">
    <label class="form-label">Description</label>
    <textarea name="description" rows="4" class="form-control">{{ old('description', $destination->description ?? '') }}</textarea>
</div>

<div class="mb-3">
    <label class="form-label">Hero Image</label>
    <input type="file" name="hero_image" class="form-control @error('hero_image') is-invalid @enderror">
    @error('hero_image') <div class="invalid-feedback">{{ $message }}</div> @enderror
    @if (!empty($destination->hero_image))
        <div class="form-text">Saat ini: {{ $destination->hero_image }}</div>
    @endif
</div>

<div class="form-check mb-3">
    <input type="checkbox" name="is_featured" value="1" class="form-check-input" id="is_featured"
           {{ old('is_featured', $destination->is_featured ?? false) ? 'checked' : '' }}>
    <label class="form-check-label" for="is_featured">Featured (tampil di Popular Destinations)</label>
</div>

<div class="mb-4">
    <label class="form-label">Status</label>
    <select name="status" class="form-select">
        <option value="draft" {{ old('status', $destination->status ?? 'draft') === 'draft' ? 'selected' : '' }}>Draft</option>
        <option value="published" {{ old('status', $destination->status ?? '') === 'published' ? 'selected' : '' }}>Published</option>
    </select>
</div>

<button type="submit" class="btn btn-primary">Save</button>
<a href="{{ route('admin.destinations.index') }}" class="btn btn-outline-secondary">Cancel</a>
