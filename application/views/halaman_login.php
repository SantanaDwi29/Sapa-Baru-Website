<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="<?php echo base_url('assets/img/logo1.png'); ?>">
    <script src="<?php echo base_url('assets/js/sweetalert2.min.js'); ?>"></script>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/sweetalert2.min.css'); ?>">

    <style>
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center bg-gray-50">
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute inset-0 bg-[linear-gradient(120deg,#f0f0f0_1px,transparent_1px)] bg-[length:40px_40px] opacity-20"></div>
    </div>

    <div class="relative w-full max-w-md mx-4">
        <div class="relative bg-white rounded-2xl shadow-lg p-8 lg:p-10 border border-indigo-100">
            <div class="flex flex-col items-center mb-10">
                <div class="w-16 h-16 bg-indigo-600 rounded-2xl flex items-center justify-center shadow-lg mb-6">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                        </path>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-indigo-950">Selamat Datang</h1>
                <p class="text-indigo-600 mt-2 text-base">Silakan masuk untuk melanjutkan</p>
            </div>

            <form name="proseslogin" method="post" action="<?php echo base_url('loginHalaman/proseslogin'); ?>" class="space-y-6" onsubmit="return validateForm()">
                <div class="space-y-2">
                    <label for="Nik" class="block text-lg font-medium text-indigo-900">NIK KTP</label>
                    <div class="relative">
                        <input
                            type="number"
                            id="NIK"
                            name="NIK"
                            class="w-full px-4 py-3 bg-indigo-50 border border-indigo-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-indigo-900 placeholder-indigo-400 transition-all duration-300"
                            placeholder="Masukan NIK KTP anda">
                    </div>
                </div>

                <div class="space-y-2">
                    <label for="Password" class="block text-lg font-medium text-indigo-900">Password</label>
                    <div class="relative">
                        <input
                            type="password"
                            id="Password"
                            name="Password"
                            class="w-full px-4 py-3 bg-indigo-50 border border-indigo-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-indigo-900 placeholder-indigo-400 transition-all duration-300"
                            placeholder="Masukan password anda">
                    </div>
                </div>

            

                <button
                    type="submit"
                    class="w-full py-3 px-4 text-lg bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 transition-all duration-300 transform hover:translate-y-[-1px] active:translate-y-[1px]">
                    Masuk
                </button>

                <div class="flex items-center justify-center">
                    <a href="<?php echo base_url('register'); ?>" class="text-lg text-indigo-600 hover:text-indigo-800 transition-colors duration-300 text-center">
                        Belum Punya Akun?
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function validateForm() {
            const Nik = document.getElementById('NIK').value;
            const password = document.getElementById('Password').value;

            if (!Nik) {
                Swal.fire({
                    title: 'Error!',
                    text: 'NIK tidak boleh kosong',
                    icon: 'error',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#0ea5e9'
                });
                return false;
            }

            if (!password) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Password tidak boleh kosong',
                    icon: 'error',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#0ea5e9'
                });
                return false;
            }

            return true;
        }

        <?php if ($this->session->flashdata('error')): ?>
        document.addEventListener('DOMContentLoaded', function () {
            const errorMessage = '<?php echo $this->session->flashdata('error'); ?>';
            Swal.fire({
                icon: errorMessage.includes('verifikasi') ? 'warning' : 'error',
                title: errorMessage.includes('verifikasi') ? 'Peringatan!' : 'Error!',
                text: errorMessage,
                confirmButtonText: 'OK',
                confirmButtonColor: '#0284c7'
            });
        });
        <?php endif; ?>
    </script>
</body>
</html>
