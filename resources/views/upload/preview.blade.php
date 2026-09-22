@extends('layouts.app')

@section('title', 'Preview & Validasi Parsing PDF')
@section('page_heading', 'Preview Data & Validasi OCR PDF')

@section('content')
<div class="card-custom p-4 mb-4 border-danger border-2" style="border-style: dashed !important;">
    <div class="d-flex align-items-center justify-content-between mb-3 pb-3 border-bottom border-white-10">
        <div>
            <h6 class="fw-bold text-white mb-1">
                <i class="fas fa-clipboard-check me-2 text-danger"></i> Hasil Pembacaan Parser PDF: {{ $upload->original_filename }}
            </h6>
            <small class="text-white-50">Instansi: <strong class="text-white">{{ $opd_name }}</strong> | Periode: <strong class="text-white">{{ $periode }} {{ $tahun }}</strong></small>
        </div>
        <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold">
            <i class="fas fa-edit me-1"></i> Mode Validasi (Dapat Diedit)
        </span>
    </div>

    <div class="alert alert-danger-subtle border border-danger-subtle rounded-3 small mb-4" style="background: rgba(220,38,38,0.20); color: #fecaca;">
        <i class="fas fa-triangle-exclamation me-1"></i> Periksa kembali data hasil ekstraksi di bawah ini. Jika ada angka/kode yang salah terbaca, silakan ubah langsung pada kolom sebelum disimpan ke database.
    </div>

    <form action="{{ route('upload.save') }}" method="POST" id="validationForm">
        @csrf
        <input type="hidden" name="upload_id" value="{{ $upload->id }}">

        <div class="table-responsive mb-4 rounded-3" style="background: rgba(0,0,0,0.30); border: 1px solid rgba(255,255,255,0.15);">
            <table class="table table-bordered align-middle" id="previewTable">
                <thead>
                    <tr style="background: rgba(0,0,0,0.35);">
                        <th style="width: 50px; color: #ffffff;">#</th>
                        <th style="width: 220px; color: #ffffff;" class="small fw-bold">Kode Rekening</th>
                        <th class="small fw-bold" style="color: #ffffff;">Nama Retribusi</th>
                        <th style="width: 220px; color: #ffffff;" class="small fw-bold">Nilai Realisasi (Rp)</th>
                        <th style="width: 60px; color: #ffffff;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    @foreach($parsedItems as $index => $item)
                        <tr>
                            <td class="text-center fw-bold text-white index-col">{{ $index + 1 }}</td>
                            <td>
                                <input type="text" name="kode_rekening[]" class="form-control form-control-sm rounded-3 fw-bold" value="{{ $item['kode'] }}" required>
                            </td>
                            <td>
                                <input type="text" name="nama_retribusi[]" class="form-control form-control-sm rounded-3" value="{{ $item['nama'] }}" required>
                            </td>
                            <td>
                                <input type="number" name="nilai[]" class="form-control form-control-sm rounded-3 fw-bold text-success value-input" value="{{ $item['nilai'] }}" oninput="updateTotal()" required>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-outline-danger rounded-circle p-1" onclick="removeRow(this)">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr style="background: rgba(0,0,0,0.40);">
                        <td colspan="3" class="fw-bold text-end text-white">Total Realisasi Extracted:</td>
                        <td class="fw-bold text-danger fs-6" id="grandTotal">Rp {{ number_format(array_sum(array_column($parsedItems, 'nilai')), 0, ',', '.') }}</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2">
            <button type="button" class="btn btn-sm btn-outline-secondary rounded-3" onclick="addRow()">
                <i class="fas fa-plus me-1"></i> Tambah Baris Manual
            </button>

            <div class="d-flex gap-2">
                <a href="{{ route('upload.index') }}" class="btn btn-light border fw-semibold">Batalkan</a>
                <button type="submit" class="btn btn-red fw-bold shadow-sm">
                    <i class="fas fa-database me-2"></i> Simpan ke Database MySQL
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    function rowCount() {
        return document.querySelectorAll('#tableBody tr').length;
    }

    function updateTotal() {
        let total = 0;
        document.querySelectorAll('.value-input').forEach(input => {
            total += parseFloat(input.value) || 0;
        });
        document.getElementById('grandTotal').innerText = 'Rp ' + total.toLocaleString('id-ID');
    }

    function addRow() {
        const tbody = document.getElementById('tableBody');
        const nextIdx = rowCount() + 1;
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td class="text-center fw-bold text-muted index-col">${nextIdx}</td>
            <td><input type="text" name="kode_rekening[]" class="form-control form-control-sm rounded-3 fw-bold" placeholder="4.1.02.xx.xx" required></td>
            <td><input type="text" name="nama_retribusi[]" class="form-control form-control-sm rounded-3" placeholder="Nama Jenis Retribusi" required></td>
            <td><input type="number" name="nilai[]" class="form-control form-control-sm rounded-3 fw-bold text-success value-input" value="0" oninput="updateTotal()" required></td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-outline-danger rounded-circle p-1" onclick="removeRow(this)">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </td>
        `;
        tbody.appendChild(tr);
        updateTotal();
    }

    function removeRow(btn) {
        if (rowCount() > 1) {
            btn.closest('tr').remove();
            // Re-index
            document.querySelectorAll('#tableBody tr').forEach((tr, i) => {
                tr.querySelector('.index-col').innerText = i + 1;
            });
            updateTotal();
        } else {
            alert('Minimal harus ada satu baris data.');
        }
    }
</script>
@endsection
