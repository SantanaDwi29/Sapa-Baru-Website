<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sapa Baru</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="icon" href="<?php echo base_url('assets/img/logo1.png'); ?>">
    <style>
        .hero-bg {
            background-color: #f8fafc;
        }

        .hero-image {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
        }

        @keyframes blink {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0;
            }
        }

        .animate-blink {
            animation: blink 1s infinite;
        }
    </style>
</head>

<body class="font-sans bg-gray-50">
    <div class="min-h-screen hero-bg">
        <nav class="container mx-auto py-6 px-4 flex justify-between items-center">

        </nav>

        <div class="container mx-auto px-4 py-16 md:py-24">
            <div class="flex flex-col md:flex-row items-center">
                <div class="w-full md:w-1/2 md:pr-12 mb-8 md:mb-0">
                    <!-- <h1 class="text-4xl md:text-5xl font-bold text-indigo-900 mb-4">Sapa Baru</h1> -->
                    <h1 id="typing-text" class="text-5xl md:text-6xl font-bold text-indigo-900 mb-4">
                        <span id="typed-text"></span><span id="cursor" class="animate-blink">|</span>
                    </h1>


                    <p class="text-gray-600 text-2xl mb-8">
                        Selamat datang di <strong>Sapa Baru!</strong> Kami menyediakan platform inovatif untuk mendata dan memantau penduduk pendatang di wilayah tertentu, 
                        sehingga kepala lingkungan dapat mengetahui informasi terkini tentang penduduk baru dan tempat tinggalnya. Dengan <strong>Sapa Baru</strong>, kami
                         bertujuan meningkatkan efektivitas pelayanan dan keamanan di wilayah tersebut, serta memperkuat hubungan antara penduduk dan pemerintah setempat. </p>
                    <a href="<?php echo base_url('loginHalaman'); ?>" class="bg-indigo-500 hover:bg-indigo-600 text-white font-medium py-3 px-8 rounded-full inline-block transition-colors duration-300 text-xl">
                        Masuk
                    </a>
                    <a href="<?php echo base_url('register'); ?>" class="bg-gray-200 hover:bg-gray-300 text-slate-800 font-medium py-3 px-8 rounded-full inline-block transition-colors duration-300 text-xl">
                        Daftar Akun Baru
                    </a>
                </div>
                <div class="w-full md:w-1/2 flex justify-center">
                    <img src="<?php echo base_url('assets/img/pageawal.png'); ?>" alt="Landing Page Sapa Baru" class="hero-image">
                </div>
            </div>
        </div>
    </div>


</body>
<script>
    const text = "Sapa Baru...";
    const typedTextElement = document.getElementById("typed-text");
    let index = 0;

    function typeLoop() {
        if (index < text.length) {
            typedTextElement.textContent += text.charAt(index);
            index++;
            setTimeout(typeLoop, 100);
        } else {
            setTimeout(() => {
                typedTextElement.textContent = "";
                index = 0;
                typeLoop();
            }, 2000);
        }
    }

    window.addEventListener('DOMContentLoaded', typeLoop);
</script>

</html>