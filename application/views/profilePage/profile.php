<?php
$NamaLengkap = $this->session->userdata('NamaLengkap');
$JenisAkun = $this->session->userdata('JenisAkun');

if (isset($profile)) {
    $NamaLengkap = $profile->NamaLengkap ?: $NamaLengkap;
    $JenisAkun = $profile->JenisAkun ?: $JenisAkun;
    $NIK = $profile->NIK ?: '';
    
    // Gunakan isset() atau property_exists() untuk mengecek properti sebelum mengaksesnya
    $Alamat = isset($profile->Alamat) ? $profile->Alamat : '';
    $Telp = isset($profile->Telp) ? $profile->Telp : '';
    $Email = isset($profile->Email) ? $profile->Email : '';
    $StatusAktivasi = isset($profile->StatusAktivasi) ? $profile->StatusAktivasi : 'Aktif';

    if (!empty($profile->FotoProfil)) {
        $FotoProfil = base_url('uploads/profile/' . $profile->FotoProfil);
    } else {
        $FotoProfil = base_url('assets/img/default-profile.jpg');
    }
} else {
    $NIK = $this->session->userdata('NIK') ?: '';
    $Alamat = '';
    $Telp = '';
    $Email = '';
    $StatusAktivasi = 'Aktif';
    $FotoProfil = base_url('assets/img/default-profile.jpg');
}

function displayData($data, $placeholder = 'Belum diisi')
{
    return !empty(trim($data)) ? htmlspecialchars($data) : '<span class="text-gray-400 italic">' . $placeholder . '</span>';
}
?>

<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-xl shadow-xl overflow-hidden border border-gray-200">
        <div class="relative bg-gradient-to-br from-slate-900 to-indigo-900 px-8 py-12">
            <div class="absolute top-4 right-4">
                <button onclick="window.location.href='<?= base_url('profile/editProfile') ?>'"
                    class="bg-white/10 hover:bg-white/20 text-white px-4 py-2 rounded-lg backdrop-blur-sm transition-all flex items-center">
                    <i class="fas fa-edit mr-2"></i> Edit Profile
                </button>
            </div>

            <div class="flex flex-col md:flex-row items-center space-y-4 md:space-y-0 md:space-x-6">
                <div class="relative group">
                    <div class="w-22 h-22 md:w-32 md:h-32 rounded-full border-4 border-white/50 shadow-lg overflow-hidden">
                        <img id="profileImage" src="<?php echo $FotoProfil; ?>" alt="Profile Photo"
                            class="w-full h-full object-cover transition-transform duration-300">
                    </div>

                    <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 ease-in-out">
                        <button id="changePhotoBtn" class="
                            bg-black/50 text-white p-2
                            rounded-full hover:bg-black/70
                            w-10 h-10 flex items-center justify-center
                            transform transition-transform duration-300"> <i class="fas fa-camera text-xl"></i>
                        </button>
                    </div>
                </div>

                <div class="text-white text-center md:text-left">
                    <h2 class="text-2xl md:text-3xl font-bold mb-2"><?php echo htmlspecialchars($NamaLengkap); ?></h2>
                    <p class="text-blue-200 text-sm md:text-lg mb-3"><?php echo htmlspecialchars($JenisAkun); ?></p>

                    <div class="flex flex-wrap justify-center md:justify-start gap-2">
                        <span class="bg-green-600/90 text-white px-3 py-1 rounded-full text-xs md:text-sm flex items-center">
                            <i class="fas fa-check-circle mr-1"></i> <?php echo htmlspecialchars($StatusAktivasi); ?>
                        </span>
                        <span class="bg-blue-600/90 text-white px-3 py-1 rounded-full text-xs md:text-sm flex items-center">
                            <i class="fas fa-user-shield mr-1"></i> <?php echo htmlspecialchars($JenisAkun); ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="border-b border-gray-200">
            <nav class="flex overflow-x-auto">
                <button id="infoTab" class="tab-button active px-6 py-4 font-medium text-sm border-b-2 border-blue-600 text-blue-600">
                    <i class="fas fa-info-circle mr-2"></i>Informasi
                </button>
                <button id="settingsTab" class="tab-button px-6 py-4 font-medium text-sm text-gray-500 hover:text-blue-600">
                    <i class="fas fa-cog mr-2"></i>Pengaturan
                </button>
            </nav>
        </div>

        <div class="p-6 md:p-8">
            <div id="infoContent" class="tab-content active">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div class="space-y-6">
                        <h3 class="text-xl font-semibold text-gray-800 flex items-center">
                            <i class="fas fa-user-circle mr-3 text-blue-600"></i>Informasi Pribadi
                        </h3>

                        <div class="space-y-3">
                            <div class="flex items-start space-x-4 p-4 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors">
                                <div class="bg-blue-100 p-3 rounded-lg">
                                    <i class="fas fa-id-card text-blue-500"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">NIK</p>
                                    <p class="font-semibold text-gray-800 mt-1"><?php echo displayData($NIK); ?></p>
                                </div>
                            </div>

                            <div class="flex items-start space-x-4 p-4 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors">
                                <div class="bg-green-100 p-3 rounded-lg">
                                    <i class="fas fa-user text-green-500"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Nama Lengkap</p>
                                    <p class="font-semibold text-gray-800 mt-1"><?php echo displayData($NamaLengkap); ?></p>
                                </div>
                            </div>

                            <div class="flex items-start space-x-4 p-4 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors">
                                <div class="bg-red-100 p-3 rounded-lg">
                                    <i class="fas fa-map-marker-alt text-red-500"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Alamat</p>
                                    <p class="font-semibold text-gray-800 mt-1"><?php echo displayData($Alamat); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <h3 class="text-xl font-semibold text-gray-800 flex items-center">
                            <i class="fas fa-address-book mr-3 text-green-600"></i>Informasi Kontak
                        </h3>

                        <div class="space-y-3">
                            <div class="flex items-start space-x-4 p-4 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors">
                                <div class="bg-purple-100 p-3 rounded-lg">
                                    <i class="fas fa-phone text-purple-500"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Telepon</p>
                                    <p class="font-semibold text-gray-800 mt-1"><?php echo displayData($Telp); ?></p>
                                </div>
                            </div>

                            <div class="flex items-start space-x-4 p-4 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors">
                                <div class="bg-orange-100 p-3 rounded-lg">
                                    <i class="fas fa-envelope text-orange-500"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Email</p>
                                    <p class="font-semibold text-gray-800 mt-1"><?php echo displayData($Email); ?></p>
                                </div>
                            </div>

                            <div class="flex items-start space-x-4 p-4 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors">
                                <div class="bg-indigo-100 p-3 rounded-lg">
                                    <i class="fas fa-user-tag text-indigo-500"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Jenis Akun</p>
                                    <p class="font-semibold text-gray-800 mt-1"><?php echo htmlspecialchars($JenisAkun); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="settingsContent" class="tab-content hidden">
                <div class="space-y-6">
                    <h3 class="text-xl font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-cog mr-3 text-blue-600"></i>Pengaturan Akun
                    </h3>

                    <div class="bg-gray-50 rounded-lg p-6">
                        <h4 class="font-medium text-gray-800 mb-4">Keamanan</h4>

                        <div class="space-y-4">
                        <div class="flex justify-between items-center p-4 bg-white rounded-lg border border-gray-200">
    <div class="flex items-center space-x-4">
        <div class="bg-blue-100 p-3 rounded-lg">
            <i class="fas fa-key text-blue-500"></i>
        </div>
        <div>
            <p class="font-medium text-gray-800">Ganti Password</p>
            <p class="text-sm text-gray-500">Ubah password Anda secara berkala untuk menjaga keamanan.</p>
        </div>
    </div>
    <a href="<?= base_url('profile/gantiPassword') ?>" class="inline-block text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg transition-all">
        Ubah Password
    </a>
</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="photoModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-96 max-w-full mx-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold">Ganti Foto Profil</h3>
            <button id="closeModal" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form id="photoForm" enctype="multipart/form-data">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Foto</label>
                <input type="file" id="photoInput" name="fotoProfil" accept="image/*"
                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, JPEG. Maksimal 2MB</p>
            </div>

            <div id="photoPreview" class="mb-4 hidden">
                <img id="previewImage" class="w-32 h-32 object-cover rounded-lg mx-auto border">
            </div>

            <div class="flex space-x-3">
                <button type="button" id="deletePhotoBtn" class="px-4 py-2 text-red-600 bg-red-50 rounded-lg hover:bg-red-100 border border-red-200">
                    <i class="fas fa-trash mr-2"></i>Hapus Foto
                </button>
                <button type="button" id="cancelBtn" class="flex-1 px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">
                    Batal
                </button>
                <button type="submit" id="uploadBtn" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50">
                    Upload
                </button>
            </div>
        </form>
    </div>
</div>

<input type="file" id="hiddenFileInput" name="fotoProfil" accept="image/*" style="display: none;">

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tabs = document.querySelectorAll('.tab-button');
        const contents = document.querySelectorAll('.tab-content');

        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                tabs.forEach(t => {
                    t.classList.remove('active', 'text-blue-600', 'border-blue-600');
                    t.classList.add('text-gray-500');
                });

                contents.forEach(c => {
                    c.classList.add('hidden');
                    c.classList.remove('active');
                });

                this.classList.add('active', 'text-blue-600', 'border-blue-600');
                this.classList.remove('text-gray-500');

                const contentId = this.id.replace('Tab', 'Content');
                document.getElementById(contentId).classList.remove('hidden');
                document.getElementById(contentId).classList.add('active');
            });
        });

        const changePhotoBtn = document.getElementById('changePhotoBtn');
        const photoModal = document.getElementById('photoModal');
        const closeModal = document.getElementById('closeModal');
        const cancelBtn = document.getElementById('cancelBtn');
        const photoInput = document.getElementById('photoInput');
        const photoPreview = document.getElementById('photoPreview');
        const previewImage = document.getElementById('previewImage');
        const photoForm = document.getElementById('photoForm');
        const hiddenFileInput = document.getElementById('hiddenFileInput');
        const profileImage = document.getElementById('profileImage');
        const uploadBtn = document.getElementById('uploadBtn');

        changePhotoBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            photoModal.classList.remove('hidden');
            photoModal.classList.add('flex');
            if (profileImage.src && profileImage.src !== '<?= base_url('assets/img/default-profile.jpg') ?>') {
                previewImage.src = profileImage.src;
                photoPreview.classList.remove('hidden');
            } else {
                previewImage.src = '';
                photoPreview.classList.add('hidden');
            }
            uploadBtn.disabled = true;
        });

        function closePhotoModal() {
            photoModal.classList.add('hidden');
            photoModal.classList.remove('flex');
            photoPreview.classList.add('hidden');
            photoForm.reset();
            uploadBtn.disabled = true;
            uploadBtn.innerHTML = 'Upload';
        }

        closeModal.addEventListener('click', closePhotoModal);
        cancelBtn.addEventListener('click', closePhotoModal);

        photoInput.addEventListener('change', function(e) {
            if (this.files && this.files[0]) {
                const file = this.files[0];

                if (file.size > 2 * 1024 * 1024) {
                    Swal.fire({
                        icon: 'error',
                        title: 'File Terlalu Besar',
                        text: 'Ukuran file maksimal 2MB. Silakan pilih file yang lebih kecil.',
                        confirmButtonColor: '#3b82f6'
                    });
                    this.value = '';
                    photoPreview.classList.add('hidden');
                    uploadBtn.disabled = true;
                    return;
                }

                if (!file.type.match('image.*')) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Format File Tidak Valid',
                        text: 'Hanya file gambar yang diperbolehkan (JPG, PNG, JPEG).',
                        confirmButtonColor: '#3b82f6'
                    });
                    this.value = '';
                    photoPreview.classList.add('hidden');
                    uploadBtn.disabled = true;
                    return;
                }

                const reader = new FileReader();

                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    photoPreview.classList.remove('hidden');
                    uploadBtn.disabled = false;
                }

                reader.readAsDataURL(file);
            } else {
                photoPreview.classList.add('hidden');
                uploadBtn.disabled = true;
            }
        });

        const deletePhotoBtn = document.getElementById('deletePhotoBtn');

        if (deletePhotoBtn) {
            deletePhotoBtn.addEventListener('click', function() {
                Swal.fire({
                    title: 'Hapus Foto Profil?',
                    text: 'Apakah Anda yakin ingin menghapus foto profil Anda?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading state
                        Swal.fire({
                            title: 'Menghapus...',
                            text: 'Sedang menghapus foto profil Anda',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        fetch('<?= base_url('profile/deletePhoto') ?>', {
                                method: 'POST',
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.status === 'success') {
                                    profileImage.src = data.photo_url;
                                    closePhotoModal();

                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil!',
                                        text: data.message,
                                        confirmButtonColor: '#10b981',
                                        timer: 2000,
                                        showConfirmButton: false
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Gagal Menghapus',
                                        text: data.message || 'Terjadi kesalahan saat menghapus foto.',
                                        confirmButtonColor: '#3b82f6'
                                    });
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Terjadi Kesalahan',
                                    text: 'Gagal menghapus foto profil. Silakan coba lagi.',
                                    confirmButtonColor: '#3b82f6'
                                });
                            });
                    }
                });
            });
        }

        photoForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            Swal.fire({
                title: 'Mengupload...',
                text: 'Sedang mengupload foto profil Anda',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            uploadBtn.disabled = true;
            uploadBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Mengupload...';

            fetch('<?= base_url('profile/uploadPhoto') ?>', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        profileImage.src = data.photo_url;
                        closePhotoModal();

                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: data.message,
                            confirmButtonColor: '#10b981',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Upload Gagal',
                            text: data.message || 'Gagal mengupload foto. Silakan coba lagi.',
                            confirmButtonColor: '#3b82f6'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan',
                        text: 'Gagal mengupload foto profil. Silakan coba lagi.',
                        confirmButtonColor: '#3b82f6'
                    });
                })
                .finally(() => {
                    uploadBtn.disabled = false;
                    uploadBtn.innerHTML = 'Upload';
                });
        });

        profileImage.addEventListener('click', function() {
            photoModal.classList.remove('hidden');
            photoModal.classList.add('flex');

            if (profileImage.src && profileImage.src !== '<?= base_url('assets/img/default-profile.jpg') ?>') {
                previewImage.src = profileImage.src;
                photoPreview.classList.remove('hidden');
            } else {
                previewImage.src = '';
                photoPreview.classList.add('hidden');
            }
            uploadBtn.disabled = true;
            hiddenFileInput.click();
        });

        hiddenFileInput.addEventListener('change', function() {
            photoInput.files = this.files;
            const event = new Event('change');
            photoInput.dispatchEvent(event);
        });
    });
</script>