<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Metode Pembayaran</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        h1 {
            font-size: 1.8rem;
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .method-list {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: center;
        }

        .method-item {
            flex: 1 1 calc(50% - 15px); /* Responsif: Lebar tiap item 50% */
            background-color: #f4f4f4;
            border: 1px solid #ddd;
            border-radius: 8px;
            text-align: center;
            padding: 10px;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s, background-color 0.2s;
        }

        .method-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .method-item img {
            width: 60px; /* Ukuran lebar logo yang konsisten */
            height: 60px; /* Ukuran tinggi logo yang konsisten */
            object-fit: contain; /* Menjaga aspek rasio gambar */
            margin-bottom: 10px;
        }

        .method-item span {
            display: block;
            font-size: 1rem;
            color: #333;
        }

        .method-item.selected {
            background-color: #007bff;
            color: #fff;
            border-color: #0056b3;
        }

        .btn-container {
            text-align: center;
            margin-top: 20px;
        }

        .btn {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.2s;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        @media (max-width: 480px) {
            .method-item {
                flex: 1 1 100%; /* Lebar penuh di layar kecil */
            }

            h1 {
                font-size: 1.5rem;
            }

            .btn {
                font-size: 0.9rem;
                padding: 8px 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Pilih Metode Pembayaran</h1>
        <div class="method-list">
            <div class="method-item" onclick="selectMethod(this, 'bank_transfer')">
                <img src="../assets/bri-logo.png" alt="Bank Transfer">
                <span>Bank Transfer</span>
            </div>
            <div class="method-item" onclick="selectMethod(this, 'e_wallet')">
                <img src="../assets/dana-logo.png" alt="E-Wallet">
                <span>E-Wallet</span>
            </div>
        </div>
        <div class="btn-container">
            <a href="#" id="proceed-btn" class="btn" onclick="proceedPayment()">Lanjutkan</a>
        </div>
    </div>

    <script>
        let selectedMethod = null;

        function selectMethod(element, method) {
            // Hapus kelas "selected" dari semua metode
            document.querySelectorAll('.method-item').forEach(item => {
                item.classList.remove('selected');
            });

            // Tambahkan kelas "selected" ke metode yang dipilih
            element.classList.add('selected');

            // Simpan metode yang dipilih
            selectedMethod = method;
        }

        function proceedPayment() {
            if (!selectedMethod) {
                alert("Silakan pilih metode pembayaran terlebih dahulu.");
                return;
            }

            alert("Anda memilih metode pembayaran: " + selectedMethod);
            // Implementasikan logika untuk melanjutkan pembayaran sesuai metode yang dipilih
        }
    </script>
</body>
</html>
