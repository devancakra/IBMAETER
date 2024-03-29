<?php

namespace App\Controllers;

use App\Models\Barang_Model;
use App\Models\Pengumuman_Model;
use App\Models\userModel;
use App\Models\Komplain_Model;
use App\Models\ArsipKomp_Model;
use App\Models\Log_Model;
use App\Models\Absensi_Model;
use App\Models\userActivity_Model;
use App\Models\userDivisi_Model;

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
	protected $absensiModel;
	protected $userActivityModel;
	protected $userDivisiModel;

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
		$this->absensiModel = new Absensi_Model();
		$this->userActivityModel = new userActivity_Model();
		$this->userDivisiModel = new userDivisi_Model();
	}

	//================================================== Dashboard Index() ==============================================

	public function Index()
	{
		if (session('uid') != null) {
			return redirect()->to('/dashboard');
		} else {
			return redirect()->to('/login');
		}
	}

	//================================================== Logout =======================================================
	public function logout()
	{
		$aktivitas = session('nama') . " melakukan Logout.";
		// insert user aktivity saat melakukan logout
		$this->userActivityModel->insert([
			'uid_aktivitas' => session('uid'),
			'aktivitas' => $aktivitas,
			'waktu_aktivitas' => date("Y-m-d H:i:s")
		]);
		session()->destroy();
		return redirect()->to('/login');
	}


	//================================================== Kelola Barang ==================================================
	public function kelolaBarang()
	{
		if (session('uid') != null) {
			$data = [
				"title" => "Kelola Barang | IBMAETER",
				"CurrentMenu" => "kelolabarang",
				"info" => $this->newsModel->showTask(),
				"infoV" => $this->newsModel->showExpVisibility(), // isi pengumuman dropdown
				"infoCV" => $this->newsModel->CountExpVisibility(array('uid' => session('uid'))), // counter pengumuman
				// "item" => $this->barangModel->getItems(), GANTI AJAX
				// "supplier" => $this->barangModel->viewSuppliers(),
				// "spec" => $this->barangModel->joinSupplier(),
				'user' => $this->userModel->getUserId(session('uid')),
				"log_notifs" => $this->LogModel->notifsLog(),
				"komplain_notifs" => $this->komplainModel->notifsKomplain(),
				"PengaduanCounter" => $this->arsipKompModel->CountPengaduanExpVisibility(),
				'absensi_notif' => $this->absensiModel->getPending()
			];
			return view('global/menu/kelolabarang', $data);
		} else {
			return redirect()->to('/login');
		}
	}

	public function ShowItem() // Show Master Data Item
	{
		// seleksi no login
		if (session('uid') != null) {
			// AJAX
			$data["item"] = $this->barangModel->getItems();
			// echo json_encode($data);
			return view('global/barang_part/list_item', $data);
		} else {
			return redirect()->to('/dashboard');
		}
	}

	// *** ADMIN CAN ACCESSS
	public function ShowPerizinan() // Show Master Data Perizinan
	{
		// seleksi no login
		if (session('uid') != null) {
			// AJAX
			$data["log_item"] = $this->LogModel->ReadLogItem();
			// echo json_encode($data);
			return view('admin/perizinan_part/list_perizinan', $data);
		} else {
			return redirect()->to('/dashboard');
		}
	}

	public function ShowSpesifikasi() // Show Master Data Spesifikasi
	{
		// seleksi no login
		if (session('uid') != null) {
			// AJAX
			$data["spec"] = $this->barangModel->joinSupplier();
			// echo json_encode($data);
			return view('global/barang_part/list_spesifikasi', $data);
		} else {
			return redirect()->to('/dashboard');
		}
	}

	// ....................................... Item Form .....................................

	public function TambahItem_Form() // Form Tambah Master Data Item
	{
		// seleksi no login
		if (session('uid') != null) {
			// AJAX
			$data = [
				// "item" => $this->barangModel->getItems(), 
				"supplier" => $this->barangModel->viewSuppliers(),
			];
			return view('global/barang_part/tambah_form', $data);
		} else {
			return redirect()->to('/dashboard');
		}
	}

	public function EditItem_Form() // Form Edit Master Data Item
	{
		// seleksi no login
		if (session('uid') != null) {
			// AJAX
			$data = [
				"item" => $this->barangModel->getItems(),
			];
			return view('global/barang_part/edit_form', $data);
		} else {
			return redirect()->to('/dashboard');
		}
	}

	public function DeleteItem_Form() // Form Delete Master Data Item
	{
		// seleksi no login
		if (session('uid') != null) {
			// AJAX
			$data = [
				"item" => $this->barangModel->getItems(),
			];
			return view('global/barang_part/delete_form', $data);
		} else {
			return redirect()->to('/dashboard');
		}
	}

	// .......................................... InOut Form..........................................
	public function InItem_Form() // Form In Master Data Item
	{
		// seleksi no login
		if (session('uid') != null) {
			// AJAX
			$data = [
				"item" => $this->barangModel->getItems(),
			];
			return view('global/barang_part/in_form', $data);
		} else {
			return redirect()->to('/dashboard');
		}
	}

	public function OutItem_Form() // Form Out Master Data Item
	{
		// seleksi no login
		if (session('uid') != null) {
			// AJAX
			$data = [
				"item" => $this->barangModel->getItems(),
			];
			return view('global/barang_part/out_form', $data);
		} else {
			return redirect()->to('/dashboard');
		}
	}

	//.... Perizinan form are in controler admin

	// .......................................... Spesifikasi Form..........................................
	public function EditSpec_Form() // Edit Form In Master Data Item
	{
		// seleksi no login
		if (session('uid') != null) {
			// AJAX
			$data = [
				"spec" => $this->barangModel->joinSupplier(),
				"supplier" => $this->barangModel->viewSuppliers(),
			];
			return view('global/barang_part/edit_spec_form', $data);
		} else {
			return redirect()->to('/dashboard');
		}
	}

	public function DetailSpec_Form() // Detail Form In Master Data Item
	{
		// seleksi no login
		if (session('uid') != null) {
			// AJAX
			$data = [
				"spec" => $this->barangModel->joinSupplier(),
				"supplier" => $this->barangModel->viewSuppliers(),
			];
			return view('global/barang_part/detail_spec_form', $data);
		} else {
			return redirect()->to('/dashboard');
		}
	}

	// .......................................... Get Data form ..........................................
	public function GetIDItem() // Form Edit Master Data Item
	{
		// seleksi no login
		if (session('uid') != null) {
			// AJAX
			$id = $this->request->getPost('id_item');
			$data = $this->barangModel->getItems($id);
			echo json_encode($data);
		} else {
			return redirect()->to('/dashboard');
		}
	}

	public function GetSupplier() // Form Edit Master Data Item
	{
		// seleksi no login
		if (session('uid') != null) {
			// AJAX
			$id = $this->request->getPost('id_supplier');
			$suppliers = $this->barangModel->viewSuppliers();
			$data = '';
			$data .= '<option value="">(Tidak Ada)</option>';
			foreach ($suppliers as $s) {
				if ($s->id_supplier == $id) {
					$selected = "selected";
				} else {
					$selected = "";
				}
				$data .= "<option class='checksupplier' value='$s->id_supplier' $selected>$s->nama_supplier</option>";
			}
			echo $data;
		} else {
			return redirect()->to('/dashboard');
		}
	}

	// .......................................... Item Action ........................................

	public function Add_item()
	{
		if (session('uid') != null) {
			// cek input kode
			$k1 = $this->request->getPost('kode1');
			if ($k1 == null) {
				$k1 = "XX";
			}
			$k2 = $this->request->getPost('kode2');
			if ($k2 == !null) {
				if ($k2 == "Padat") {
					$k2 = "01";
				} elseif ($k2 == "Cair") {
					$k2 = "02";
				} elseif ($k2 == "Mudah Terbakar") {
					$k2 = "03";
				} elseif ($k2 == "Minyak") {
					$k2 = "04";
				} elseif ($k2 == "Daging") {
					$k2 = "05";
				} else {
					$k2 = "06";
				}
			} else {
				$k2 = "XX";
			}
			$k3 = $this->request->getPost('kode3');
			$kode = $k1 . $k2 . $k3;

			$data = array(
				'nama_item' => str_replace("'", "", htmlspecialchars($this->request->getPost('nama_item'), ENT_QUOTES)),
				'stok' => str_replace("'", "", htmlspecialchars($this->request->getPost('stok'), ENT_QUOTES)),
				'jenis' => str_replace("'", "", htmlspecialchars($this->request->getPost('jenis'), ENT_QUOTES)),
				'penyimpanan' => str_replace("'", "", htmlspecialchars($this->request->getPost('penyimpanan'), ENT_QUOTES)),
				'kode_barang' => str_replace("'", "", htmlspecialchars($kode, ENT_QUOTES)),
				'harga' => str_replace("'", "", htmlspecialchars($this->request->getPost('harga'), ENT_QUOTES)),
				'berat' => str_replace("'", "", htmlspecialchars($this->request->getPost('berat'), ENT_QUOTES)),
				'id_supplier' => str_replace("'", "", htmlspecialchars($this->request->getPost('supplier'), ENT_QUOTES))
			);
			$this->barangModel->addItem($data);

			$aktivitas = session('nama') . " melakukan Penambahan Barang " . $data['nama_item'];
			// insert user aktivity saat melakukan tambah item/barang
			$this->userActivityModel->insert([
				'uid_aktivitas' => session('uid'),
				'aktivitas' => $aktivitas,
				'waktu_aktivitas' => date("Y-m-d H:i:s")
			]);

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

			$aktivitas = session('nama') . " melakukan Perubahan pada Barang " . $data['nama_item'] . ", dengan ID barang : " . $id;
			// insert user aktivity saat melakukan edit item/barang
			$this->userActivityModel->insert([
				'uid_aktivitas' => session('uid'),
				'aktivitas' => $aktivitas,
				'waktu_aktivitas' => date("Y-m-d H:i:s")
			]);

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

			$aktivitas = session('nama') . " menghapus Barang dengan ID barang : " . $id;
			// insert user aktivity saat melakukan edit item/barang
			$this->userActivityModel->insert([
				'uid_aktivitas' => session('uid'),
				'aktivitas' => $aktivitas,
				'waktu_aktivitas' => date("Y-m-d H:i:s")
			]);

			return redirect()->to('kelolabarang');
		} else {
			return redirect()->to('/login');
		}
	}

	// ---------------------- Aksi In/Out ---------------------------

	public function In_item()
	{
		if (session('uid') != null) {
			// $id = $this->request->getPost('id_item');
			$ket = $this->request->getPost('ket');

			// jika komen kosong
			if ($ket == null) {
				$ket = "(Tanpa Keterangan)";
			}

			$data = array(
				'uid' => str_replace("'", "", htmlspecialchars(session('uid'), ENT_QUOTES)),
				'id_item' => str_replace("'", "", htmlspecialchars($this->request->getPost('InOut_Id_Item'), ENT_QUOTES)),
				'tgl' => str_replace("'", "", htmlspecialchars($this->request->getPost('tgl'), ENT_QUOTES)),
				'request' => str_replace("'", "", htmlspecialchars('Masuk', ENT_QUOTES)),
				'status' => str_replace("'", "", htmlspecialchars('Pending', ENT_QUOTES)),
				'ubah_stok' => str_replace("'", "", htmlspecialchars($this->request->getPost('jumlah_in'), ENT_QUOTES)),
				'ket' => str_replace("'", "", htmlspecialchars($ket, ENT_QUOTES))
			);
			$this->LogModel->Add_Log_Item($data);

			$aktivitas = session('nama') . " menambah stok Barang dengan ID : " . $data['id_item'] . ", sejumlah : " . $data['ubah_stok'];
			// insert user aktivity saat melakukan input stok masuk item/barang
			$this->userActivityModel->insert([
				'uid_aktivitas' => session('uid'),
				'aktivitas' => $aktivitas,
				'waktu_aktivitas' => date("Y-m-d H:i:s")
			]);

			return redirect()->to('kelolabarang');
		} else {
			return redirect()->to('/login');
		}
	}

	public function Out_item()
	{
		if (session('uid') != null) {
			// $id = $this->request->getPost('id_item');
			$ket = $this->request->getPost('ket');

			// jika komen kosong
			if ($ket == null) {
				$ket = "(Tanpa Keterangan)";
			}

			$data = array(
				'uid' => str_replace("'", "", htmlspecialchars(session('uid'), ENT_QUOTES)),
				'id_item' => str_replace("'", "", htmlspecialchars($this->request->getPost('InOut_Id_Item'), ENT_QUOTES)),
				'tgl' => str_replace("'", "", htmlspecialchars($this->request->getPost('tgl'), ENT_QUOTES)),
				'request' => str_replace("'", "", htmlspecialchars('Keluar', ENT_QUOTES)),
				'status' => str_replace("'", "", htmlspecialchars('Pending', ENT_QUOTES)),
				'ubah_stok' => str_replace("'", "", htmlspecialchars($this->request->getPost('jumlah_out'), ENT_QUOTES)),
				'ket' => str_replace("'", "", htmlspecialchars($ket, ENT_QUOTES))
			);
			$this->LogModel->Add_Log_Item($data);

			$aktivitas = session('nama') . " melaukan Pengeluaran stok Barang dengan ID : " . $data['id_item'] . ", sejumlah : " . $data['ubah_stok'];
			// insert user aktivity saat melakukan input stok masuk item/barang
			$this->userActivityModel->insert([
				'uid_aktivitas' => session('uid'),
				'aktivitas' => $aktivitas,
				'waktu_aktivitas' => date("Y-m-d H:i:s")
			]);

			return redirect()->to('kelolabarang');
		} else {
			return redirect()->to('/login');
		}
	}

	// --------------------------- Aksi Spesifikasi Item ---------------------------

	public function EditSpecItem()
	{
		if (session('uid') != null) {
			$id = $this->request->getPost('sp_id_item');
			$data = array(
				'kode_barang' => str_replace("'", "", htmlspecialchars($this->request->getPost('sp_kode'), ENT_QUOTES)),
				'harga' => str_replace("'", "", htmlspecialchars($this->request->getPost('sp_harga'), ENT_QUOTES)),
				'berat' => str_replace("'", "", htmlspecialchars($this->request->getPost('sp_berat'), ENT_QUOTES)),
				'id_supplier' => str_replace("'", "", htmlspecialchars($this->request->getPost('sp_supplier'), ENT_QUOTES))
			);
			$this->barangModel->updateItem($data, $id);

			$aktivitas = session('nama') . " mengubah spesifikasi Barang dengan ID : " . $id;
			// insert user aktivity saat melakukan input stok masuk item/barang
			$this->userActivityModel->insert([
				'uid_aktivitas' => session('uid'),
				'aktivitas' => $aktivitas,
				'waktu_aktivitas' => date("Y-m-d H:i:s")
			]);

			return redirect()->to('kelolabarang');
		} else {
			return redirect()->to('/login');
		}
	}
	// ======================================================== Pengumuman User ===============================================

	public function Pengumuman()
	{
		if (session('uid') != null) {
			$curpage = $this->request->getVar('page_pengumuman') ? $this->request->getVar('page_pengumuman') : 1;

			$data = [
				"title" => "Pengumuman | IBMAETER",
				"CurrentMenu" => "pengumuman",
				"info" => $this->newsModel->showTask(),
				"infoJoinV" => $this->newsModel->JoinVisibility(),
				"infoV" => $this->newsModel->showExpVisibility(), // isi pengumuman dropdown
				"infoCV" => $this->newsModel->CountExpVisibility(array('uid' => session('uid'))), // counter pengumuman
				'user' => $this->userModel->getUserId(session('uid')),
				"log_notifs" => $this->LogModel->notifsLog(),
				"komplain_notifs" => $this->komplainModel->notifsKomplain(),
				"PengaduanCounter" => $this->arsipKompModel->CountPengaduanExpVisibility(),
				'absensi_notif' => $this->absensiModel->getPending(),
				"pengumuman" => $this->newsModel->paginate(3, 'pengumuman'),
				"pager" => $this->newsModel->pager,
				"curpage" => $curpage
			];
			return view('global/menu/user_pengumuman.php', $data);
		} else {
			return redirect()->to('/login');
		}
	}

	// Visibilitas Experience Function
	public function PengumumanDilihat()
	{
		if (session('uid') != null) {
			$id_pengumuman = $this->request->getPost('id_pengumuman');
			$data = array(
				'status' => 'Dilihat',
				'waktu' => date("Y-m-d H:i:s"),
				'id_pengumuman' => $id_pengumuman
			);
			// $update_visibility = $this->newsModel->UpdateVisibility($data, $id_visibility);
			if ($this->newsModel->UpdateVisibility($data, $id_pengumuman)) {
				$response = [
					'success' => true,
					'msg' => '<div class="notif-success"><i class="fas fa-fw fa-check-circle text-green mr-2"></i>Pengumuman Dilihat !</div>',
					'data' => $data
				];
			} else {
				$response = [
					'success' => true,
					'msg' => '<div class="notif-failed"><i class="fas fa-fw fa-exclamation-triangle text-danger mr-2"></i>Gagal Menjalankan Aksi !</div>',
					'data' => $data
				];
			}
			return $this->response->setJSON($response);
		} else {
			return redirect()->to('/login');
		}
	}

	public function CountDilihat()
	{
		if (session('uid') != null) {
			$total = $this->newsModel->CountExpVisibility(session('uid'));
			$response = [
				'success' => true,
				'msg' => 'success',
				'totalcount' => $total
			];
			return $this->response->setJSON($response);
		} else {
			return redirect()->to('/login');
		}
	}

	public function UpdateListDilihat()
	{
		if (session('uid') != null) {
			$updateListVisibility = $this->newsModel->UpdateshowExpVisibility();
			$data = '';
			// $data .= '<option value="">(Tidak Ada)</option>';

			foreach ($updateListVisibility as $u) {

				$data .= '
				<a href="' . base_url('Menu/Pengumuman/') . '" class="list-group-item border m-0">
					<div class="row mx-auto align-items-center">
						<div class="col-2">
							<i class="fas fa-fw fa-users"></i>
						</div>
						<div class="col-10">
							<div class="text-dark">
								<span>' . $u['judul'] . '</span>
							</div>
							<div class="text-muted small mt-1 overflowy-notif">
								<span>' . $u['isi'] . '</span>
							</div>
							<div class="text-muted small mt-1">
								<small>' . $u['waktu'] . '</small>
							</div>
						</div>
					</div>
				</a>';
			}
			return $data;
		} else {
			return redirect()->to('/login');
		}
	}



	// ====================================================== Profile Akun =========================================================
	public function Profakun($email)
	{
		if (session('uid') != null) {
			$data = [
				"title" => "My Profile | IBMAETER",
				"CurrentMenu" => "profakun",
				'user' => $this->userModel->getUser($email),
				'divisi' => $this->userDivisiModel->getDivisi(session('divisi_user')),
				"info" => $this->newsModel->showTask(),
				"infoV" => $this->newsModel->showExpVisibility(), // isi pengumuman dropdown
				"infoCV" => $this->newsModel->CountExpVisibility(array('uid' => session('uid'))), // counter pengumuman
				"log_notifs" => $this->LogModel->notifsLog(),
				"komplain_notifs" => $this->komplainModel->notifsKomplain(),
				"PengaduanCounter" => $this->arsipKompModel->CountPengaduanExpVisibility(),
				'absensi_notif' => $this->absensiModel->getPending(),
				'aktivitas' => $this->userActivityModel->getActivity(session('uid'))
			];
			return view('global/menu/myprofile', $data);
		} else {
			return redirect()->to('/login');
		}
	}

	// ============================================================ Profile Edit =============================================================

	public function Profedit($uid)
	{
		if (session('uid') != null) {
			$data = [
				"title" => "Edit Profile | IBMAETER",
				"CurrentMenu" => "profedit",
				"info" => $this->newsModel->showTask(),
				"infoV" => $this->newsModel->showExpVisibility(), // isi pengumuman dropdown
				"infoCV" => $this->newsModel->CountExpVisibility(array('uid' => session('uid'))), // counter pengumuman
				'validation' => \Config\Services::Validation(),
				'user' => $this->userModel->getUserId($uid),
				"log_notifs" => $this->LogModel->notifsLog(),
				"PengaduanCounter" => $this->arsipKompModel->CountPengaduanExpVisibility(),
				"komplain_notifs" => $this->komplainModel->notifsKomplain(),
				"PengaduanCounter" => $this->arsipKompModel->CountPengaduanExpVisibility(),
				'absensi_notif' => $this->absensiModel->getPending()
			];
			return view('global/menu/editprofile', $data);
		} else {
			return redirect()->to('/login');
		}
	}

	public function profUpdate($uid)
	{
		$aktivitas = session('nama') . " melakukan Edit Profil.";
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
				if ($this->request->getVar('fotoLama') !== "default.jpg") {
					unlink('img/user/' . $this->request->getVar('fotoLama'));
				}
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

			// insert user aktivity saat melakukan edit profil
			$this->userActivityModel->insert([
				'uid_aktivitas' => $uid,
				'aktivitas' => $aktivitas,
				'waktu_aktivitas' => date("Y-m-d H:i:s")
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

	// ============================================================= Absensi User ==================================================================

	public function absensiUser()
	{
		if (session('uid') != null) {
			$data = [
				"title" => "Absensi Pekerja | IBMAETER",
				"CurrentMenu" => "absensi",
				"info" => $this->newsModel->showTask(),
				"infoV" => $this->newsModel->showExpVisibility(), // isi pengumuman dropdown
				"infoCV" => $this->newsModel->CountExpVisibility(array('uid' => session('uid'))), // counter pengumuman
				'user' => $this->userModel->getUserId(session('uid')),
				'user_division' => $this->userDivisiModel->getDivisi(session('divisi_user')),
				"log_notifs" => $this->LogModel->notifsLog(),
				"komplain_notifs" => $this->komplainModel->notifsKomplain(),
				"PengaduanCounter" => $this->arsipKompModel->CountPengaduanExpVisibility(),
				'absensi_notif' => $this->absensiModel->getPending(),
				'absensi' => $this->absensiModel->getStatus(session('uid'), date("Y-m-d")),
				'izin' => $this->absensiModel->getStatusIzin(session('uid'), date("Y-m-d")),
				'validation' => \Config\Services::Validation()
			];
			return view('global/menu/absensi', $data);
		} else {
			return redirect()->to('/login');
		}
	}

	public function absen()
	{
		// jika mengajukan izin rules akan berlaku
		if ($this->request->getVar('alasanIzin') != null) {
			$rulesAlasanIzin = "required";
			$rulesBuktiIzin = "required_with[foto,alasanIzin]|max_size[foto,5120]|is_image[foto]|max_dims[foto],3500,3500]|mime_in[foto,image/jpg,image/jpeg,image/png]";
			$aktivitas = session('nama') . " melakukan Pengajuan Izin.";
		} else {
			$rulesAlasanIzin = "min_length[0]";
			$rulesBuktiIzin = "min_length[0]";
			$aktivitas = session('nama') . " melakukan Absensi.";
		}

		// jika valdiasi tidakk lolos maka redirect ke halaman edit
		if (!$this->validate([
			'alasanIzin' => [
				'rules' => $rulesAlasanIzin,
				'errors' => [
					'required' => 'Alasan izin harus diisi.',
					'min_length' => 'Alasan izin harus diisi.'
				]
			],
			'foto' => [
				'rules' => $rulesBuktiIzin,
				'errors' => [
					'required_with' => 'Bukti harus diisi.',
					'max_size' => 'Ukuran gambar maksimal 5MB',
					'is_image' => 'Bukti izin harus berupa gambar.',
					'max_dims' => 'Dimensi File tidak boleh melebihi 3500 x 3500 !',
					'mime_in' => '{field} harus berekstensi .jpg, .jpeg, atau .png',
					'min_length' => 'Bukti harus diisi.'
				]
			]
		])) {
			return redirect()->to('/Menu/absensiUser')->withInput();
		}

		$time = date("H:i:s");
		$uid = session('uid');
		$buktiFoto = $this->request->getFile('foto'); // mengambil file gambar
		$buktiIzin = "-";
		$alasanIzin = "-";
		$respon = "Masuk";
		$tipePesan = "pesan";

		// jika melakukan perizinan
		if ($this->request->getVar('alasanIzin') != null) {
			// mengambil alasan izin
			$alasanIzin = $this->request->getVar('alasanIzin');
			// set status absen menjadi izin
			$status = "Izin";
			// generate nama random
			$buktiIzin = $buktiFoto->getRandomName();
			// upload gambar
			$buktiFoto->move('img/bukti_absen', $buktiIzin);
			// respon jika izin
			$respon = "Pending";
			// pesan FLASH
			$pesanFlash = "Izin telah diajukan.";
		} else {
			// jika waktu lebih dari setengah 8 maka dihitung terlambat
			if ($time >= "07:30:05") {
				// jika melewati jam kerja (4 sore) maka dihitung tidak bekerja
				if ($time >= "16:15:05") {
					$status = "Tidak Bekerja";
					// pesan flash
					$pesanFlash = "Jam kerja telah terlewat, status absensi Anda hari ini adalah <b>Tidak Bekerja</b>.<br>Pengajuan izin tetap bisa dilakukan.";
					$tipePesan = "alert";
				} else {
					$status = "Terlambat";
					// pesan flash
					$pesanFlash = "Jam toleransi keterlambatan telah terlewat, status absensi Anda hari ini adalah <b>Terlambat</b>.<br>Silahkan melakukan pengajuan izin jika terdapat perizinan.";
					$tipePesan = "alert";
				}
			} else {
				// jika tidak terlambat maka akan tercatat 'Hadir'
				$status = "Hadir";
				// pesan flash
				$pesanFlash = "Absensi Berhasil Dilakukan.";
			}
		}

		$this->absensiModel->insert([
			'uid_absen' => $uid,
			'email_absen' => str_replace("'", "", htmlspecialchars($this->request->getVar('email_absen'), ENT_QUOTES)),
			'status_absen' => $status,
			'alasan_izin' => $alasanIzin,
			'bukti_izin' => $buktiIzin,
			'tgl_absen' => date("Y-m-d"),
			'waktu_absen' => $time,
			'respons' => $respon,
			'komen_izin' => "-",
			'waktu_komen' => null
		]);

		// insert user aktivity saat melakukan absensi
		$this->userActivityModel->insert([
			'uid_aktivitas' => $uid,
			'aktivitas' => $aktivitas,
			'waktu_aktivitas' => date("Y-m-d H:i:s")
		]);

		// Pesan FLASH
		session()->setFlashdata($tipePesan, $pesanFlash);
		return redirect()->to('/Menu/absensiUser');
	}

	// ================================================== Laporan Bulanan ==================================================

	public function LaporanBulanan()
	{
		if (session('uid') != null) {
			$data = [
				"title" => "Laporan Bulanan | IBMAETER",
				"CurrentMenu" => "laporanBulanan",
				"info" => $this->newsModel->showTask(),
				"infoV" => $this->newsModel->showExpVisibility(), // isi pengumuman dropdown
				"infoCV" => $this->newsModel->CountExpVisibility(array('uid' => session('uid'))), // counter pengumuman
				'user' => $this->userModel->getUserId(session('uid')),
				"log_notifs" => $this->LogModel->notifsLog(),
				"komplain_notifs" => $this->komplainModel->notifsKomplain(),
				"PengaduanCounter" => $this->arsipKompModel->CountPengaduanExpVisibility(),
				'absensi_notif' => $this->absensiModel->getPending()
			];
			return view('global/menu/laporanBulanan', $data);
		} else {
			return redirect()->to('/login');
		}
	}

	//================================================== Pengaduan ==================================================

	public function Pengaduan()
	{
		if (session('uid') != null) {
			$data = [
				"title" => "Pengaduan | IBMAETER",
				"CurrentMenu" => "pengaduan",
				"info" => $this->newsModel->showTask(),
				"infoV" => $this->newsModel->showExpVisibility(), // isi pengumuman dropdown
				"infoCV" => $this->newsModel->CountExpVisibility(array('uid' => session('uid'))), // counter pengumuman
				'validation' => \Config\Services::Validation(),
				'user' => $this->userModel->getUserId(session('uid')),
				"log_notifs" => $this->LogModel->notifsLog(),
				"komplain_notifs" => $this->komplainModel->notifsKomplain(),
				"PengaduanCounter" => $this->arsipKompModel->CountPengaduanExpVisibility(),
				'absensi_notif' => $this->absensiModel->getPending()
			];
			return view('global/menu/pengaduan', $data);
		} else {
			return redirect()->to('/login');
		}
	}

	public function ShowPengaduanKomplainArsip() // Show Master Data Arsip Komplain
	{
		// seleksi no login
		if (session('uid') != null) {
			// AJAX
			$data['PengaduanList'] = $this->arsipKompModel->getPengaduanUser();
			return view('global/pengaduan_part/list_pengaduan', $data);
		} else {
			return redirect()->to('/dashboard');
		}
	}

	//----------------------------------- form Pengaduan -----------------------------------

	public function DetailPengaduan_Form()
	{
		// seleksi login
		if (session('uid') != null) {
			// $data = [
			// 	'arsipKomp' => $this->arsipKompModel->getAll(),
			// 	'validation' => \Config\Services::Validation()
			// ];
			return view('global/pengaduan_part/detail_pengaduan_modal');
		} else {
			return redirect()->to('/dashboard');
		}
	}

	public function PengaduanForm() // Edit Form In Master Data Item
	{
		// seleksi no login
		if (session('uid') != null) {
			// AJAX
			$data = [
				'validation' => \Config\Services::Validation(),
				'user' => $this->userModel->getUserId(session('uid')),
			];
			return view('global/pengaduan_part/pengaduan_form', $data);
		} else {
			return redirect()->to('/dashboard');
		}
	}

	// ..................................Visibilitas Experience Function.................................
	public function PengaduanDilihat()
	{
		if (session('uid') != null) {
			$no_komplain = $this->request->getPost('no_pengaduan');
			$data = array(
				'status' => 'Dilihat',
				'waktu' => date("Y-m-d"),
			);
			// DEBUG DATA
			if ($this->arsipKompModel->UpdatePengaduanVisibility($data, $no_komplain)) {
				$response = [
					'success' => true,
					'data' => $data,
					'no_komplain' => $no_komplain,
					'session' => session('uid')
				];
			}
			return $this->response->setJSON($response);
		} else {
			return redirect()->to('/login');
		}
	}

	public function CountKomplainVisibilityDilihat() // pengaduan 
	{
		if (session('uid') != null) {
			$total = $this->arsipKompModel->CountPengaduanExpVisibility();
			// DEBUG DATA
			$response = [
				'success' => true,
				'msg' => 'success',
				'totalcount' => $total, // ini yang dipakai
				'session' => session('uid')
			];
			return $this->response->setJSON($response);
		} else {
			return redirect()->to('/login');
		}
	}

	// ---------------------------------------- Aksi Pengaduan -----------------------------------------
	public function GetUidPengaduanArsipKomplain() // Pick uid arsip Komplain
	{
		// seleksi no login
		if (session('uid') != null) {
			// AJAX
			$id = $this->request->getPost("id_arsipKomp");
			$data = $this->arsipKompModel->getIdArsipKomp($id);
			echo json_encode($data);
		} else {
			return redirect()->to('/dashboard');
		}
	}

	public function GetPengaduanAdminArsipKomplain() // Pick uid arsip Komplain
	{
		// seleksi no login
		if (session('uid') != null) {
			// AJAX
			$id = $this->request->getPost("id_arsipKomp");
			$data = $this->arsipKompModel->getUidAdminArsipKomp($id);
			echo json_encode($data);
		} else {
			return redirect()->to('/dashboard');
		}
	}

	public function adukan()
	{
		if (session('uid') != null) {
			$validation = \Config\Services::Validation();
			$rules = [
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
			];
			// jika valdiasi tidakk lolos maka redirect ke halaman edit
			if (!$this->validate($rules)) {
				$response = [
					'success' => false,
					'msg' => '<div class="notif-failed"><i class="fas fa-fw fa-exclamation-triangle text-danger mr-2"></i>Validasi Input Edit Data User Gagal !</div>',
					// get error
					'judul' => $validation->getError('judul'),
					'isi' => $validation->getError('isi'),
					'foto' => $validation->getError('foto'),
				];
				return $this->response->setJSON($response);
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
			$data = [
				'no_komplain' => $no_komp,
				'uid_komplain' => $uid,
				'email_komplain' => $inputEmail,
				'judul_komplain' => $judul,
				'isi_komplain' => $isi,
				'foto_komplain' => $namaFoto,
				'waktu_komplain' => date("Y-m-d h:i:sa")
			];
			// var_dump($data);
			// die;


			$aktivitas = session('nama') . " mengajukan Komplain dengan nomor komplain : " . $no_komp . ".";

			// insert user aktivity saat melakukan pengaduan
			$this->userActivityModel->insert([
				'uid_aktivitas' => session('uid'),
				'aktivitas' => $aktivitas,
				'waktu_aktivitas' => date("Y-m-d H:i:s")
			]);

			if ($this->komplainModel->insert($data)) {
				$response = [
					'success' => true,
					'msg' => '<div class="notif-success"><i class="fas fa-fw fa-check-circle text-green mr-2"></i>Update Data User Berhasil !</div>',
				];
			} else {
				$response = [
					'success' => true,
					'msg' => '<div class="notif-failed"><i class="fas fa-fw fa-exclamation-triangle text-danger mr-2"></i>Update Data User Gagal !</div>',
				];
			}

			return $this->response->setJSON($response);
			// return redirect()->to('/Menu/pengaduan');
		} else {
			return redirect()->to('/login');
		}
	}

	// ================================================== Dashboard Utama ==================================================

	public function Dashboard()
	{
		if (session('uid') != null) {
			$data = [
				"title" => "Dashboard | IBMAETER",
				"CurrentMenu" => "dashboard",
				"info" => $this->newsModel->showTask(),
				"infoV" => $this->newsModel->showExpVisibility(), // isi pengumuman dropdown
				"infoCV" => $this->newsModel->CountExpVisibility(array('uid' => session('uid'))), // counter pengumuman
				'user' => $this->userModel->getUserId(session('uid')),
				"log_notifs" => $this->LogModel->notifsLog(),
				"komplain_notifs" => $this->komplainModel->notifsKomplain(),
				"PengaduanCounter" => $this->arsipKompModel->CountPengaduanExpVisibility(),
				'absensi_notif' => $this->absensiModel->getPending(),
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
				"sj5" => $this->barangModel->stockjenis5(),
				"cc1" => $this->barangModel->costclassA(),
				"cc2" => $this->barangModel->costclassB(),
				"cc3" => $this->barangModel->costclassC(),
				"cc4" => $this->barangModel->costclassD(),
				"cc5" => $this->barangModel->costclassE(),
				"cc6" => $this->barangModel->costclassF(),
				"cc7" => $this->barangModel->costclassG(),
				"cw1" => $this->barangModel->weightclassA(),
				"cw2" => $this->barangModel->weightclassB(),
				"cw3" => $this->barangModel->weightclassC(),
				"cw4" => $this->barangModel->weightclassD(),
				"cw5" => $this->barangModel->weightclassE(),
				"cw6" => $this->barangModel->weightclassF(),
				"cw7" => $this->barangModel->weightclassG(),
				"cf" => $this->userModel->countFemale(),
				"cm" => $this->userModel->countMale(),
				"inc" => $this->LogModel->stockIncome(),
				"otc" => $this->LogModel->stockOutcome(),
				"cin" => $this->LogModel->countIncome(),
				"cout" => $this->LogModel->countOutcome()
			];

			$nama = session('nama');
			session()->setFlashdata('msg', '<div class="alert alert-success alert-dismissible fade show success-login" role="alert">
				Hai <strong>' . $nama . '</strong>, Selamat datang di website <strong>IBMAETER</strong>, selamat bekerja...
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>');

			return view('global/menu/dashboard', $data);
		} else {
			return redirect()->to('/login');
		}
	}
}
