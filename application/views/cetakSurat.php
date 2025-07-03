<?php

if (!function_exists('format_tanggal_indonesia')) {
    function format_tanggal_indonesia($tanggal) {
        $bulan = array (
            1 =>   'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        );
        $pecahkan = explode('-', date('Y-m-d', strtotime($tanggal)));
        
        return $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
    }
}


if (!function_exists('base_url')) {
    function base_url($path = '') {
        // Sesuaikan dengan URL root proyek Anda
        $base_url = "http://" . $_SERVER['HTTP_HOST'] . "/nama_folder_proyek_anda/";
        return $base_url . $path;
    }
}
// ==================================================================

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Pengantar - <?= htmlspecialchars($surat['NamaLengkap']) ?></title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            line-height: 1.5;
            margin: 0.7in;
            color: #000;
            background: white;
        }
        
        /* Kop Surat */
        .kop-surat-container {
            display: table;
            width: 100%;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #000;
        }
        
        .logo-section {
            display: table-cell;
            width: 90px;
            vertical-align: middle;
        }
        
        .logo {
            width: 90px;
            height: 90px;
        }
        
        .kop-surat-text {
            display: table-cell;
            text-align: center;
            vertical-align: middle;
            padding-left: 20px;
        }
        
        .kop-surat-text h1 {
            font-size: 16pt;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
            line-height: 1.2;
        }
        
        .kop-surat-text h2 {
            font-size: 14pt;
            font-weight: bold;
            margin: 2px 0;
            text-transform: uppercase;
            line-height: 1.2;
        }
        
        .kop-surat-text p {
            font-size: 10pt;
            margin: 8px 0 0;
            line-height: 1.3;
        }
        
        /* Judul dan Nomor Surat */
        .judul-surat {
            text-align: center;
            font-weight: bold;
            text-decoration: underline;
            font-size: 14pt;
            margin: 25px 0 5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .nomor-surat {
            text-align: center;
            margin-bottom: 25px;
            font-size: 11pt;
            font-weight: normal;
        }
        
        /* Isi Surat */
        .isi-surat {
            margin-top: 20px;
        }
        
        .isi-surat p {
            text-align: justify;
            margin-bottom: 15px;
            line-height: 1.6;
        }
        
        .pembuka {
            text-indent: 30px;
            margin-bottom: 20px;
        }
        
        .penutup {
            text-indent: 30px;
            margin-top: 10px;
            margin-bottom: 15px;
        }
        
        /* Data Pendatang */
        .data-pendatang {
            margin: 20px 0;
            margin-left: 30px;
        }
        
        .data-pendatang table {
            border-collapse: collapse;
            width: 100%;
            max-width: 600px;
        }
        
        .data-pendatang td {
            padding: 2px 0;
            vertical-align: top;
            line-height: 1.4;
        }
        
        .data-pendatang .label {
            width: 160px;
            font-weight: normal;
            padding-right: 10px;
        }
        
        .data-pendatang .separator {
            width: 15px;
            text-align: left;
            padding-right: 10px;
        }
        
        .data-pendatang .data {
            word-wrap: break-word;
            font-weight: normal;
        }
        
        .data-pendatang .nama {
            font-weight: bold;
            text-transform: uppercase;
        }
        
        /* Keperluan */
        .keperluan {
            font-weight: bold;
            text-transform: uppercase;
        }
        
        /* Tanda Tangan */
        .blok-tanda-tangan {
            width: 45%;
            margin-top: 10px;
            float: right;
            text-align: center;
        }
        .blok-tanda-tangan p {
            margin: 0;
        }
        .area-qr-dan-tanda-tangan {
            height: 110px;
            margin-top: 10px;
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .area-qr-dan-tanda-tangan img {
            width: 100px;
            height: 100px;
        }
        
        .nama-pejabat {
            font-weight: bold;
        }
        
        /* Print Styles */
        @media print {
            body {
                margin: 0.5in;
                font-size: 10pt;
            }
            
            .kop-surat-container {
                page-break-inside: avoid;
            }
            
            .tanda-tangan-container {
                page-break-inside: avoid;
            }
        }
        
        /* Clear floats */
        .clear {
            clear: both;
        }
    </style>
</head>
<body>

    <div class="kop-surat-container">
        <div class="logo-section">
            <img src="<?= base_url('assets/img/Lambang_Kabupaten_Badung.png') ?>" alt="Logo Kabupaten Badung" class="logo">
        </div>
        <div class="kop-surat-text">
            <h1>Pemerintah Kabupaten Badung</h1>
            <h2>Kecamatan Kuta Selatan</h2>
            <h2>Kelurahan Jimbaran</h2>
            <p>Sekretariat: Perumahan Puri Gading, Jl. Solo<br>
               Telp: 081936065872 | Kode Pos: 80361</p>
        </div>
    </div>

    <div class="judul-surat"> <?= htmlspecialchars($surat['NamaKeperluan']) ?></div>
    <div class="nomor-surat">Nomor: <?= htmlspecialchars($surat['NomorSurat']) ?></div>

    <div class="isi-surat">
        <p class="pembuka">Yang bertanda tangan di bawah ini, Kepala Lingkungan <?= htmlspecialchars($nama_kepala_lingkungan) ?>, Kelurahan Jimbaran, Kecamatan Kuta Selatan, Kabupaten Badung, dengan ini menerangkan dengan sebenarnya bahwa:</p>

        <div class="data-pendatang">
            <table>
                <tr>
                    <td class="label">Nama Lengkap</td>
                    <td class="separator">:</td>
                    <td class="data nama"><?= htmlspecialchars($surat['NamaLengkap']) ?></td>
                </tr>
                <tr>
                    <td class="label">NIK</td>
                    <td class="separator">:</td>
                    <td class="data"><?= htmlspecialchars($surat['NIK']) ?></td>
                </tr>
                <tr>
                    <td class="label">Tempat, Tanggal Lahir</td>
                    <td class="separator">:</td>
                    <td class="data"><?= htmlspecialchars($surat['TempatLahir'] . ', ' . format_tanggal_indonesia($surat['TanggalLahir'])) ?></td>
                </tr>
                <tr>
                    <td class="label">Jenis Kelamin</td>
                    <td class="separator">:</td>
                    <td class="data"><?= htmlspecialchars($surat['JenisKelamin']) ?></td>
                </tr>
                <tr>
                    <td class="label">Alamat Asal</td>
                    <td class="separator">:</td>
                    <td class="data"><?= htmlspecialchars($surat['Alamat']) ?></td>
                </tr>
                <tr>
                    <td class="label">Alamat Tujuan</td>
                    <td class="separator">:</td>
                    <td class="data"><?= htmlspecialchars($surat['TempatTujuan']) ?></td>
                </tr>
            </table>
        </div>

        <p class="pembuka">Bahwa nama tersebut di atas adalah benar warga pendatang yang berdomisili di wilayah kami. Surat pengantar ini dibuat sebagai kelengkapan persyaratan administrasi untuk keperluan: <span class="keperluan"><?= htmlspecialchars($surat['NamaKeperluan']) ?></span>.</p>
        
        <p class="penutup">Demikian surat pengantar ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya. Atas perhatian dan kerjasamanya, kami ucapkan terima kasih.</p>
    </div>

    <div class="blok-tanda-tangan">
        <p>Jimbaran, <?= format_tanggal_indonesia($surat['TanggalPengajuan']) ?></p>
        <p>Kepala Lingkungan,</p>
        
        <div class="area-qr-dan-tanda-tangan">
            <img src="<?= base_url('assets/img/qrcode.png') ?>" alt="QR Code Verifikasi">
        </div>
        
        <div class="nama-pejabat">
            (<?= strtoupper(htmlspecialchars($nama_kepala_lingkungan)) ?>)
        </div>
    </div>

    <div class="clear"></div>

</body>
</html>