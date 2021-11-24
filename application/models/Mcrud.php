<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mcrud extends CI_Model {

 public static function tgl_id($date, $bln='')
 {
	 date_default_timezone_set('Asia/Jakarta');
		 $str = explode('-', $date);
		 $bulan = array(
			 '01' => 'Januari',
			 '02' => 'Februari',
			 '03' => 'Maret',
			 '04' => 'April',
			 '05' => 'Mei',
			 '06' => 'Juni',
			 '07' => 'Juli',
			 '08' => 'Agustus',
			 '09' => 'September',
			 '10' => 'Oktober',
			 '11' => 'November',
			 '12' => 'Desember',
		 );
		 if ($bln == '') {
			 $hasil = $str['0'] . "-" . substr($bulan[$str[1]],0,3) . "-" .$str[2];
		 }elseif ($bln == 'full') {
			 $hasil = $str['0'] . " " . $bulan[$str[1]] . " " .$str[2];
		 }else {
			 $hasil = $bulan[$str[1]];
		 }
		 return $hasil;
 }

	public function hari_id($tanggal)
	{
		$day = date('D', strtotime($tanggal));
		$dayList = array(
			'Sun' => 'Minggu',
			'Mon' => 'Senin',
			'Tue' => 'Selasa',
			'Wed' => 'Rabu',
			'Thu' => 'Kamis',
			'Fri' => "Jum'at",
			'Sat' => 'Sabtu'
		);
		return $dayList[$day];
	}

	public function get_user_name_by_id($id)
	{
		$user = $this->Guzzle_model->getUserById($id);
		return $user['nama'];
	}

	public function waktu($data, $aksi='')
	{
		if ($aksi=='full') {
			$tgl_n = date('d-m-Y H:i:s',strtotime($data));
		}else {
			$tgl_n = date('d-m-Y',strtotime($data));
		}
		$hari = $this->Mcrud->hari_id($tgl_n);
		$tgl  = $this->Mcrud->tgl_id($tgl_n,$aksi);
		return $hari.", ".$tgl;
	}

	function judul_web($id='')
	{
		// $nama_web = $this->db->get_where('tbl_web',"id_web='1'")->row()->nama_web;
		// $ket_web  = $this->db->get_where('tbl_web',"id_web='1'")->row()->ket_web;
		// if ($id==1) {
		// 	$data = "$nama_web";
		// }elseif ($id==2) {
		// 	$data = "$ket_web";
		// }else {
		// 	$data = "$nama_web $ket_web";
		// }
		$data = '';
		return $data;
	}

	// function footer()
	// {
	// 		return "Copyright &copy; 2019 | Developer by <a href='#' target='_blank'>CV. LINK NET</a>";
	// }

	public function cek_filename($file='')
	{
		$data = "assets/favicon.png";
		if ($file != '') {
			if(file_exists("$file")){
				$data = $file;
			}
		}
		return $data;
	}

	function kirim_notif($pengirim,$penerima,$id_for_link,$notif_type,$pesan,$nama_client)
	{
		date_default_timezone_set('Asia/Jakarta');
		$tgl = date('Y-m-d H:i:s');
		if ($pengirim=='superadmin') { $pengirim = '1'; }
		if ($penerima=='superadmin') { $penerima = '1'; }

		if ($notif_type == 'laporan') {
			if ($pesan=='notaris_kirim_laporan') {
				$pesan = "Mengirim Laporan baru";
				// <--- >//	
			}elseif ($pesan=='superadmin_konfirmasi_laporan') {
				$pesan = "Laporan perlu perbaikan";
			}elseif ($pesan=='superadmin_selesai_laporan') {
				$pesan = "Laporan telah selesai diverifikasi";	
			}
			// id laporan notaris
			if ($id_for_link=='' OR $id_for_link==0) {
				$link = '';
			}else{
				$link = "laporan/v/d/".hashids_encrypt($id_for_link);
			}
			//sampai sini
		}
		elseif ($notif_type == 'laporan_semester') {
			if ($pesan=='notaris_kirim_laporan') {
				$pesan = "Mengirim Laporan Semester baru";
				// <--- >//	
			}elseif ($pesan=='superadmin_konfirmasi_laporan') {
				$pesan = "Laporan perlu perbaikan";
			}elseif ($pesan=='superadmin_selesai_laporan') {
				$pesan = "Laporan telah selesai diverifikasi";	
			}
			// id laporan notaris
			if ($id_for_link=='' OR $id_for_link==0) {
				$link = '';
			}else{
				$link = "laporan_semester/v/d/".hashids_encrypt($id_for_link);
			}
			//sampai sini
		}
		elseif ($notif_type == 'pengaduan') {
			if ($pesan=='user_kirim_pengaduan') {
				$pesan = "Pengaduan baru dari masyarakat";
			}
			if ($id_for_link=='' OR $id_for_link==0) {
				$link = '';
			}else{
				$link = "pengaduan/v/d/".hashids_encrypt($id_for_link);
			}
		}
		elseif ($notif_type == 'permohonan') {
			if ($pesan=='user_kirim_permohonan') {
				$pesan = "Permohonan Bantuan Hukum baru dari masyarakat";
			}
			if ($id_for_link=='' OR $id_for_link==0) {
				$link = '';
			}else{
				$link = "permohonan_bankum/v/d/".hashids_encrypt($id_for_link);
			}
		}



		$data2 = array(
			'pengirim'  => $pengirim,
			'penerima'  => $penerima,
			'pesan'  		=> $pesan,
			'link'			=> $link,
			'id_for_link' => $id_for_link,
			'tgl_notif' => $tgl,
			'nama_client' => $nama_client
		);
		$this->db->insert('tbl_notif',$data2);
		
		
	}

	public function rupiah($angka) {
		$hasil_rupiah = "Rp " . number_format($angka,0,"",".");
		return $hasil_rupiah;
	}

	public function persen($realisasi, $total) {
		 $persen = ($realisasi / $total) * 100;
		 return $persen;
	 }
}
