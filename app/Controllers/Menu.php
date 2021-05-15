<?php

namespace App\Controllers;

use App\Models\Barang_Model;
use App\Models\Pengumuman_Model;
use App\Models\userModel;
use App\Models\Komplain_Model;
use App\Models\ArsipKomp_Model;
use App\Models\Log_Model;
use Dompdf\Dompdf;

class Menu extends BaseController
{
	/**
	 * Instance of the main Request object.
	 *
	 * @var HTTP\IncomingRequest
	 */
	protected $request; // Menghilangkan alert getPost()
	protected $session;
	protected $userModel;
	protected $barangModel;
	protected $newsModel;
	protected $komplainModel;
	protected $arsipKompModel;
	protected $Log_Model;

	public function __construct()
	{
		$this->session = \Config\Services::session();
		$this->session->start();

		$this->userModel = new userModel();
		$this->barangModel = new Barang_Model();
		$this->newsModel = new Pengumuman_Model();
		$this->komplainModel = new Komplain_Model();
		$this->arsipKompModel = new ArsipKomp_Model();
		$this->LogModel = new Log_Model();
	}

	//========================= Dashboard Index()=====================

	public function Index()
	{
		if (session('uid') != null) {
			return redirect()->to('/dashboard');
		} else {
			return redirect()->to('/login');
		}
	}

	//========================= Logout ==============================
	public function logout()
	{
		session()->destroy();
		return redirect()->to('/login');
	}

	public function kelolaBarang()
	{
		if (session('uid') != null) {
			$data = [
				"title" => "Kelola Barang | INVENBAR",
				"CurrentMenu" => "kelolabarang",
				"info" => $this->newsModel->showTask(),
				"item" => $this->barangModel->getItems(),
				"log_item" => $this->LogModel->ReadLogItem(),
				'user' => $this->userModel->getUserId(session('uid'))
			];
			return view('global/kelolabarang', $data);
		} else {
			return redirect()->to('/login');
		}
	}

	public function Add_item()
	{
		if (session('uid') != null) {
			$data = array(
				'nama_item' => str_replace("'", "", htmlspecialchars($this->request->getPost('nama_item'), ENT_QUOTES)),
				'stok' => str_replace("'", "", htmlspecialchars($this->request->getPost('stok'), ENT_QUOTES)),
				'jenis' => str_replace("'", "", htmlspecialchars($this->request->getPost('jenis'), ENT_QUOTES)),
				'penyimpanan' => str_replace("'", "", htmlspecialchars($this->request->getPost('penyimpanan'), ENT_QUOTES))
			);
			$this->barangModel->addItem($data);
			return redirect()->to('kelolabarang');
		} else {
			return redirect()->to('/login');
		}
	}

	public function Edit_item()
	{
		if (session('uid') != null) {
			$id = $this->request->getPost('id_item');
			$data = array(
				'nama_item' => str_replace("'", "", htmlspecialchars($this->request->getPost('nama_item'), ENT_QUOTES)),
				'stok' => str_replace("'", "", htmlspecialchars($this->request->getPost('stok'), ENT_QUOTES)),
				'jenis' => str_replace("'", "", htmlspecialchars($this->request->getPost('jenis'), ENT_QUOTES)),
				'penyimpanan' => str_replace("'", "", htmlspecialchars($this->request->getPost('penyimpanan'), ENT_QUOTES))
			);
			$this->barangModel->updateItem($data, $id);
			return redirect()->to('kelolabarang');
		} else {
			return redirect()->to('/login');
		}
	}

	public function Delete_item()
	{
		if (session('uid') != null) {
			$id = $this->request->getPost('id_item');
			$this->barangModel->deleteItem($id);
			return redirect()->to('kelolabarang');
		} else {
			return redirect()->to('/login');
		}
	}

	public function Income_item() // perlu fitur tahapan acc
	{
		if (session('uid') != null) {
			// $id = $this->request->getPost('id_item');
			$data = array(
				'nama_pekerja' => str_replace("'", "", htmlspecialchars(session('nama'), ENT_QUOTES)),
				'tgl' => str_replace("'", "", htmlspecialchars($this->request->getPost('tgl'), ENT_QUOTES)),
				'nama_barang' => str_replace("'", "", htmlspecialchars($this->request->getPost('nama_barang'), ENT_QUOTES)),
				'request' => str_replace("'", "", htmlspecialchars('Masuk', ENT_QUOTES)),
				'status' => str_replace("'", "", htmlspecialchars('Pending', ENT_QUOTES)),
				'ubah_stok' => str_replace("'", "", htmlspecialchars($this->request->getPost('jumlah_in'), ENT_QUOTES)),
				'ket' => str_replace("'", "", htmlspecialchars($this->request->getPost('ket'), ENT_QUOTES))
			);
			$this->LogModel->Add_Log_Item($data);
			return redirect()->to('kelolabarang');
		} else {
			return redirect()->to('/login');
		}
	}

	public function Outcome_item() // perlu fitur tahapan acc
	{
		if (session('uid') != null) {
			// $id = $this->request->getPost('id_item');
			$data = array(
				'nama_pekerja' => str_replace("'", "", htmlspecialchars(session('nama'), ENT_QUOTES)),
				'tgl' => str_replace("'", "", htmlspecialchars($this->request->getPost('tgl'), ENT_QUOTES)),
				'nama_barang' => str_replace("'", "", htmlspecialchars($this->request->getPost('nama_barang'), ENT_QUOTES)),
				'request' => str_replace("'", "", htmlspecialchars('Keluar', ENT_QUOTES)),
				'status' => str_replace("'", "", htmlspecialchars('Pending', ENT_QUOTES)),
				'ubah_stok' => str_replace("'", "", htmlspecialchars($this->request->getPost('jumlah_out'), ENT_QUOTES)),
				'ket' => str_replace("'", "", htmlspecialchars($this->request->getPost('ket'), ENT_QUOTES))
			);
			$this->LogModel->Add_Log_Item($data);
			return redirect()->to('kelolabarang');
		} else {
			return redirect()->to('/login');
		}
	}
	// =============================== Pengumuman User ======================

	public function Pengumuman()
	{
		if (session('uid') != null) {
			$data = [
				"title" => "Pengumuman | INVENBAR",
				"CurrentMenu" => "pengumuman",
				"info" => $this->newsModel->showTask(),
				'user' => $this->userModel->getUserId(session('uid'))
			];
			return view('global/user_pengumuman.php', $data);
		} else {
			return redirect()->to('/login');
		}
	}

	// ============================= Profile Akun ================================
	public function Profakun($email)
	{
		if (session('uid') != null) {
			$data = [
				"title" => "My Profile | INVENBAR",
				"CurrentMenu" => "profakun",
				'user' => $this->userModel->getUser($email),
				"info" => $this->newsModel->showTask()
			];
			return view('global/myprofile', $data);
		} else {
			return redirect()->to('/login');
		}
	}

	// =================================== Profile Edit ====================================

	public function Profedit($uid)
	{
		if (session('uid') != null) {
			$data = [
				"title" => "Edit Profile | INVENBAR",
				"CurrentMenu" => "profedit",
				"info" => $this->newsModel->showTask(),
				'validation' => \Config\Services::Validation(),
				'user' => $this->userModel->getUserId($uid)
			];
			return view('global/editprofile', $data);
		} else {
			return redirect()->to('/login');
		}
	}

	public function profUpdate($uid)
	{
		// dd($this->request->getFile('foto')->getName());
		$dataUser = $this->userModel->getUserId($uid);

		// jika seleksi inputan email
		if ($dataUser['email'] == $this->request->getVar('email')) {
			// jika inputan email tidak diubah
			$rule_email = 'required|valid_email';
		} else {
			// jika inputan email diubah
			$rule_email = 'required|valid_email|is_unique[user.email]';
		}
		$rule_pw1 = 'min_length[0]';

		if ($this->request->getVar('password1') != null) {
			$rule_pw1 = 'min_length[8]';
		}

		// jika valdiasi tidakk lolos maka redirect ke halaman edit
		if (!$this->validate([
			'nama' => [
				'rules' => 'required',
				'errors' => ['required' => '{field} harus diisi.']
			],
			'email' => [
				'rules' => $rule_email,
				'errors' => [
					'required' => '{field} harus diisi.',
					'valid_email' => '{field} tidak valid.',
					'is_unique' => 'Email sudah terdaftar.'
				]
			],
			'password1' => [
				'rules' => $rule_pw1,
				'errors' => ['min_length' => 'Password minimal 8 karakter.']
			],
			'password2' => [
				'rules' => 'matches[password1]',
				'errors' => ['matches' => 'Konfirmasi password tidak valid.']
			],
			'password' => [
				'rules' => 'required',
				'errors' => ['required' => '{field} harus diisi.']
			],
			'foto' => [
				'rules' => 'max_size[foto,5120]|is_image[foto]|max_dims[foto],3500,3500]|mime_in[foto,image/jpg,image/jpeg,image/png]',
				'errors' => [
					'max_size' => 'Ukuran gambar {field} maksimal 5MB',
					'is_image' => '{field} harus merupakan gambar.',
					'max_dims' => 'Dimensi File tidak boleh melebihi 3500 x 3500 !',
					'mime_in' => '{field} harus berekstensi .jpg, .jpeg, atau .png'
				]
			]
		])) {
			return redirect()->to('/Menu/profedit/' . $uid)->withInput();
		}

		// mengambil inputan foto/gambar
		$fileFoto = $this->request->getFile('foto');

		// cek gambar lama
		if ($fileFoto->getError() == 4) {
			// jika tidak diubah maka pakai foto lama
			$namaFoto = $this->request->getVar('fotoLama');
		} else {
			// generate nama random
			$namaFoto = $fileFoto->getRandomName();
			// upload gambar
			$fileFoto->move('img/user', $namaFoto);

			// jika nama foto lama bukan default.jpg
			if ($fileFoto->getName() != "default.jpg") {
				//hapus gambar lama
				unlink('img/user/' . $this->request->getVar('fotoLama'));
			}
		}

		$inputPassword = $this->request->getVar('password');
		//cek password
		if (password_verify($inputPassword, $dataUser['password'])) {

			// cek jika ada inputan password baru
			if ($this->request->getvar('password1') != null) {
				$password = $this->request->getvar('password1');
			} else {
				$password = $inputPassword;
			}

			// tampung masukan
			$inputNama = str_replace("'", "", htmlspecialchars($this->request->getVar('nama'), ENT_QUOTES));
			$inputEmail = str_replace("'", "", htmlspecialchars($this->request->getVar('email'), ENT_QUOTES));
			$inputPW = password_hash($password, PASSWORD_DEFAULT);

			$this->userModel->update($uid, [
				'nama' => $inputNama,
				'email' => $inputEmail,
				'password' => $inputPW,
				'role' => str_replace("'", "", htmlspecialchars($dataUser['role'], ENT_QUOTES)),
				'picture' => $namaFoto
			]);

			session()->setFlashdata('pesan', 'Data berhasil diubah');
			$this->session->set('nama', $inputNama); // ganti session nama
			$this->session->set('email', $inputEmail); // ganti session email
			$this->session->set('password', $inputPW); // ganti session password
			$this->session->set('picture', $namaFoto); // ganti session gambar

			return redirect()->to('/menu/profakun/' . $dataUser['email']);
		} else {
			session()->setFlashdata('pesanPassword', 'Password Salah. Harap masukkan password valid untuk mengubah data.');
			return redirect()->to('/menu/profedit/' . $uid)->withInput();
		}
	}


	// ==================================== Export & Print Data =========================================
	public function excelbarang()
	{
		if (session('uid') != null) {
			$data = [
				"title" => "Excel BARANG | INVENBAR",
				"item" => $this->barangModel->getItems()
			];

			return view('global/exxlsBarang', $data);
		} else {
			return redirect()->to('/login');
		}
	}

	public function docbarang()
	{
		if (session('uid') != null) {
			$data = [
				"title" => "DOC BARANG | INVENBAR",
				"item" => $this->barangModel->getItems()
			];

			return view('global/exdocBarang', $data);
		} else {
			return redirect()->to('/login');
		}
	}

	public function pdfbarang()
	{
		if (session('uid') != null) {
			$data = [
				"title" => "PDF BARANG | INVENBAR",
				"item" => $this->barangModel->getItems()
			];

			$html = view('global/expdfBarang', $data);

			$dompdf = new Dompdf();
			$dompdf->loadHtml($html);
			$dompdf->setPaper('A4', 'potrait');
			$dompdf->render();
			$dompdf->stream('Tabel-Barang-Gudang-2021.pdf');
		} else {
			return redirect()->to('/login');
		}
	}

	public function excelspesifikasi()
	{
		if (session('uid') != null) {
			$data = [
				"title" => "EXCEL SPESIFIKASI | INVENBAR"
			];

			return view('global/exxlsSpesifikasi', $data);
		} else {
			return redirect()->to('/login');
		}
	}

	public function docspesifikasi()
	{
		if (session('uid') != null) {
			$data = [
				"title" => "DOC SPESIFIKASI | INVENBAR"
			];

			return view('global/exdocSpesifikasi', $data);
		} else {
			return redirect()->to('/login');
		}
	}

	public function pdfspesifikasi()
	{
		if (session('uid') != null) {
			$data = [
				"title" => "PDF SPESIFIKASI | INVENBAR"
			];

			$html = view('global/expdfSpesifikasi', $data);

			$dompdf = new Dompdf();
			$dompdf->loadHtml($html);
			$dompdf->setPaper('A4', 'potrait');
			$dompdf->render();
			$dompdf->stream('Tabel-Spesifikasi-Barang-2021.pdf');
		} else {
			return redirect()->to('/login');
		}
	}

	public function pdfchart1()
	{
		if (session('uid') != null) {
			$data = [
				"title" => "PDF CHART-1 | INVENBAR",
				"class" => $this->barangModel->invenclass(),
				"sc1" => $this->barangModel->stockclass1(),
				"sc2" => $this->barangModel->stockclass2(),
				"sc3" => $this->barangModel->stockclass3(),
				"sc4" => $this->barangModel->stockclass4(),
				"sc5" => $this->barangModel->stockclass5(),
				"sc6" => $this->barangModel->stockclass6(),
				"sc7" => $this->barangModel->stockclass7()
			];

			return view('global/printChart1', $data);
		} else {
			return redirect()->to('/login');
		}
	}

	public function pdfchart2()
	{
		if (session('uid') != null) {
			$data = [
				"title" => "PDF CHART-2 | INVENBAR",
				"class" => $this->barangModel->invenclass(),
				"sc1" => $this->barangModel->stockclass1(),
				"sc2" => $this->barangModel->stockclass2(),
				"sc3" => $this->barangModel->stockclass3(),
				"sc4" => $this->barangModel->stockclass4(),
				"sc5" => $this->barangModel->stockclass5(),
				"sc6" => $this->barangModel->stockclass6(),
				"sc7" => $this->barangModel->stockclass7()
			];

			return view('global/printChart2', $data);
		} else {
			return redirect()->to('/login');
		}
	}

	public function pdfchart3()
	{
		if (session('uid') != null) {
			$data = [
				"title" => "PDF CHART-3 | INVENBAR",
				"category" => $this->barangModel->jenis(),
				"sj1" => $this->barangModel->stockjenis1(),
				"sj2" => $this->barangModel->stockjenis2(),
				"sj3" => $this->barangModel->stockjenis3(),
				"sj4" => $this->barangModel->stockjenis4(),
				"sj5" => $this->barangModel->stockjenis5()
			];

			return view('global/printChart3', $data);
		} else {
			return redirect()->to('/login');
		}
	}

	public function pdfchart4()
	{
		if (session('uid') != null) {
			$data = [
				"title" => "PDF CHART-4 | INVENBAR",
				"category" => $this->barangModel->jenis(),
				"sj1" => $this->barangModel->stockjenis1(),
				"sj2" => $this->barangModel->stockjenis2(),
				"sj3" => $this->barangModel->stockjenis3(),
				"sj4" => $this->barangModel->stockjenis4(),
				"sj5" => $this->barangModel->stockjenis5()
			];

			return view('global/printChart4', $data);
		} else {
			return redirect()->to('/login');
		}
	}

	public function pdfprintBarang()
	{
		if (session('uid') != null) {
			$data = [
				"title" => "PDF BARANG | INVENBAR",
				"item" => $this->barangModel->getItems()
			];

			return view('global/printBarang', $data);
		} else {
			return redirect()->to('/login');
		}
	}

	public function pdfprintSpesifikasi()
	{
		if (session('uid') != null) {
			$data = [
				"title" => "PDF SPESIFIKASI | INVENBAR"
			];

			return view('global/printSpesifikasi', $data);
		} else {
			return redirect()->to('/login');
		}
	}

	// ==================================== Highlights =========================================

	public function absensiUser()
	{
		if (session('uid') != null) {
			$data = [
				"title" => "Absensi Pekerja | INVENBAR",
				"CurrentMenu" => "absensi",
				"info" => $this->newsModel->showTask(),
				'user' => $this->userModel->getUserId(session('uid'))
			];
			return view('global/absensi', $data);
		} else {
			return redirect()->to('/login');
		}
	}

	public function LaporanBulanan()
	{
		if (session('uid') != null) {
			$data = [
				"title" => "Cetak Laporan | INVENBAR",
				"CurrentMenu" => "laporanBulanan",
				"info" => $this->newsModel->showTask(),
				'user' => $this->userModel->getUserId(session('uid'))
			];
			return view('global/laporanBulanan', $data);
		} else {
			return redirect()->to('/login');
		}
	}

	public function Dashboard()
	{
		if (session('uid') != null) {
			$data = [
				"title" => "Dashboard | INVENBAR",
				"CurrentMenu" => "dashboard",
				"info" => $this->newsModel->showTask(),
				'user' => $this->userModel->getUserId(session('uid')),
				"class" => $this->barangModel->invenclass(),
				"category" => $this->barangModel->jenis(),
				"sc1" => $this->barangModel->stockclass1(),
				"sc2" => $this->barangModel->stockclass2(),
				"sc3" => $this->barangModel->stockclass3(),
				"sc4" => $this->barangModel->stockclass4(),
				"sc5" => $this->barangModel->stockclass5(),
				"sc6" => $this->barangModel->stockclass6(),
				"sc7" => $this->barangModel->stockclass7(),
				"sj1" => $this->barangModel->stockjenis1(),
				"sj2" => $this->barangModel->stockjenis2(),
				"sj3" => $this->barangModel->stockjenis3(),
				"sj4" => $this->barangModel->stockjenis4(),
				"sj5" => $this->barangModel->stockjenis5()
			];

			$nama = session('nama');
			session()->setFlashdata('msg', '<div class="alert alert-success alert-dismissible fade show success-login" role="alert">
				Hai <strong>' . $nama . '</strong>, Selamat datang di website <strong>INVENBAR</strong>...
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>');

			return view('global/dashboard', $data);
		} else {
			return redirect()->to('/login');
		}
	}

	public function Pengaduan()
	{
		if (session('uid') != null) {
			$data = [
				"title" => "Pengaduan | INVENBAR",
				"CurrentMenu" => "pengaduan",
				"info" => $this->newsModel->showTask(),
				'validation' => \Config\Services::Validation(),
				'user' => $this->userModel->getUserId(session('uid'))
			];
			return view('global/pengaduan', $data);
		} else {
			return redirect()->to('/login');
		}
	}

	public function adukan()
	{
		if (session('uid') != null) {

			// jika valdiasi tidakk lolos maka redirect ke halaman edit
			if (!$this->validate([
				'judul' => [
					'rules' => 'required',
					'errors' => ['required' => 'Judul harus diisi.']
				],
				'isi' => [
					'rules' => 'required|min_length[1]|max_length[256]',
					'errors' => [
						'required' => 'Isi pengaduan harus diisi.',
						'min_length' => 'Isi pengaduan minimal terdapat satu huruf.',
						'max_length' => 'Isi pengaduan tidak boleh lebih dari 255 huruf dan karakter.'
					]
				],
				'foto' => [
					'rules' => 'max_size[foto,10240]|is_image[foto]|mime_in[foto,image/jpg,image/jpeg,image/png]',
					'errors' => [
						'max_size' => 'Ukuran bukti screenshot maksimal 10MB',
						'is_image' => 'Bukti screenshot harus merupakan gambar.',
						'mime_in' => 'Bukti screenshot harus berekstensi .jpg, .jpeg, atau .png'
					]
				]
			])) {
				return redirect()->to('/Menu/pengaduan')->withInput();
			}

			// mengambil uid pengadu
			$uid = $this->request->getVar('uid');
			// random number
			$randomNumb = rand(100, 999);

			// make uid for no_komplain
			if ($uid < 10) {
				$noKomp = "00" . $uid;
			} elseif ($uid < 100) {
				$noKomp = "0" . $uid;
			} else {
				$noKomp = $uid;
			}

			// generate no_komplain
			$no_komp = "K-" . date("dmy") . "-" . $noKomp . "-" . $randomNumb;

			// mengambil input gambar
			$fileFoto = $this->request->getFile('foto');

			// cek inputan gambar, jika gambar tidak kosong
			if ($fileFoto->getError() != 4) {
				// generate nama random
				$namaFoto = $fileFoto->getRandomName();
				// upload gambar
				$fileFoto->move('img/komplain', $namaFoto);
			} else {
				$namaFoto = "-";
			}

			// tampung masukan
			$judul = str_replace("'", "", htmlspecialchars($this->request->getVar('judul'), ENT_QUOTES));
			$isi = str_replace("'", "", htmlspecialchars($this->request->getVar('isi'), ENT_QUOTES));
			$inputEmail = str_replace("'", "", htmlspecialchars($this->request->getVar('email'), ENT_QUOTES));

			// upload tabel 'komplain'
			$this->komplainModel->insert([
				'no_komplain' => $no_komp,
				'uid_komplain' => $uid,
				'email_komplain' => $inputEmail,
				'judul_komplain' => $judul,
				'isi_komplain' => $isi,
				'foto_komplain' => $namaFoto,
				'waktu_komplain' => date("Y-m-d h:i:sa")
			]);

			session()->setFlashdata('pengaduanSukses', 'Pengaduan telah diterima, masalah sedang diselidiki.');
			return redirect()->to('/Menu/pengaduan');
		} else {
			return redirect()->to('/login');
		}
	}
}
