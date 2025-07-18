<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="<?php echo base_url('assets/img/logo1.png'); ?>">
    <script src="<?php echo base_url('assets/js/sweetalert2.min.js'); ?>"></script>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/sweetalert2.min.css'); ?>">
</head>

<body class="min-h-screen flex items-center justify-center bg-gray-50 py-8">
    <div class="relative w-full max-w-xl mx-4 animate-fade-in">
        <div class="relative bg-white rounded-2xl shadow-lg p-8 lg:p-10 border border-indigo-100">
            <div class="flex flex-col items-center mb-8 animate-slide-up">
                <h1 class="text-3xl font-bold text-indigo-950">Buat Akun Baru</h1>
                <p class="text-indigo-600 mt-2">Silakan lengkapi formulir di bawah ini untuk mendaftar</p>
            </div>

            <?php if ($this->session->flashdata('error')) : ?>
                <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-lg">
                    <?= $this->session->flashdata('error'); ?>
                </div>
            <?php endif; ?>

            <form name="prosesregister" method="post" action="<?= base_url('Register/prosesregister'); ?>" class="space-y-6" onsubmit="return validateForm(event)">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label for="namaLengkap" class="block text-lg font-medium text-indigo-900">Nama Lengkap</label>
                        <input type="text" id="namaLengkap" name="NamaLengkap" 
                               class="w-full px-4 py-3 bg-indigo-50 border border-indigo-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500/20" 
                               placeholder="Masukkan nama lengkap">
                    </div>
                    <div class="space-y-2">
                        <label for="jenisAkun" class="block text-lg font-medium text-indigo-900">Level</label>
                        <select name="JenisAkun" id="jenisAkun" class="w-full px-4 py-3 bg-indigo-50 border border-indigo-200 rounded-lg">
                            <option value="">Pilih Level</option>
                            <option value="Kepala Lingkungan">Kepala Lingkungan</option>
                            <option value="Penanggung Jawab">Penanggung Jawab</option>
                        </select>
                    </div>
                </div>
                <div class="space-y-2">
                    <label for="Nik" class="block text-lg font-medium text-indigo-900">NIK KTP</label>
                    <input type="text" id="Nik" name="NIK" class="w-full px-4 py-3 bg-indigo-50 border border-indigo-200 rounded-lg" 
                           placeholder="Masukkan NIK Anda (16 digit)" maxlength="16" 
                           oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                    <p class="text-xs text-gray-500">NIK harus terdiri dari 16 digit angka</p>
                </div>

                <div class="space-y-2">
                    <label for="alamat" class="block text-lg font-medium text-indigo-900">Alamat</label>
                    <textarea id="alamat" name="Alamat" class="w-full px-4 py-3 bg-indigo-50 border border-indigo-200 rounded-lg" 
                              placeholder="Masukkan alamat Anda"></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label for="Telp" class="block text-lg font-medium text-indigo-900">No Telepon</label>
                        <input type="tel" id="Telp" name="Telp" class="w-full px-4 py-3 bg-indigo-50 border border-indigo-200 rounded-lg" 
                               placeholder="Masukkan no telepon Anda" 
                               oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                    </div>
                    <div class="space-y-2">
                        <label for="email" class="block text-lg font-medium text-indigo-900">Email</label>
                        <input type="email" id="email" name="Email" class="w-full px-4 py-3 bg-indigo-50 border border-indigo-200 rounded-lg" 
                               placeholder="Masukkan email Anda">
                    </div>
                </div>

                <div class="p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                    <p class="text-lg text-yellow-800">
                        <strong>Catatan:</strong> Password akan diberikan secara otomatis setelah registrasi berhasil.
                    </p>
                </div>

                <div class="mt-6">
                    <button type="submit" class="w-full py-3 px-4 text-lg bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                        Registrasi Akun
                    </button>
                </div>

                <div class="flex items-center justify-center mt-4">
                    <span class="text-lg text-indigo-800">Sudah punya akun?</span>
                    <a href="<?= base_url('loginHalaman'); ?>" class="ml-2 text-lg text-indigo-600 font-medium hover:text-indigo-800">Masuk</a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        function validateForm(event) {
            event.preventDefault();
            
            const namaLengkap = document.getElementById('namaLengkap').value.trim();
            const alamat = document.getElementById('alamat').value.trim();
            const nik = document.getElementById('Nik').value.trim();
            const telp = document.getElementById('Telp').value.trim(); // Changed to match the field ID
            const email = document.getElementById('email').value.trim();
            const jenisAkun = document.getElementById('jenisAkun').value;

            if (!namaLengkap || !alamat || !nik || !telp || !email || !jenisAkun) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Semua bidang harus diisi',
                    icon: 'error',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#4f46e5'
                });
                return false;
            }

            if (nik.length !== 16 || !/^\d+$/.test(nik)) {
                Swal.fire({
                    title: 'Error!',
                    text: 'NIK harus terdiri dari 16 digit angka',
                    icon: 'error',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#4f46e5'
                });
                return false;
            }

            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Format email tidak valid',
                    icon: 'error',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#4f46e5'
                });
                return false;
            }

            $.ajax({
                url: '<?= base_url("Register/cekNIK"); ?>',
                type: 'POST',
                data: { NIK: nik },
                success: function(response) {
                    if (response === 'exists') {
                        Swal.fire({
                            title: 'Error!',
                            text: 'NIK sudah terdaftar!',
                            icon: 'error',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#4f46e5'
                        });
                    } else {
                        Swal.fire({
                            title: 'Processing...',
                            text: 'Akun Anda sedang diproses, harap tunggu...',
                            icon: 'info',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            document.forms['prosesregister'].submit();
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Terjadi kesalahan saat memeriksa NIK',
                        icon: 'error',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#4f46e5'
                    });
                }
            });
            return false;
        }
    </script>
</body>
</html>