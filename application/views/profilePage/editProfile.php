<?php
$NamaLengkap = isset($profile) ? $profile->NamaLengkap : $this->session->userdata('NamaLengkap');
$NIK = isset($profile) ? $profile->NIK : $this->session->userdata('NIK');
$Alamat = isset($profile) ? $profile->Alamat : '';
$Telp = isset($profile) ? $profile->Telp : '';
$Email = isset($profile) ? $profile->Email : '';
$JenisAkun = isset($profile) ? $profile->JenisAkun : $this->session->userdata('JenisAkun');
?>

<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-xl shadow-xl overflow-hidden border border-gray-200">
        <div class="bg-gradient-to-br from-slate-900 to-indigo-900 px-8 py-6">
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-bold text-white flex items-center">
                    <i class="fas fa-edit mr-3"></i>Edit Profile
                </h2>
                <button onclick="window.location.href='<?= base_url('profile') ?>'"
                    class="bg-white/10 hover:bg-white/20 text-white px-4 py-2 rounded-lg backdrop-blur-sm transition-all flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </button>
            </div>
        </div>

        <div class="p-6 md:p-8">
            <?php if ($this->session->flashdata('error')): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <?= $this->session->flashdata('error') ?>
                </div>
            <?php endif; ?>

            <?php if ($this->session->flashdata('success')): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    <i class="fas fa-check-circle mr-2"></i>
                    <?= $this->session->flashdata('success') ?>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('profile/save') ?>" method="POST" class="space-y-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div class="space-y-6">
                        <h3 class="text-xl font-semibold text-gray-800 flex items-center border-b pb-3">
                            <i class="fas fa-user-circle mr-3 text-blue-600"></i>Informasi Pribadi
                        </h3>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">
                                <i class="fas fa-id-card mr-2 text-blue-500"></i>NIK
                            </label>
                            <input type="text" value="<?= htmlspecialchars($NIK) ?>"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100 text-gray-600"
                                readonly>
                            <p class="text-xs text-gray-500">NIK tidak dapat diubah</p>
                        </div>

                        <!-- Nama Lengkap -->
                        <div class="space-y-2">
                            <label for="namaLengkap" class="block text-sm font-medium text-gray-700">
                                <i class="fas fa-user mr-2 text-green-500"></i>Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="namaLengkap" name="namaLengkap"
                                value="<?= htmlspecialchars($NamaLengkap) ?>"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                placeholder="Masukkan nama lengkap" required>
                        </div>

                        <!-- Alamat -->
                        <div class="space-y-2">
                            <label for="alamat" class="block text-sm font-medium text-gray-700">
                                <i class="fas fa-map-marker-alt mr-2 text-red-500"></i>Alamat <span class="text-red-500">*</span>
                            </label>
                            <textarea id="alamat" name="alamat" rows="4"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                placeholder="Masukkan alamat lengkap" required><?= htmlspecialchars($Alamat) ?></textarea>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">
                                <i class="fas fa-user-tag mr-2 text-indigo-500"></i>Jenis Akun
                            </label>
                            <input type="text" value="<?= htmlspecialchars($JenisAkun) ?>"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100 text-gray-600"
                                readonly>
                            <p class="text-xs text-gray-500">Jenis akun tidak dapat diubah</p>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <h3 class="text-xl font-semibold text-gray-800 flex items-center border-b pb-3">
                            <i class="fas fa-address-book mr-3 text-green-600"></i>Informasi Kontak
                        </h3>

                        <div class="space-y-2">
                            <label for="telp" class="block text-sm font-medium text-gray-700">
                                <i class="fas fa-phone mr-2 text-purple-500"></i>Telepon <span class="text-red-500">*</span>
                            </label>
                            <input type="tel" id="telp" name="telp"
                                value="<?= htmlspecialchars($Telp) ?>"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                placeholder="Contoh: 08123456789" required>
                            <p class="text-xs text-gray-500">Masukkan nomor telepon yang aktif</p>
                        </div>

                        <div class="space-y-2">
                            <label for="email" class="block text-sm font-medium text-gray-700">
                                <i class="fas fa-envelope mr-2 text-orange-500"></i>Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" id="email" name="email"
                                value="<?= htmlspecialchars($Email) ?>"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                placeholder="contoh@email.com" required>
                            <p class="text-xs text-gray-500">Email akan digunakan untuk notifikasi</p>
                        </div>

                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <h4 class="font-medium text-blue-800 mb-2 flex items-center">
                                <i class="fas fa-info-circle mr-2"></i>Informasi Penting
                            </h4>
                            <ul class="text-sm text-blue-700 space-y-1">
                                <li>• Pastikan data yang dimasukkan benar dan valid</li>
                                <li>• Email harus unik dan belum digunakan pengguna lain</li>
                                <li>• Nomor telepon harus dalam format yang benar</li>
                                <li>• Data yang telah disimpan dapat diubah kembali kapan saja</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-gray-200">
                    <button type="submit"
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-lg transition-colors flex items-center justify-center">
                        <i class="fas fa-save mr-2"></i>Simpan Perubahan
                    </button>

                    <button type="button"
                        onclick="window.location.href='<?= base_url('profile') ?>'"
                        class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-medium py-3 px-6 rounded-lg transition-colors flex items-center justify-center">
                        <i class="fas fa-times mr-2"></i>Batal
                    </button>

                    <button type="reset"
                        class="sm:flex-none bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-3 px-6 rounded-lg transition-colors flex items-center justify-center">
                        <i class="fas fa-undo mr-2"></i>Reset
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const telp = document.getElementById('telp');
        const email = document.getElementById('email');
        const resetButton = document.querySelector('button[type="reset"]');

        if (telp) {
            telp.addEventListener('input', function() {
                let value = this.value.replace(/\D/g, '');
                if (value.startsWith('62')) {
                    value = '0' + value.substring(2);
                }
                this.value = value;
                if (value.length < 10 || value.length > 15) {
                    this.setCustomValidity('Nomor telepon harus 10-15 digit');
                } else {
                    this.setCustomValidity('');
                }
            });
        }

        if (email) {
            email.addEventListener('input', function() {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(this.value)) {
                    this.setCustomValidity('Format email tidak valid');
                } else {
                    this.setCustomValidity('');
                }
            });
        }

        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Simpan Perubahan?',
                    text: "Apakah Anda yakin ingin menyimpan data profil?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Simpan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        }

        if (resetButton) {
            resetButton.addEventListener('click', function(e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Reset Form?',
                    text: "Semua perubahan yang belum disimpan akan hilang. Anda yakin?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Reset!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.reset();
                    }
                });
            });
        }
    });
</script>