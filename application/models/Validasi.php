<?php
class  Validasi extends CI_Model
{
    function validasiakun()
		{
			$JenisAkun=$this->session->userdata('JenisAkun');
			if($JenisAkun=="")
			{
				echo "<script>alert('Maaf anda tidak dapat akses halaman ini')</script>";
				redirect('awalPage','refresh');	
			}	
		}
}
