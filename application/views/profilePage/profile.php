<?php

$NamaLengkap = isset($profile->NamaLengkap) ? htmlspecialchars($profile->NamaLengkap) : 'Nama Pengguna';
$JenisAkun = isset($profile->JenisAkun) ? htmlspecialchars($profile->JenisAkun) : 'User';
$StatusAktivasi = isset($profile->StatusAktivasi) ? htmlspecialchars($profile->StatusAktivasi) : 'Aktif';
$FotoProfil = (!empty($profile->FotoProfil) && file_exists(FCPATH . 'uploads/profile/' . $profile->FotoProfil)) ? base_url('uploads/profile/' . $profile->FotoProfil) : base_url('assets/img/default-profile.jpg');
$NIK = isset($profile->NIK) ? htmlspecialchars($profile->NIK) : '';
$Alamat = isset($profile->Alamat) ? htmlspecialchars($profile->Alamat) : '';
$Telp = isset($profile->Telp) ? htmlspecialchars($profile->Telp) : '';
$Email = isset($profile->Email) ? htmlspecialchars($profile->Email) : '';
$latitude = isset($profile->latitude_daftar) ? $profile->latitude_daftar : null;
$longitude = isset($profile->longitude_daftar) ? $profile->longitude_daftar : null;


function displayData($data, $placeholder = 'Belum diisi') {
    return !empty(trim($data)) ? $data : '<span class="text-gray-400 italic">' . $placeholder . '</span>';
}
?>

<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-xl shadow-xl overflow-hidden border border-gray-200">
        <div class="relative bg-gradient-to-br from-slate-900 to-indigo-900 px-8 py-12">
            <div class="flex flex-col md:flex-row items-center space-y-4 md:space-y-0 md:space-x-6">
                <div class="relative group w-24 h-24 md:w-32 md:h-32 rounded-full border-4 border-white/50 shadow-lg overflow-hidden">
                    <img id="profileImage" src="<?= $FotoProfil; ?>" alt="Foto Profil" class="w-full h-full object-cover">
                    <button id="changePhotoBtn" class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 flex items-center justify-center transition-all duration-300 cursor-pointer">
                        <i class="fas fa-camera text-white text-2xl opacity-0 group-hover:opacity-100 transition-opacity"></i>
                    </button>
                </div>
                <div class="text-white text-center md:text-left">
                    <h2 class="text-2xl md:text-3xl font-bold"><?= $NamaLengkap; ?></h2>
                    <p class="text-blue-200 text-lg"><?= $JenisAkun; ?></p>
                    <span class="mt-2 inline-block bg-green-600/90 text-white px-3 py-1 rounded-full text-sm">
                        <i class="fas fa-check-circle mr-1"></i> <?= $StatusAktivasi; ?>
                    </span>
                </div>
            </div>
        </div>

        <div class="border-b border-gray-200">
            <nav class="flex overflow-x-auto">
                <button id="infoTab" class="tab-button active px-6 py-4 font-medium text-sm border-b-2 border-blue-600 text-blue-600">
                    <i class="fas fa-info-circle mr-2"></i>Informasi Detail
                </button>
                <button id="settingsTab" class="tab-button px-6 py-4 font-medium text-sm text-gray-500 hover:text-blue-600 border-b-2 border-transparent">
                    <i class="fas fa-cog mr-2"></i>Pengaturan
                </button>
            </nav>
        </div>

        <div class="p-6 md:p-8">
            <div id="infoContent" class="tab-content">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div class="space-y-6">
                        <h3 class="text-xl font-semibold text-gray-800"><i class="fas fa-user-circle mr-3 text-blue-600"></i>Informasi Pribadi</h3>
                        <div class="space-y-3">
                            <div class="flex items-start space-x-4"><i class="fas fa-id-card mt-1 text-blue-500 w-5 text-center"></i><div><p class="text-xs text-gray-500">NIK</p><p class="font-semibold text-gray-800"><?= displayData($NIK); ?></p></div></div>
                            <div class="flex items-start space-x-4"><i class="fas fa-user mt-1 text-green-500 w-5 text-center"></i><div><p class="text-xs text-gray-500">Nama Lengkap</p><p class="font-semibold text-gray-800"><?= displayData($NamaLengkap); ?></p></div></div>
                            <div class="flex items-start space-x-4"><i class="fas fa-map-marker-alt mt-1 text-red-500 w-5 text-center"></i><div><p class="text-xs text-gray-500">Alamat</p><p class="font-semibold text-gray-800"><?= displayData($Alamat); ?></p></div></div>
                        </div>
                    </div>
                    <div class="space-y-6">
                        <h3 class="text-xl font-semibold text-gray-800"><i class="fas fa-address-book mr-3 text-green-600"></i>Informasi Kontak</h3>
                        <div class="space-y-3">
                            <div class="flex items-start space-x-4"><i class="fas fa-phone mt-1 text-purple-500 w-5 text-center"></i><div><p class="text-xs text-gray-500">Telepon</p><p class="font-semibold text-gray-800"><?= displayData($Telp); ?></p></div></div>
                            <div class="flex items-start space-x-4"><i class="fas fa-envelope mt-1 text-orange-500 w-5 text-center"></i><div><p class="text-xs text-gray-500">Email</p><p class="font-semibold text-gray-800"><?= displayData($Email); ?></p></div></div>
                        </div>
                    </div>
                </div>
                <div class="pt-8 mt-8 border-t">
                    <h3 class="text-xl font-semibold text-gray-800"><i class="fas fa-map-marked-alt mr-3 text-purple-600"></i>Lokasi Tersimpan</h3>
                    <div class="mt-4 rounded-lg overflow-hidden border">
                        <?php if ($latitude && $longitude): ?>
                            <div id="map-display" style="height: 350px; width: 100%;"></div>
                        <?php else: ?>
                            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-6 text-center">
                                <p class="font-semibold text-yellow-800">Lokasi Belum Diatur</p>
                                <a href="<?= base_url('profile/edit') ?>" class="mt-2 inline-block bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded-lg">Atur Lokasi Sekarang</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div id="settingsContent" class="tab-content hidden">
                 <div class="space-y-6">
                    <h3 class="text-xl font-semibold text-gray-800 flex items-center"><i class="fas fa-cog mr-3 text-blue-600"></i>Pengaturan Akun</h3>
                    <div class="bg-gray-50 rounded-lg p-6 space-y-4">
                        <div class="flex justify-between items-center p-4 bg-white rounded-lg border">
                            <div><p class="font-medium text-gray-800">Edit Profil</p><p class="text-sm text-gray-500">Perbarui data pribadi, kontak, dan lokasi Anda.</p></div>
                            <a href="<?= base_url('profile/edit') ?>" class="text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg">Edit</a>
                        </div>
                        <div class="flex justify-between items-center p-4 bg-white rounded-lg border">
                            <div><p class="font-medium text-gray-800">Ganti Password</p><p class="text-sm text-gray-500">Ubah password Anda secara berkala.</p></div>
                            <a href="<?= base_url('profile/gantiPassword') ?>" class="text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg">Ubah</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="photoModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center" style="z-index: 1050;">    <div class="bg-white rounded-lg p-6 w-96 max-w-full mx-4">
        <div class="flex justify-between items-center mb-4"><h3 class="text-lg font-semibold">Ganti Foto Profil</h3><button id="closeModal" class="text-gray-500 hover:text-gray-700"><i class="fas fa-times"></i></button></div>
        <form id="photoForm" enctype="multipart/form-data">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Foto</label>
                <input type="file" id="photoInput" name="fotoProfil" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG. Maks 2MB</p>
            </div>
            <div id="photoPreview" class="mb-4 hidden"><img id="previewImage" class="w-32 h-32 object-cover rounded-lg mx-auto border"></div>
            <div class="flex space-x-3">
                <button type="button" id="deletePhotoBtn" class="px-4 py-2 text-red-600 bg-red-50 rounded-lg hover:bg-red-100 border border-red-200"><i class="fas fa-trash mr-2"></i>Hapus</button>
                <button type="button" id="cancelBtn" class="flex-1 px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">Batal</button>
                <button type="submit" id="uploadBtn" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50">Upload</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    

    const tabs = document.querySelectorAll('.tab-button');
    const contents = document.querySelectorAll('.tab-content');
    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            tabs.forEach(t => {
                t.classList.remove('active', 'text-blue-600', 'border-blue-600');
                t.classList.add('text-gray-500', 'border-transparent');
            });
            contents.forEach(c => c.classList.add('hidden'));
            this.classList.add('active', 'text-blue-600', 'border-blue-600');
            this.classList.remove('text-gray-500', 'border-transparent');
            document.getElementById(this.id.replace('Tab', 'Content')).classList.remove('hidden');
        });
    });


    const mapDisplayDiv = document.getElementById('map-display');
    if (mapDisplayDiv) {
        const lat = <?= json_encode($latitude) ?? 'null' ?>;
        const lng = <?= json_encode($longitude) ?? 'null' ?>;
        if(lat && lng) {
            const mapDisplay = L.map(mapDisplayDiv, { dragging: false, scrollWheelZoom: false }).setView([lat, lng], 16);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(mapDisplay);
            L.marker([lat, lng]).addTo(mapDisplay);

            setTimeout(() => mapDisplay.invalidateSize(), 10);
        }
    }
    

    const changePhotoBtn = document.getElementById('changePhotoBtn');
    const photoModal = document.getElementById('photoModal');
    if(changePhotoBtn && photoModal) {
        const closeModal = document.getElementById('closeModal');
        const cancelBtn = document.getElementById('cancelBtn');
        const photoInput = document.getElementById('photoInput');
        const photoPreview = document.getElementById('photoPreview');
        const previewImage = document.getElementById('previewImage');
        const photoForm = document.getElementById('photoForm');
        const profileImage = document.getElementById('profileImage');
        const uploadBtn = document.getElementById('uploadBtn');
        const deletePhotoBtn = document.getElementById('deletePhotoBtn');

        const openModal = () => {
            photoModal.classList.remove('hidden');
            photoModal.classList.add('flex');
            uploadBtn.disabled = true;
        };

        const closeModalFunc = () => {
            photoModal.classList.add('hidden');
            photoModal.classList.remove('flex');
            photoForm.reset();
            photoPreview.classList.add('hidden');
        };

        changePhotoBtn.addEventListener('click', openModal);
        closeModal.addEventListener('click', closeModalFunc);
        cancelBtn.addEventListener('click', closeModalFunc);

        photoInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const file = this.files[0];
                if (file.size > 2 * 1024 * 1024) {
                    Swal.fire('File Terlalu Besar', 'Ukuran file maksimal 2MB.', 'error');
                    this.value = ''; return;
                }
                const reader = new FileReader();
                reader.onload = e => {
                    previewImage.src = e.target.result;
                    photoPreview.classList.remove('hidden');
                    uploadBtn.disabled = false;
                }
                reader.readAsDataURL(file);
            }
        });

        photoForm.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({ title: 'Mengupload...', allowOutsideClick: false, didOpen: () => { Swal.showLoading() } });
            fetch('<?= base_url('profile/uploadPhoto') ?>', {
                method: 'POST',
                body: new FormData(this),
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    profileImage.src = data.photo_url;
                    Swal.fire('Berhasil!', data.message, 'success');
                    closeModalFunc();
                } else { Swal.fire('Gagal', data.message, 'error'); }
            }).catch(() => Swal.fire('Error', 'Gagal terhubung ke server.', 'error'));
        });

        deletePhotoBtn.addEventListener('click', function() {
            Swal.fire({ title: 'Hapus Foto Profil?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', confirmButtonText: 'Ya, Hapus!'})
            .then(result => {
                if (result.isConfirmed) {
                    fetch('<?= base_url('profile/deletePhoto') ?>', { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'success') {
                            profileImage.src = data.photo_url;
                            Swal.fire('Dihapus!', 'Foto profil telah dihapus.', 'success');
                            closeModalFunc();
                        } else { Swal.fire('Gagal', data.message, 'error'); }
                    });
                }
            });
        });
    }
});
</script>