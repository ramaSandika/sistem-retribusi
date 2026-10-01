@extends('layouts.admin')

@section('content')
    <div class="mb-5">
        <h1 class="h2 text-dark fw-bold mb-1">Edit Produk</h1>
        <p class="text-secondary font-monospace" style="font-size: 0.8rem;">Ubah informasi detail produk thrift #{{ $product->id }}.</p>
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

    <div class="row g-4">
        <!-- Main Form Column -->
        <div class="col-lg-8">
            <div class="card-admin shadow-sm mb-4">
                <div class="card-header">Form Edit Produk</div>
                <div class="card-body">
                    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row g-4">
                            <!-- Name -->
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-medium text-dark">Nama Produk <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $product->name) }}" required placeholder="Contoh: Vintage Wool Sweater">
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
                                        <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
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
                                    <input type="number" name="price" id="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', intval($product->price)) }}" required min="0" placeholder="150000">
                                </div>
                                @error('price')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Size -->
                            <div class="col-md-4">
                                <label for="size" class="form-label fw-medium text-dark">Ukuran (Size) <span class="text-danger">*</span></label>
                                <input type="text" name="size" id="size" class="form-control @error('size') is-invalid @enderror" value="{{ old('size', $product->size) }}" required placeholder="Contoh: XL, L, 32, Oversize">
                                @error('size')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Condition -->
                            <div class="col-md-4">
                                <label for="condition" class="form-label fw-medium text-dark">Kondisi Barang <span class="text-danger">*</span></label>
                                <select name="condition" id="condition" class="form-select @error('condition') is-invalid @enderror" required>
                                    <option value="Like New (9.5/10)" {{ old('condition', $product->condition) == 'Like New (9.5/10)' ? 'selected' : '' }}>Like New (9.5/10)</option>
                                    <option value="Very Good (9/10)" {{ old('condition', $product->condition) == 'Very Good (9/10)' ? 'selected' : '' }}>Very Good (9/10)</option>
                                    <option value="Good (8.5/10)" {{ old('condition', $product->condition) == 'Good (8.5/10)' ? 'selected' : '' }}>Good (8.5/10)</option>
                                    <option value="Fair Condition" {{ old('condition', $product->condition) == 'Fair Condition' ? 'selected' : '' }}>Fair Condition</option>
                                </select>
                                @error('condition')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Brand -->
                            <div class="col-md-6">
                                <label for="brand" class="form-label fw-medium text-dark">Brand / Merek (Opsional)</label>
                                <input type="text" name="brand" id="brand" class="form-control @error('brand') is-invalid @enderror" value="{{ old('brand', $product->brand) }}" placeholder="Contoh: Carhartt, Nike, Levi's">
                                @error('brand')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="col-md-6">
                                <label for="status" class="form-label fw-medium text-dark">Status Ketersediaan <span class="text-danger">*</span></label>
                                <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                    <option value="available" {{ old('status', $product->status) == 'available' ? 'selected' : '' }}>Tersedia (Ready Stock)</option>
                                    <option value="sold_out" {{ old('status', $product->status) == 'sold_out' ? 'selected' : '' }}>Sold Out (Terjual)</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Add More Images -->
                            <div class="col-12">
                                <label class="form-label fw-medium text-dark d-block">Tambah Foto Baru</label>
                                
                                <!-- Hidden File Input -->
                                <input type="file" name="images[]" id="images" class="d-none" multiple accept="image/jpeg,image/png,image/webp">
                                
                                <!-- Preview Container -->
                                <div id="imagePreviewContainer" class="d-flex gap-2 overflow-x-auto p-3 border border-light-subtle rounded-3 bg-light" style="white-space: nowrap; min-height: 135px;">
                                    <!-- Previews & Add Button rendered by JS -->
                                </div>

                                <div class="form-text font-monospace text-secondary mt-2" style="font-size: 0.75rem;">
                                    * Tambahkan foto satu per satu dengan tombol (+), atau pilih beberapa file sekaligus. Maks 5MB/foto.<br>
                                    * Klik tombol (x) pada pratinjau untuk membatalkan unggahan foto baru.
                                </div>
                                @error('images')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="col-12">
                                <label for="description" class="form-label fw-medium text-dark">Deskripsi & Kondisi Minus (Opsional)</label>
                                <textarea name="description" id="description" rows="6" class="form-control @error('description') is-invalid @enderror" placeholder="Tuliskan detail spesifikasi produk...">{{ old('description', $product->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Buttons -->
                            <div class="col-12 mt-4 border-top border-light-subtle pt-3 d-flex justify-content-end gap-3">
                                <a href="{{ route('products.index') }}" class="btn btn-outline-black px-4">Batal</a>
                                <button type="submit" class="btn btn-black px-4">Perbarui Data</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Images Management Column -->
        <div class="col-lg-4">
            <div class="card-admin shadow-sm">
                <div class="card-header">Galeri Foto Produk</div>
                <div class="card-body">
                    <p class="text-secondary font-monospace mb-4" style="font-size: 0.75rem;">Kelola foto produk yang terunggah. Atur foto utama atau hapus foto tambahan.</p>
                    
                    <div class="row g-3">
                        @forelse($product->images as $img)
                            <div class="col-6">
                                <div class="border border-light-subtle rounded-3 position-relative bg-light overflow-hidden shadow-sm" style="padding-bottom: 125%;">
                                    <img src="{{ $img->image_url }}" class="position-absolute w-100 h-100 object-fit-cover top-0 start-0" alt="product image">
                                    
                                    @if($img->is_primary)
                                        <span class="position-absolute top-0 start-0 bg-dark text-white font-monospace rounded-bottom-end px-2 py-1" style="font-size: 0.65rem;">UTAMA</span>
                                    @endif
                                </div>
                                <div class="mt-2 d-flex justify-content-between gap-1">
                                    <!-- Set as Primary -->
                                    @if(!$img->is_primary)
                                        <form action="{{ route('products.images.primary', [$product->id, $img->id]) }}" method="POST" class="d-inline flex-grow-1">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-black w-100 py-1 font-monospace rounded-3" style="font-size: 0.65rem;">Jadikan Utama</button>
                                        </form>
                                    @else
                                        <button class="btn btn-sm btn-dark w-100 py-1 font-monospace rounded-3" style="font-size: 0.65rem;" disabled>Utama</button>
                                    @endif

                                    <!-- Delete Image -->
                                    <form action="{{ route('products.images.destroy', [$product->id, $img->id]) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus foto ini?');" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-black py-1 px-2 text-danger rounded-3" title="Hapus Foto" {{ $product->images->count() === 1 ? 'disabled' : '' }}>
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center py-4 text-muted font-monospace" style="font-size: 0.8rem;">
                                Tidak ada foto terunggah.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    let selectedFiles = [];
    const fileInput = document.getElementById('images');
    const container = document.getElementById('imagePreviewContainer');
    
    const addMoreBtn = document.createElement('div');
    addMoreBtn.className = 'flex-shrink-0 border border-dashed border-secondary rounded-3 d-flex flex-column align-items-center justify-content-center bg-white';
    addMoreBtn.style.width = '100px';
    addMoreBtn.style.height = '100px';
    addMoreBtn.style.cursor = 'pointer';
    addMoreBtn.innerHTML = `
        <i class="bi bi-camera-fill fs-4 text-secondary mb-1"></i>
        <span class="font-monospace text-secondary" style="font-size: 0.65rem;">+ Tambah</span>
    `;
    addMoreBtn.onclick = () => fileInput.click();

    function renderPreviews() {
        container.innerHTML = '';
        
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
                badge.innerText = 'BARU ' + (index + 1);
                
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
