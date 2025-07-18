<?php

$NamaLengkap = isset($profile->NamaLengkap) ? $profile->NamaLengkap : '';
    $NIK = isset($profile->NIK) ? $profile->NIK : '';
    $Alamat = isset($profile->Alamat) ? $profile->Alamat : '';
    $Telp = isset($profile->Telp) ? $profile->Telp : '';
    $Email = isset($profile->Email) ? $profile->Email : '';
    $JenisAkun = isset($profile->JenisAkun) ? $profile->JenisAkun : '';

    $latitude = isset($profile->Latitude_daftar) ? $profile->Latitude_daftar : '';
    $longitude = isset($profile->Longitude_daftar) ? $profile->Longitude_daftar : '';
?>


<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-xl shadow-xl overflow-hidden border border-gray-200">
        <div class="bg-gradient-to-br from-slate-900 to-indigo-900 px-8 py-6">
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-bold text-white flex items-center"><i class="fas fa-edit mr-3"></i>Edit Profile</h2>
                <a href="<?= base_url('profile') ?>" class="bg-white/10 hover:bg-white/20 text-white px-4 py-2 rounded-lg"><i class="fas fa-arrow-left mr-2"></i> Kembali</a>
            </div>
        </div>

        <div class="p-6 md:p-8">
            <?php if ($this->session->flashdata('success')): ?>
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert"><p><?= $this->session->flashdata('success') ?></p></div>
            <?php endif; ?>
            <?php if ($this->session->flashdata('error')): ?>
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert"><p><?= $this->session->flashdata('error') ?></p></div>
            <?php endif; ?>

            <form id="editProfileForm" action="<?= base_url('profile/save') ?>" method="POST" class="space-y-6">
                <input type="hidden" name="latitude_daftar" id="latitude_input" value="<?= htmlspecialchars($latitude) ?>">
                <input type="hidden" name="longitude_daftar" id="longitude_input" value="<?= htmlspecialchars($longitude) ?>">

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-x-8 gap-y-6">
                    <div class="space-y-6">
                        <h3 class="text-xl font-semibold text-gray-800 border-b pb-3"><i class="fas fa-user-circle mr-3 text-blue-600"></i>Informasi Pribadi</h3>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">NIK</label>
                            <input type="text" value="<?= htmlspecialchars($NIK) ?>" class="mt-1 w-full px-4 py-3 border rounded-lg bg-gray-100" readonly>
                        </div>
                        <div>
                            <label for="namaLengkap" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                            <input type="text" id="namaLengkap" name="namaLengkap" value="<?= htmlspecialchars($NamaLengkap) ?>" class="mt-1 w-full px-4 py-3 border rounded-lg" required>
                        </div>
                        <div>
                            <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat</label>
                            <textarea id="alamat" name="alamat" rows="4" class="mt-1 w-full px-4 py-3 border rounded-lg" required><?= htmlspecialchars($Alamat) ?></textarea>
                            <button type="button" id="search-address-btn" class="mt-2 px-4 py-2 text-sm bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                                <i class="fas fa-search-location mr-2"></i>Cari Alamat di Peta
                            </button>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <h3 class="text-xl font-semibold text-gray-800 border-b pb-3"><i class="fas fa-address-book mr-3 text-green-600"></i>Informasi Kontak</h3>
                        <div>
                            <label for="telp" class="block text-sm font-medium text-gray-700">Telepon</label>
                            <input type="tel" id="telp" name="telp" value="<?= htmlspecialchars($Telp) ?>" class="mt-1 w-full px-4 py-3 border rounded-lg" required>
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" id="email" name="email" value="<?= htmlspecialchars($Email) ?>" class="mt-1 w-full px-4 py-3 border rounded-lg" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jenis Akun</label>
                            <input type="text" value="<?= htmlspecialchars($JenisAkun) ?>" class="mt-1 w-full px-4 py-3 border rounded-lg bg-gray-100" readonly>
                        </div>
                    </div>
                </div>

                <div class="pt-6 border-t col-span-1 lg:col-span-2">
                    <h3 class="text-xl font-semibold text-gray-800"><i class="fas fa-map-marked-alt mr-3 text-purple-600"></i>Tentukan Lokasi di Peta</h3>
                    <p class="text-sm text-gray-500 mt-1 mb-4">Klik tombol "Cari Alamat" atau klik langsung pada peta untuk menentukan lokasi.</p>
                    <div id="map-edit" style="height: 400px; width: 100%; border-radius: 0.5rem; z-index:0;"></div>
                </div>

                <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t">
                    <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-lg flex items-center justify-center">
                        <i class="fas fa-save mr-2"></i>Simpan Perubahan
                    </button>
                    <button type="reset" class="sm:flex-none bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-3 px-6 rounded-lg flex items-center justify-center">
                        <i class="fas fa-undo mr-2"></i>Reset Form
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('editProfileForm');
    const resetButton = form.querySelector('button[type="reset"]');


    const latField = document.getElementById('latitude_input');
    const lngField = document.getElementById('longitude_input');
    const alamatInput = document.getElementById('alamat');
    

    const initialLat = latField.value ? parseFloat(latField.value) : -8.650000;
    const initialLng = lngField.value ? parseFloat(lngField.value) : 115.216667;

    window.mapInstance = L.map('map-edit').setView([initialLat, initialLng], 14);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(window.mapInstance);
    window.markerInstance = L.marker([initialLat, initialLng], { draggable: true }).addTo(window.mapInstance);

    function updateInputs(latlng) {
        latField.value = latlng.lat.toFixed(8);
        lngField.value = latlng.lng.toFixed(8);
    }

    window.markerInstance.on('dragend', e => updateInputs(e.target.getLatLng()));
    window.mapInstance.on('click', e => {
        window.markerInstance.setLatLng(e.latlng);
        updateInputs(e.latlng);
    });

    async function geocodeAddress() {
        const address = alamatInput.value;
        if (address.length < 5) {
            Swal.fire('Alamat Terlalu Pendek', 'Ketik alamat yang lebih spesifik.', 'warning');
            return;
        }
        const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}&countrycodes=ID`;
        const response = await fetch(url);
        const data = await response.json();
        if (data && data.length > 0) {
            const newPosition = L.latLng(data[0].lat, data[0].lon);
            window.mapInstance.flyTo(newPosition, 16);
            window.markerInstance.setLatLng(newPosition);
            updateInputs(newPosition);
        } else {
            Swal.fire('Gagal', 'Alamat tidak dapat ditemukan di peta.', 'error');
        }
    }
    document.getElementById('search-address-btn').addEventListener('click', geocodeAddress);


    form.addEventListener('submit', function(e) {
        e.preventDefault();

        updateInputs(window.markerInstance.getLatLng());
        
        Swal.fire({
            title: 'Simpan Perubahan?', text: "Data profil Anda akan diperbarui.", icon: 'question',
            showCancelButton: true, confirmButtonText: 'Ya, Simpan!', cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) form.submit();
        });
    });

    resetButton.addEventListener('click', function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Reset Form?', text: "Semua perubahan akan dibatalkan.", icon: 'warning',
            showCancelButton: true, confirmButtonText: 'Ya, Reset!'
        }).then((result) => {
            if (result.isConfirmed) {
                form.reset();

                window.mapInstance.setView([initialLat, initialLng], 14);
                window.markerInstance.setLatLng([initialLat, initialLng]);
                updateInputs({ lat: initialLat, lng: initialLng });
            }
        });
    });
});
</script>