<?php

namespace App\Controllers;

use App\Models\Barang_Model;
use App\Models\Admin_Model;
use App\Models\userModel;
use App\Models\Pengumuman_Model;
use App\Models\Komplain_Model;
use App\Models\ArsipKomp_Model;
use App\Models\Log_Model;
use App\Models\Absensi_Model;
use App\Models\userActivity_Model;
use Dompdf\Dompdf;


class Exlapor extends BaseController
{
	/**
	 * Instance of the main Request object.
	 *
	 * @var HTTP\IncomingRequest
	 */
	protected $request; // Menghilangkan alert getPost()
	protected $session;
	protected $adminModel;
	protected $userModel;
	protected $barangModel;
	protected $newsModel;
	protected $komplainModel;
	protected $arsipKompModel;
	protected $Log_Model;
	protected $absensiModel;
	protected $userActivityModel;

	public function __construct()
	{
		$this->session = \Config\Services::session();
		$this->session->start();

		$this->adminModel = new Admin_Model();
		$this->userModel = new userModel();
		$this->barangModel = new Barang_Model();
		$this->newsModel = new Pengumuman_Model();
		$this->komplainModel = new Komplain_Model();
		$this->arsipKompModel = new ArsipKomp_Model();
		$this->LogModel = new Log_Model();
		$this->absensiModel = new Absensi_Model();
		$this->userActivityModel = new userActivity_Model();
	}



	// =========================================================================================================
	// ======================================== Export & Print Data Admin ======================================
	// =========================================================================================================

	public function exceluser()
	{
		// seleksi login
		if (session('uid') != null) {
			// jika user merupakan Admin
			if (session('role') == 0) {
				$data = [
					"title" => "EXCEL USER | IBMAETER",
					"user" => $this->userModel->getJoinDivisionUser(),
					"us" => $this->adminModel->countUser()
				];

				return view('admin/export/exxlsUser', $data);
			} else {
				return redirect()->to('/dashboard');
			}
		} else {
			return redirect()->to('/login');
		}
	}

	public function docuser()
	{
		// seleksi login
		if (session('uid') != null) {
			// jika user merupakan Admin
			if (session('role') == 0) {
				$data = [
					"title" => "DOC USER | IBMAETER",
					"user" => $this->userModel->getJoinDivisionUser(),
					"us" => $this->adminModel->countUser()
				];

				return view('admin/export/exdocUser', $data);
			} else {
				return redirect()->to('/dashboard');
			}
		} else {
			return redirect()->to('/login');
		}
	}

	public function pdfuser()
	{
		// seleksi login
		if (session('uid') != null) {
			// jika user merupakan Admin
			if (session('role') == 0) {
				$data = [
					"title" => "PDF USER | IBMAETER",
					"user" => $this->userModel->getJoinDivisionUser(),
					"us" => $this->adminModel->countUser()
				];

				$html = view('admin/export/expdfUser', $data);

				$dompdf = new Dompdf();
				$dompdf->loadHtml($html);
				$dompdf->setPaper('A4', 'potrait');
				$dompdf->render();
				$dompdf->stream('Data-User.pdf');
			} else {
				return redirect()->to('/dashboard');
			}
		} else {
			return redirect()->to('/login');
		}
	}

	public function excelkomplain()
	{
		// seleksi login
		if (session('uid') != null) {
			// jika user merupakan Admin
			if (session('role') == 0) {
				$data = [
					"title" => "EXCEL KOMPLAIN | IBMAETER",
					"user" => $this->adminModel->getUser(),
					'komplain' => $this->komplainModel->getKomplain(),
				];

				return view('admin/export/exxlsKomplain', $data);
			} else {
				return redirect()->to('/dashboard');
			}
		} else {
			return redirect()->to('/login');
		}
	}

	public function dockomplain()
	{
		// seleksi login
		if (session('uid') != null) {
			// jika user merupakan Admin
			if (session('role') == 0) {
				$data = [
					"title" => "DOC KOMPLAIN | IBMAETER",
					"user" => $this->adminModel->getUser(),
					'komplain' => $this->komplainModel->getKomplain(),
				];

				return view('admin/export/exdocKomplain', $data);
			} else {
				return redirect()->to('/dashboard');
			}
		} else {
			return redirect()->to('/login');
		}
	}

	public function pdfkomplain()
	{
		// seleksi login
		if (session('uid') != null) {
			// jika user merupakan Admin
			if (session('role') == 0) {
				$data = [
					"title" => "PDF KOMPLAIN | IBMAETER",
					"user" => $this->adminModel->getUser(),
					'komplain' => $this->komplainModel->getKomplain(),
				];

				$html = view('admin/export/expdfKomplain', $data);

				$dompdf = new Dompdf();
				$dompdf->loadHtml($html);
				$dompdf->setPaper('A4', 'potrait');
				$dompdf->render();
				$dompdf->stream('Data-Komplain.pdf');
			} else {
				return redirect()->to('/dashboard');
			}
		} else {
			return redirect()->to('/login');
		}
	}

	public function excelabsensi()
	{
		// seleksi login
		if (session('uid') != null) {
			// jika user merupakan Admin
			if (session('role') == 0) {
				$data = [
					"title" => "EXCEL ABSENSI | IBMAETER",
					'user' => $this->userModel->getUserId(session('uid')),
					"absensi" => $this->absensiModel->getAbsen(),
					"countPresent" => $this->absensiModel->countPresent(),
					"countLate" => $this->absensiModel->countLate(),
					"countPermission" => $this->absensiModel->countPermission(),
					"totalUser" => $this->userModel->countUser()
				];

				return view('admin/export/exxlsAbsensi', $data);
			} else {
				return redirect()->to('/dashboard');
			}
		} else {
			return redirect()->to('/login');
		}
	}

	public function docabsensi()
	{
		// seleksi login
		if (session('uid') != null) {
			// jika user merupakan Admin
			if (session('role') == 0) {
				$data = [
					"title" => "DOC ABSENSI | IBMAETER",
					'user' => $this->userModel->getUserId(session('uid')),
					"absensi" => $this->absensiModel->getAbsen(),
					"countPresent" => $this->absensiModel->countPresent(),
					"countLate" => $this->absensiModel->countLate(),
					"countPermission" => $this->absensiModel->countPermission(),
					"totalUser" => $this->userModel->countUser()
				];

				return view('admin/export/exdocAbsensi', $data);
			} else {
				return redirect()->to('/dashboard');
			}
		} else {
			return redirect()->to('/login');
		}
	}

	public function pdfabsensi()
	{
		// seleksi login
		if (session('uid') != null) {
			// jika user merupakan Admin
			if (session('role') == 0) {
				$data = [
					"title" => "PDF ABSENSI | IBMAETER",
					'user' => $this->userModel->getUserId(session('uid')),
					"absensi" => $this->absensiModel->getAbsen(),
					"countPresent" => $this->absensiModel->countPresent(),
					"countLate" => $this->absensiModel->countLate(),
					"countPermission" => $this->absensiModel->countPermission(),
					"totalUser" => $this->userModel->countUser()
				];

				$html = view('admin/export/expdfAbsensi', $data);

				$dompdf = new Dompdf();
				$dompdf->loadHtml($html);
				$dompdf->setPaper('A4', 'potrait');
				$dompdf->render();
				$dompdf->stream('Data-Absensi.pdf');
			} else {
				return redirect()->to('/dashboard');
			}
		} else {
			return redirect()->to('/login');
		}
	}

	public function excelaktivitas()
	{
		// seleksi login
		if (session('uid') != null) {
			// jika user merupakan Admin
			if (session('role') == 0) {
				$data = [
					"title" => "EXCEL AKTIVITAS | IBMAETER",
					"user" => $this->adminModel->getUser(),
					'aktivitas' => $this->userActivityModel->getActivity()
				];

				return view('admin/export/exxlsAktivitas', $data);
			} else {
				return redirect()->to('/dashboard');
			}
		} else {
			return redirect()->to('/login');
		}
	}

	public function docaktivitas()
	{
		// seleksi login
		if (session('uid') != null) {
			// jika user merupakan Admin
			if (session('role') == 0) {
				$data = [
					"title" => "DOC AKTIVITAS | IBMAETER",
					"user" => $this->adminModel->getUser(),
					'aktivitas' => $this->userActivityModel->getActivity()
				];

				return view('admin/export/exdocAktivitas', $data);
			} else {
				return redirect()->to('/dashboard');
			}
		} else {
			return redirect()->to('/login');
		}
	}

	public function pdfaktivitas()
	{
		// seleksi login
		if (session('uid') != null) {
			// jika user merupakan Admin
			if (session('role') == 0) {
				$data = [
					"title" => "PDF AKTIVITAS | IBMAETER",
					"user" => $this->adminModel->getUser(),
					'aktivitas' => $this->userActivityModel->getActivity()
				];

				$html = view('admin/export/expdfAktivitas', $data);

				$dompdf = new Dompdf();
				$dompdf->loadHtml($html);
				$dompdf->setPaper('A4', 'potrait');
				$dompdf->render();
				$dompdf->stream('Data-Aktivitas.pdf');
			} else {
				return redirect()->to('/dashboard');
			}
		} else {
			return redirect()->to('/login');
		}
	}

	public function excelizin()
	{
		// seleksi login
		if (session('uid') != null) {
			// jika user merupakan Admin
			if (session('role') == 0) {
				$data = [
					"title" => "EXCEL PERIZINAN | IBMAETER",
					"log_item" => $this->LogModel->ReadLogItem(),
					"log_notifs" => $this->LogModel->notifsLog(),
					"komplain_notifs" => $this->komplainModel->notifsKomplain(),
					'absensi_notif' => $this->absensiModel->getPending()
				];

				return view('admin/export/exxlsIzin', $data);
			} else {
				return redirect()->to('/dashboard');
			}
		} else {
			return redirect()->to('/login');
		}
	}

	public function docizin()
	{
		// seleksi login
		if (session('uid') != null) {
			// jika user merupakan Admin
			if (session('role') == 0) {
				$data = [
					"title" => "DOC PERIZINAN | IBMAETER",
					"log_item" => $this->LogModel->ReadLogItem(),
					"log_notifs" => $this->LogModel->notifsLog(),
					"komplain_notifs" => $this->komplainModel->notifsKomplain(),
					'absensi_notif' => $this->absensiModel->getPending()
				];

				return view('admin/export/exdocIzin', $data);
			} else {
				return redirect()->to('/dashboard');
			}
		} else {
			return redirect()->to('/login');
		}
	}

	public function pdfizin()
	{
		// seleksi login
		if (session('uid') != null) {
			// jika user merupakan Admin
			if (session('role') == 0) {
				$data = [
					"title" => "PDF PERIZINAN | IBMAETER",
					"log_item" => $this->LogModel->ReadLogItem(),
					"log_notifs" => $this->LogModel->notifsLog(),
					"komplain_notifs" => $this->komplainModel->notifsKomplain(),
					'absensi_notif' => $this->absensiModel->getPending()
				];

				$html = view('admin/export/expdfIzin', $data);

				$dompdf = new Dompdf();
				$dompdf->loadHtml($html);
				$dompdf->setPaper('A4', 'potrait');
				$dompdf->render();
				$dompdf->stream('Data-Perizinan.pdf');
			} else {
				return redirect()->to('/dashboard');
			}
		} else {
			return redirect()->to('/login');
		}
	}

	public function pdfprintUser()
	{
		// seleksi login
		if (session('uid') != null) {
			// jika user merupakan Admin
			if (session('role') == 0) {
				$data = [
					"title" => "PDF USER | IBMAETER",
					"user" => $this->userModel->getJoinDivisionUser(),
					"us" => $this->adminModel->countUser()
				];
				return view('admin/print/printUser', $data);
			} else {
				return redirect()->to('/dashboard');
			}
		} else {
			return redirect()->to('/login');
		}
	}

	public function pdfprintKomplain()
	{
		// seleksi login
		if (session('uid') != null) {
			// jika user merupakan Admin
			if (session('role') == 0) {
				$data = [
					"title" => "PDF KOMPLAIN | IBMAETER",
					"user" => $this->adminModel->getUser(),
					'komplain' => $this->komplainModel->getKomplain()
				];

				return view('admin/print/printKomplain', $data);
			} else {
				return redirect()->to('/dashboard');
			}
		} else {
			return redirect()->to('/login');
		}
	}

	public function pdfprintAktivitas()
	{
		// seleksi login
		if (session('uid') != null) {
			// jika user merupakan Admin
			if (session('role') == 0) {
				$data = [
					"title" => "PDF AKTIVITAS | IBMAETER",
					"user" => $this->adminModel->getUser(),
					'aktivitas' => $this->userActivityModel->getActivity()
				];

				return view('admin/print/printAktivitas', $data);
			} else {
				return redirect()->to('/dashboard');
			}
		} else {
			return redirect()->to('/login');
		}
	}

	public function pdfprintAbsensi()
	{
		// seleksi login
		if (session('uid') != null) {
			// jika user merupakan Admin
			if (session('role') == 0) {
				$data = [
					"title" => "PDF Absensi | IBMAETER",
					'user' => $this->userModel->getUserId(session('uid')),
					"absensi" => $this->absensiModel->getAbsen(),
					"countPresent" => $this->absensiModel->countPresent(),
					"countLate" => $this->absensiModel->countLate(),
					"countPermission" => $this->absensiModel->countPermission(),
					"totalUser" => $this->userModel->countUser()
				];

				return view('admin/print/printAbsensi', $data);
			} else {
				return redirect()->to('/dashboard');
			}
		} else {
			return redirect()->to('/login');
		}
	}

	public function pdfprintIzin()
	{
		// seleksi login
		if (session('uid') != null) {
			// jika user merupakan Admin
			if (session('role') == 0) {
				$data = [
					"title" => "PDF PERIZINAN | IBMAETER",
					"log_item" => $this->LogModel->ReadLogItem()
				];

				return view('admin/print/printIzin', $data);
			} else {
				return redirect()->to('/dashboard');
			}
		} else {
			return redirect()->to('/login');
		}
	}

	public function pdfprintNotaizin()
	{
		// seleksi login
		if (session('uid') != null) {
			$id = $this->request->getPost('notaIzin');

			$data = [
				"title" => "NOTA PERIZINAN | IBMAETER",
				"log_item" => $this->LogModel->NotaItem($id)
			];
			return view('admin/print/printNotaizin', $data);
		} else {
			return redirect()->to('/login');
		}
	}



	// =========================================================================================================
	// ==================================== Export & Print Data Global =========================================
	// =========================================================================================================
	public function excelbarang()
	{
		if (session('uid') != null) {
			$data = [
				"title" => "Excel BARANG | IBMAETER",
				"item" => $this->barangModel->getItems()
			];

			return view('global/export/exxlsBarang', $data);
		} else {
			return redirect()->to('/login');
		}
	}

	public function docbarang()
	{
		if (session('uid') != null) {
			$data = [
				"title" => "DOC BARANG | IBMAETER",
				"item" => $this->barangModel->getItems()
			];

			return view('global/export/exdocBarang', $data);
		} else {
			return redirect()->to('/login');
		}
	}

	public function pdfbarang()
	{
		if (session('uid') != null) {
			$data = [
				"title" => "PDF BARANG | IBMAETER",
				"item" => $this->barangModel->getItems()
			];

			$html = view('global/export/expdfBarang', $data);

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
				"title" => "EXCEL SPESIFIKASI | IBMAETER",
				"item" => $this->barangModel->getItems(),
				"supplier" => $this->barangModel->viewSuppliers(),
				"spec" => $this->barangModel->joinSupplier()
			];

			return view('global/export/exxlsSpesifikasi', $data);
		} else {
			return redirect()->to('/login');
		}
	}

	public function docspesifikasi()
	{
		if (session('uid') != null) {
			$data = [
				"title" => "DOC SPESIFIKASI | IBMAETER",
				"item" => $this->barangModel->getItems(),
				"supplier" => $this->barangModel->viewSuppliers(),
				"spec" => $this->barangModel->joinSupplier()
			];

			return view('global/export/exdocSpesifikasi', $data);
		} else {
			return redirect()->to('/login');
		}
	}

	public function pdfspesifikasi()
	{
		if (session('uid') != null) {
			$data = [
				"title" => "PDF SPESIFIKASI | IBMAETER",
				"item" => $this->barangModel->getItems(),
				"supplier" => $this->barangModel->viewSuppliers(),
				"spec" => $this->barangModel->joinSupplier()
			];

			$html = view('global/export/expdfSpesifikasi', $data);

			$dompdf = new Dompdf();
			$dompdf->loadHtml($html);
			$dompdf->setPaper('A4', 'potrait');
			$dompdf->render();
			$dompdf->stream('Tabel-Spesifikasi-Barang-2021.pdf');
		} else {
			return redirect()->to('/login');
		}
	}

	public function excelstatizin()
	{
		if (session('uid') != null) {
			$data = [
				"title" => "EXCEL STATUS PERIZINAN | IBMAETER",
				"log_item" => $this->LogModel->ReadLogItem(),
				"log_notifs" => $this->LogModel->notifsLog(),
				"komplain_notifs" => $this->komplainModel->notifsKomplain(),
				'absensi_notif' => $this->absensiModel->getPending()
			];

			return view('global/export/exxlsStatizin', $data);
		} else {
			return redirect()->to('/login');
		}
	}

	public function docstatizin()
	{
		if (session('uid') != null) {
			$data = [
				"title" => "DOC STATUS PERIZINAN | IBMAETER",
				"log_item" => $this->LogModel->ReadLogItem(),
				"log_notifs" => $this->LogModel->notifsLog(),
				"komplain_notifs" => $this->komplainModel->notifsKomplain(),
				'absensi_notif' => $this->absensiModel->getPending()
			];

			return view('global/export/exdocStatizin', $data);
		} else {
			return redirect()->to('/login');
		}
	}

	public function pdfstatizin()
	{
		if (session('uid') != null) {
			$data = [
				"title" => "PDF STATUS PERIZINAN | IBMAETER",
				"log_item" => $this->LogModel->ReadLogItem(),
				"log_notifs" => $this->LogModel->notifsLog(),
				"komplain_notifs" => $this->komplainModel->notifsKomplain(),
				'absensi_notif' => $this->absensiModel->getPending()
			];

			$html = view('global/export/expdfStatizin', $data);

			$dompdf = new Dompdf();
			$dompdf->loadHtml($html);
			$dompdf->setPaper('A4', 'potrait');
			$dompdf->render();
			$dompdf->stream('Status-Perizinan.pdf');
		} else {
			return redirect()->to('/login');
		}
	}

	public function pdfprintBarang()
	{
		if (session('uid') != null) {
			$data = [
				"title" => "PDF BARANG | IBMAETER",
				"item" => $this->barangModel->getItems()
			];

			return view('global/print/printBarang', $data);
		} else {
			return redirect()->to('/login');
		}
	}

	public function pdfprintSpesifikasi()
	{
		if (session('uid') != null) {
			$data = [
				"title" => "PDF SPESIFIKASI | IBMAETER",
				"item" => $this->barangModel->getItems(),
				"supplier" => $this->barangModel->viewSuppliers(),
				"spec" => $this->barangModel->joinSupplier()
			];

			return view('global/print/printSpesifikasi', $data);
		} else {
			return redirect()->to('/login');
		}
	}

	public function pdfprintStatizin()
	{
		if (session('uid') != null) {
			$data = [
				"title" => "PDF STATUS PERIZINAN | IBMAETER",
				"log_item" => $this->LogModel->ReadLogItem()
			];

			return view('global/print/printStatizin', $data);
		} else {
			return redirect()->to('/login');
		}
	}

	public function pdfprintBulanan()
	{
		if (session('uid') != null) {
			$limitBawah = $this->request->getVar('tgl_before');
			$limitAtas = $this->request->getVar('tgl_after');

			// echo $limitBawah . " - " . $limitAtas;

			$data = [
				"title" => "PDF DETAIL LAPORAN BULANAN | IBMAETER",
				"item" => $this->barangModel->getItems(),
				"supplier" => $this->barangModel->viewSuppliers(),
				"spec" => $this->barangModel->joinSupplier(),
				"log_item" => $this->LogModel->ReadLogItem($limitBawah, $limitAtas),
				"cf" => $this->userModel->countFemale(),
				"cm" => $this->userModel->countMale(),
				"scin" => $this->LogModel->sumIncome($limitBawah, $limitAtas),
				"scout" => $this->LogModel->sumOutcome($limitBawah, $limitAtas),
				"sj1" => $this->barangModel->stockjenis1(),
				"sj2" => $this->barangModel->stockjenis2(),
				"sj3" => $this->barangModel->stockjenis3(),
				"sj4" => $this->barangModel->stockjenis4(),
				"sj5" => $this->barangModel->stockjenis5(),
				"sst" => $this->barangModel->sumStock(),
				"swh" => $this->barangModel->sumWeight(),
				"sco" => $this->barangModel->sumCost(),
				"countPresent" => $this->absensiModel->countPresent($limitBawah, $limitAtas),
				"countLate" => $this->absensiModel->countLate($limitBawah, $limitAtas),
				"countPermission" => $this->absensiModel->countPermission($limitBawah, $limitAtas),
				"startDate" => $limitBawah,
				"endDate" => $limitAtas
			];

			$aktivitas = session('nama') . " mencetak Laporan Bulanan";
			// insert user aktivity saat melakukan input stok masuk item/barang
			$this->userActivityModel->insert([
				'uid_aktivitas' => session('uid'),
				'aktivitas' => $aktivitas,
				'waktu_aktivitas' => date("Y-m-d H:i:s")
			]);

			return view('global/print/printBulanan', $data);
		} else {
			return redirect()->to('/login');
		}
	}

	public function pdfprintNotaspesifikasi()
	{
		if (session('uid') != null) {
			$id = $this->request->getPost('print_nota');

			$data = [
				"title" => "NOTA DETAIL SPESIFIKASI | IBMAETER",
				"spec" => $this->barangModel->notaDetail($id)
			];

			return view('global/print/printNotaspesifikasi', $data);
		} else {
			return redirect()->to('/login');
		}
	}
}
