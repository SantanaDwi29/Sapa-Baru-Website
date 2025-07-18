

<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto bg-white rounded-xl shadow-xl overflow-hidden border border-gray-200">
        
        <div class="bg-gradient-to-br from-slate-900 to-indigo-900 px-6 py-5 md:px-8">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-white flex items-center">
                    <i class="fas fa-key mr-3"></i>Ganti Password
                </h2>
                <a href="<?= base_url('profile') ?>"
                    class="bg-white/10 hover:bg-white/20 text-white px-4 py-2 rounded-lg backdrop-blur-sm transition-all flex items-center text-sm">
                    <i class="fas fa-times mr-2"></i> Batal
                </a>
            </div>
        </div>
        
        <div class="p-6 md:p-8">
            <form id="gantiPasswordForm" action="<?= base_url('profile/proses_ganti_password') ?>" method="POST">
                
                <p class="text-gray-600 mb-8">Untuk keamanan akun Anda, gunakan password yang kuat dan jangan bagikan kepada siapa pun.</p>

                <div class="space-y-6">
                    
                    <div>
                        <label for="password_lama" class="block text-sm font-medium text-gray-700 mb-2">Password Lama</label>
                        <div class="relative">
                            <input type="password" name="password_lama" id="password_lama" required
                                class="block w-full px-4 py-3 pr-10 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors">
                            <span class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer toggle-password-visibility" data-target="password_lama">
                                <i class="fas fa-eye-slash text-gray-400"></i>
                            </span>
                        </div>
                    </div>

                    <div>
                        <label for="password_baru" class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
                         <div class="relative">
                            <input type="password" name="password_baru" id="password_baru" required minlength="8"
                                class="block w-full px-4 py-3 pr-10 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                                placeholder="Minimal 8 karakter">
                            <span class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer toggle-password-visibility" data-target="password_baru">
                                <i class="fas fa-eye-slash text-gray-400"></i>
                            </span>
                        </div>
                    </div>

                    <div>
                        <label for="konfirmasi_password" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password Baru</label>
                        <div class="relative">
                            <input type="password" name="konfirmasi_password" id="konfirmasi_password" required
                                class="block w-full px-4 py-3 pr-10 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors">
                            <span class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer toggle-password-visibility" data-target="konfirmasi_password">
                                <i class="fas fa-eye-slash text-gray-400"></i>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-200 flex flex-col sm:flex-row-reverse sm:items-center">
                    <button type="submit"
                        class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                        <i class="fas fa-save mr-2"></i>Simpan Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    

    const form = document.getElementById('gantiPasswordForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault(); 
            Swal.fire({
                title: 'Simpan Password Baru?',
                text: "Apakah Anda yakin ingin melanjutkan?",
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


    const togglePasswordIcons = document.querySelectorAll('.toggle-password-visibility');
    
    togglePasswordIcons.forEach(item => {
        item.addEventListener('click', function () {

            const targetId = this.dataset.target;
            const passwordInput = document.getElementById(targetId);
            

            const icon = this.querySelector('i');


            if (passwordInput.type === 'password') {

                passwordInput.type = 'text';

                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            } else {

                passwordInput.type = 'password';

                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        });
    });

});
</script>