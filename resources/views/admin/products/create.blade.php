@extends('layouts.admin')

@section('content')
    <div class="mb-5">
        <h1 class="h2 text-dark fw-bold mb-1">Tambah Produk Baru</h1>
        <p class="text-secondary font-monospace" style="font-size: 0.8rem;">Unggah artikel baju thrift premium baru ke dalam katalog.</p>
    </div>

    <!-- Error Messages (General Validation) -->
    @if ($errors->any())
        <div class="alert alert-danger border-0 bg-white border-start border-danger border-4 rounded-3 shadow-sm py-3 px-4 mb-4" role="alert">
            <h5 class="h6 fw-bold text-danger"><i class="bi bi-exclamation-triangle-fill"></i> Ada beberapa kesalahan input:</h5>
            <ul class="mb-0 font-monospace" style="font-size: 0.8rem;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card-admin shadow-sm rounded-3 border-0">
        <div class="card-header bg-white border-bottom border-light-subtle rounded-top-3 fw-bold">Form Data Produk</div>
        <div class="card-body">
            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="row g-4">
                    <!-- Name -->
                    <div class="col-md-6">
                        <label for="name" class="form-label fw-medium text-dark">Nama Produk <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required placeholder="Contoh: Vintage Wool Sweater">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Category -->
                    <div class="col-md-6">
                        <label for="category_id" class="form-label fw-medium text-dark">Kategori <span class="text-danger">*</span></label>
                        <select name="category_id" id="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Price -->
                    <div class="col-md-4">
                        <label for="price" class="form-label fw-medium text-dark">Harga (Rupiah) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-secondary font-monospace" style="font-size: 0.85rem;">Rp</span>
                            <input type="number" name="price" id="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}" required min="0" placeholder="150000">
                        </div>
                        @error('price')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Size -->
                    <div class="col-md-4">
                        <label for="size" class="form-label fw-medium text-dark">Ukuran (Size) <span class="text-danger">*</span></label>
                        <input type="text" name="size" id="size" class="form-control @error('size') is-invalid @enderror" value="{{ old('size') }}" required placeholder="Contoh: XL, L, 32, Oversize">
                        @error('size')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Condition -->
                    <div class="col-md-4">
                        <label for="condition" class="form-label fw-medium text-dark">Kondisi Barang <span class="text-danger">*</span></label>
                        <select name="condition" id="condition" class="form-select @error('condition') is-invalid @enderror" required>
                            <option value="Like New (9.5/10)" {{ old('condition') == 'Like New (9.5/10)' ? 'selected' : '' }}>Like New (9.5/10)</option>
                            <option value="Very Good (9/10)" {{ old('condition') == 'Very Good (9/10)' || !old('condition') ? 'selected' : '' }}>Very Good (9/10)</option>
                            <option value="Good (8.5/10)" {{ old('condition') == 'Good (8.5/10)' ? 'selected' : '' }}>Good (8.5/10)</option>
                            <option value="Fair Condition" {{ old('condition') == 'Fair Condition' ? 'selected' : '' }}>Fair Condition</option>
                        </select>
                        @error('condition')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Brand -->
                    <div class="col-md-6">
                        <label for="brand" class="form-label fw-medium text-dark">Brand / Merek (Opsional)</label>
                        <input type="text" name="brand" id="brand" class="form-control @error('brand') is-invalid @enderror" value="{{ old('brand') }}" placeholder="Contoh: Carhartt, Nike, Levi's">
                        @error('brand')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="col-md-6">
                        <label for="status" class="form-label fw-medium text-dark">Status Ketersediaan <span class="text-danger">*</span></label>
                        <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="available" {{ old('status') == 'available' || !old('status') ? 'selected' : '' }}>Tersedia (Ready Stock)</option>
                            <option value="sold_out" {{ old('status') == 'sold_out' ? 'selected' : '' }}>Sold Out (Terjual)</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Multi-Image Upload Area -->
                    <div class="col-12">
                        <label class="form-label fw-medium text-dark d-block">Foto Produk (Maksimal 10 Foto) <span class="text-danger">*</span></label>
                        <p class="text-muted font-monospace mb-2" style="font-size: 0.75rem;">
                            Foto pertama yang diunggah akan otomatis menjadi <strong>Foto Sampul (Utama)</strong>. Ukuran maks 5MB/foto.
                        </p>
                        
                        <!-- File Input (Hidden, triggered via button) -->
                        <input type="file" id="imageInput" name="images[]" multiple accept="image/*" class="d-none">

                        <!-- Preview Container -->
                        <div class="border border-light-subtle rounded-3 p-3 bg-light" id="previewWrapper">
                            <div class="d-flex flex-wrap gap-3 align-items-center" id="imagePreviewsContainer">
                                <!-- Previews will be injected by JavaScript -->
                            </div>
                        </div>

                        @error('images')
                            <div class="text-danger font-monospace mt-1" style="font-size: 0.75rem;">{{ $message }}</div>
                        @enderror
                        @error('images.*')
                            <div class="text-danger font-monospace mt-1" style="font-size: 0.75rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="col-12">
                        <label for="description" class="form-label fw-medium text-dark">Deskripsi & Kondisi Minus (Opsional)</label>
                        <textarea name="description" id="description" rows="5" class="form-control @error('description') is-invalid @enderror" placeholder="Jelaskan detail ukuran riil (P x L), minus noda/sobekan jika ada, atau informasi tag label.">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Form Action Buttons -->
                <div class="d-flex justify-content-end gap-3 mt-5 pt-4 border-top border-light-subtle">
                    <a href="{{ route('products.index') }}" class="btn btn-outline-black px-4">Batal</a>
                    <button type="submit" class="btn btn-black px-4">Simpan Produk</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    const fileInput = document.getElementById('imageInput');
    const container = document.getElementById('imagePreviewsContainer');
    let selectedFiles = [];

    function renderPreviews() {
        container.innerHTML = '';

        // "Add More" Button Element
        const addMoreBtn = document.createElement('div');
        addMoreBtn.className = 'border border-dashed border-secondary rounded-3 d-flex flex-column align-items-center justify-content-center cursor-pointer bg-white';
        addMoreBtn.style.width = '100px';
        addMoreBtn.style.height = '100px';
        addMoreBtn.style.cursor = 'pointer';
        addMoreBtn.innerHTML = `
            <i class="bi bi-camera-fill fs-4 text-secondary mb-1"></i>
            <span class="font-monospace text-secondary" style="font-size: 0.65rem;">+ Tambah (${selectedFiles.length}/10)</span>
        `;
        addMoreBtn.onclick = () => fileInput.click();

        selectedFiles.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const imgWrapper = document.createElement('div');
                imgWrapper.className = 'position-relative flex-shrink-0 border border-light-subtle rounded-3 overflow-hidden bg-light shadow-sm';
                imgWrapper.style.width = '100px';
                imgWrapper.style.height = '100px';
                
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'w-100 h-100 object-fit-cover';
                
                const badge = document.createElement('span');
                badge.className = 'position-absolute top-0 start-0 bg-dark text-white font-monospace px-1 rounded-bottom-end';
                badge.style.fontSize = '0.6rem';
                badge.innerText = index === 0 ? 'UTAMA' : (index + 1);
                
                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.className = 'position-absolute top-0 end-0 btn btn-sm btn-danger p-0 d-flex align-items-center justify-content-center rounded-circle m-1 shadow-sm';
                removeBtn.style.width = '20px';
                removeBtn.style.height = '20px';
                removeBtn.style.fontSize = '12px';
                removeBtn.innerHTML = '&times;';
                removeBtn.onclick = function(ev) {
                    ev.preventDefault();
                    selectedFiles.splice(index, 1);
                    updateFileInput();
                    renderPreviews();
                };
                
                imgWrapper.appendChild(img);
                imgWrapper.appendChild(badge);
                imgWrapper.appendChild(removeBtn);
                container.appendChild(imgWrapper);
            }
            reader.readAsDataURL(file);
        });
        
        if (selectedFiles.length < 10) {
            container.appendChild(addMoreBtn);
        }
    }

    function updateFileInput() {
        const dt = new DataTransfer();
        selectedFiles.forEach(file => dt.items.add(file));
        fileInput.files = dt.files;
    }

    fileInput.addEventListener('change', function(e) {
        if (this.files) {
            Array.from(this.files).forEach(file => {
                // Ensure no duplicate file names (optional basic check) and within limit
                if (file.type.match('image.*') && selectedFiles.length < 10) {
                    selectedFiles.push(file);
                }
            });
            updateFileInput();
            renderPreviews();
        }
    });

    // Initialize with add button
    renderPreviews();
</script>
@endsection
