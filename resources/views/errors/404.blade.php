<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan - BAPENDA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
            background: url('{{ asset("kantor_bupati.jpg") }}') center center / cover no-repeat;
            position: relative;
            color: #fff;
        }
        .bg-overlay {
            position: fixed; inset: 0;
            background: linear-gradient(135deg, rgba(15,10,10,0.80) 0%, rgba(30,15,15,0.70) 100%);
            z-index: 1;
        }
        .error-card {
            position: relative; z-index: 10;
            background: rgba(255, 255, 255, 0.10);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 24px;
            padding: 44px 36px;
            max-width: 480px; width: 90%;
            text-align: center;
            box-shadow: 0 10px 40px rgba(0,0,0,0.4);
        }
        .btn-red {
            background: linear-gradient(135deg, #dc2626, #991b1b);
            color: #fff; font-weight: 700; border: none;
            padding: 12px 24px; border-radius: 12px;
            text-decoration: none; display: inline-block;
        }
        .btn-red:hover { color: #fff; transform: translateY(-1px); }
    </style>
</head>
<body>
    <div class="bg-overlay"></div>
    <div class="error-card">
        <div class="mb-3">
            <span class="display-1 fw-bold" style="color:#fca5a5;">404</span>
        </div>
        <h4 class="fw-bold mb-2">Halaman Tidak Ditemukan</h4>
        <p class="small mb-4" style="color:rgba(255,255,255,0.60);">
            Halaman atau dokumen yang Anda cari tidak tersedia di server BAPENDA.
        </p>
        <a href="{{ url('/') }}" class="btn-red">
            <i class="fas fa-home me-2"></i>Kembali ke Dashboard
        </a>
    </div>
</body>
</html>
