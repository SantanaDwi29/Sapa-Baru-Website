<div class="bg-white shadow-xl rounded-lg mb-10">
    <div class="p-6">
        <div class="border-b pb-4">
            <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-user-shield text-indigo-950 mr-3"></i>
                <span id="form-title-pj">Form Tambah Data Penanggung Jawab</span>
            </h1>
        </div>
        <form id="pj-form" action="<?= site_url('PenanggungJawab/save') ?>" method="POST">
            <input type="hidden" name="id_daftar" id="id_daftar_pj" value="">
            <input type="hidden" name="latitude_daftar" id="latitude_pj">
            <input type="hidden" name="longitude_daftar" id="longitude_pj">

            <div class="mt-6 bg-blue-50 border-l-4 border-blue-400 text-blue-800 p-4" role="alert">
                <p class="font-bold flex items-center"><i class="fas fa-info-circle mr-2"></i>Informasi Penting</p>
                <ul class="list-disc list-inside text-sm mt-2 space-y-1">
                    <li>Pastikan semua data yang Anda masukkan, terutama <strong>NIK</strong> dan <strong>Email</strong>, sudah benar dan valid.</li>
                    <li>Untuk hasil pencarian peta yang akurat, masukkan <strong>alamat selengkap mungkin</strong>.</li>
                    <li>Ketik alamat lalu tekan tombol <strong>'Enter'</strong> pada keyboard untuk memindahkan peta secara otomatis.</li>
                </ul>
            </div>

            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                <div class="space-y-1">
                    <label for="namaLengkap_pj" class="block text-lg font-medium text-gray-700">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="namaLengkap" id="namaLengkap_pj" required class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Masukkan nama lengkap">
                </div>
                <div class="space-y-1">
                    <label for="nik_pj" class="block text-lg font-medium text-gray-700">NIK <span class="text-red-500">*</span></label>
                    <input type="text" name="nik" id="nik_pj" required maxlength="16" class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Masukkan 16 digit NIK" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                </div>
                <div class="space-y-1">
                    <label for="telp_pj" class="block text-lg font-medium text-gray-700">No Telepon <span class="text-red-500">*</span></label>
                    <input type="tel" name="telp" id="telp_pj" required maxlength="13" class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Contoh: 081234567890" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                </div>
                <div class="space-y-1">
                    <label for="email_pj" class="block text-lg font-medium text-gray-700">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" id="email_pj" required class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Masukkan email yang valid">
                </div>
                <div class="md:col-span-2 space-y-1">
                    <label for="alamat_pj" class="block text-lg font-medium text-gray-700">Alamat <span class="text-red-500">*</span></label>
                    <textarea name="alamat" id="alamat_pj" required rows="3" class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Ketik alamat lengkap, lalu tekan Enter..."></textarea>
                </div>
                <div class="md:col-span-2 space-y-1">
                    <label class="block text-lg font-medium text-gray-700">Pilih Lokasi di Peta <span class="text-red-500">*</span></label>
                    <div id="map" style="height: 400px; width: 100%;" class="rounded-lg border-2 border-gray-200 z-0"></div>
                </div>
                <div class="space-y-1">
                    <label for="password_pj" class="block text-lg font-medium text-gray-700">Password</label>
                    <input type="password" name="password" id="password_pj" class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Isi untuk mengubah password">
                    <p class="text-sm text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah password.</p>
                </div>
                <div class="space-y-1">
                    <label for="jenisAkun_pj" class="block text-lg font-medium text-gray-700">Jenis Akun</label>
                    <input type="text" name="jenisAkun" id="jenisAkun_pj" value="Penanggung Jawab" readonly class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-lg bg-gray-100 cursor-not-allowed">
                </div>
            </div>
            
            <div class="flex justify-end space-x-4 mt-8">
                <button type="button" id="reset-btn-pj" class="px-6 py-2.5 text-lg bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">Batal</button>
                <button type="submit" class="px-6 py-2.5 text-lg bg-blue-600 text-white rounded-lg hover:bg-blue-700">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>

function editUserPJ(id) {
    document.getElementById('form-title-pj').textContent = 'Form Edit Data Penanggung Jawab';
    fetch(`<?= site_url('PenanggungJawab/get/') ?>${id}`)
        .then(response => { if (!response.ok) throw new Error('Data tidak ditemukan di server.'); return response.json(); })
        .then(data => {
            document.getElementById('id_daftar_pj').value = data.id_daftar;
            document.getElementById('namaLengkap_pj').value = data.NamaPJ;
            document.getElementById('nik_pj').value = data.NIK;
            document.getElementById('telp_pj').value = data.Telp;
            document.getElementById('email_pj').value = data.Email;
            document.getElementById('alamat_pj').value = data.Alamat;
            document.getElementById('jenisAkun_pj').value = data.JenisAkun;
            document.getElementById('password_pj').value = '';

            const map = window.mapInstance;
            const marker = window.markerInstance;
            const latInput = document.getElementById('latitude_pj');
            const lngInput = document.getElementById('longitude_pj');

            if (data.latitude_daftar && data.longitude_daftar) {
                const userPosition = L.latLng(data.latitude_daftar, data.longitude_daftar);
                map.setView(userPosition, 16);
                marker.setLatLng(userPosition);
                latInput.value = data.latitude_daftar;
                lngInput.value = data.longitude_daftar;
            } else {
                const defaultPosition = L.latLng(-8.40824, 115.18802); // Koordinat Bali
                map.setView(defaultPosition, 13);
                marker.setLatLng(defaultPosition);
                latInput.value = defaultPosition.lat.toFixed(6);
                lngInput.value = defaultPosition.lng.toFixed(6);
            }
            
            document.getElementById('pj-form').scrollIntoView({ behavior: 'smooth', block: 'start' });
            Swal.fire({ icon: 'success', title: 'Mode Edit Aktif', text: 'Silakan ubah data yang diinginkan.', timer: 2000, showConfirmButton: false });
        })
        .catch(error => { console.error('Error fetching data:', error); Swal.fire({ icon: 'error', title: 'Gagal Memuat Data', text: error.message }); });
}

function deleteUserPJ(id) {
    Swal.fire({
        title: 'Anda Yakin?',
        text: "Data penanggung jawab ini akan dihapus secara permanen.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = `<?= site_url('PenanggungJawab/delete/') ?>${id}`;
        }
    });
}

document.addEventListener('DOMContentLoaded', function () {

    const defaultLat = -8.40824; 
    const defaultLng = 115.18802;

    window.mapInstance = L.map('map').setView([defaultLat, defaultLng], 13);
    window.markerInstance = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(window.mapInstance);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(window.mapInstance);

    const latInput = document.getElementById('latitude_pj');
    const lngInput = document.getElementById('longitude_pj');

    function updateCoordinates(latlng) {
        latInput.value = latlng.lat.toFixed(6);
        lngInput.value = latlng.lng.toFixed(6);
    }
    
    updateCoordinates(window.markerInstance.getLatLng());

    window.markerInstance.on('dragend', e => updateCoordinates(e.target.getLatLng()));
    window.mapInstance.on('click', e => {
        window.markerInstance.setLatLng(e.latlng);
        updateCoordinates(e.latlng);
    });
    
    const alamatInput = document.getElementById('alamat_pj');
    
    async function geocodeAddress() {
        const address = alamatInput.value;
        if (address.length < 10) {
             Swal.fire({ icon: 'warning', title: 'Alamat terlalu pendek', text: 'Ketik alamat yang lebih spesifik.' });
            return;
        }

        const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}&countrycodes=ID`;

        try {
            const response = await fetch(url);
            const data = await response.json();
            if (data && data.length > 0) {
                const { lat, lon } = data[0]; 
                const newPosition = L.latLng(lat, lon);
                
                window.mapInstance.flyTo(newPosition, 16); 
                window.markerInstance.setLatLng(newPosition);
                updateCoordinates(newPosition);
            } else {
                Swal.fire({ icon: 'error', title: 'Alamat tidak ditemukan' });
            }
        } catch (error) {
            console.error('Geocoding error:', error);
            Swal.fire({ icon: 'error', title: 'Gagal mencari alamat' });
        }
    }

    alamatInput.addEventListener('keydown', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault(); 
            geocodeAddress(); 
        }
    });

    const form = document.getElementById('pj-form');
    const resetButton = document.getElementById('reset-btn-pj');
    if (form && resetButton) {
        resetButton.addEventListener('click', () => {
            form.reset();
            document.getElementById('id_daftar_pj').value = '';
            document.getElementById('form-title-pj').textContent = 'Form Tambah Data Penanggung Jawab';
            
            const defaultPosition = L.latLng(defaultLat, defaultLng);
            window.mapInstance.setView(defaultPosition, 13);
            window.markerInstance.setLatLng(defaultPosition);
            updateCoordinates(defaultPosition);

            Swal.fire({ icon: 'info', title: 'Form Direset', timer: 1500, showConfirmButton: false });
        });
    }
});
</script>