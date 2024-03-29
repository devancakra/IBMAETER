<?= csrf_field(); ?>
<div class="modal-body">
    <div class="row">
        <div class="col-sm-12 mb-2">
            <h6 class="font-weight-bold text-center"><i class="fas fa-fw fa-exclamation-triangle mr-2 text-danger"></i>Apakah Anda Yakin anda ingin menghapus user ini ?</h6>
        </div>
        <hr>
        <div class="col-sm-4">
            <div class="show-img text-center mb-2" id="show-add-img" style="height: 370px;">
                <img class="img-thumbnail img-preview disabled" id="show_edit_img" src="<?= base_url('../img/user/default.jpg'); ?>" alt="image preview" style="max-height: 350px; ">
            </div>
        </div>
        <div class="col-sm-4 border-left">
            <div class="form-group">
                <label for="nama_user" class="font-weight-bold"><i class="fas fa-fw fa-user-tag mr-2"></i>Nama User</label>
                <input type="text" class="form-control disabled" id="nama_user" name="user" readonly>
            </div>
            <div class="form-group">
                <label for="email_user" class="font-weight-bold"><i class="fas fa-fw fa-envelope mr-2"></i>E-mail</label>
                <input type="email" class="form-control disabled" id="email_user" name="email" readonly>
            </div>
        </div>
        <div class="col-sm-4 border-left">
            <div class="form-group">
                <label for="ttl" class="font-weight-bold"><i class="fas fa-fw fa-calendar-alt mr-2"></i>Tanggal Lahir</label>
                <input type="date" class="form-control disabled" value="" id="ttl" name="ttl" readonly>
            </div>
            <div class="form-group">
                <label for="gender_user" class="font-weight-bold"><i class="fas fa-fw fa-restroom mr-2"></i>Gender</label>
                <select class="form-control disabled" id="gender" name="gender" readonly disabled>
                    <option value="Laki-laki">Laki-laki</option>
                    <option value="Perempuan">Perempuan</option>
                </select>
            </div>
            <div class="form-group">
                <label for="jenis_user" class="font-weight-bold"><i class="fas fa-fw fa-user-lock mr-2"></i>Role</label>
                <select class="form-control disabled" id="jenis_user" name="role" readonly disabled>
                    <option value="1">User (Pekerja)</option>
                    <option value="0">Admin</option>
                </select>
            </div>
            <div class="form-group">
                <label for="division_user" class="font-weight-bold"><i class="fas fa-fw fa-user-tie mr-2"></i>Divisi</label>
                <select class="form-control disabled" id="division" name="division" readonly disabled>
                </select>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="hidden" name="user_id" id="user_id">
    <button type="button" class="btn btn-dark shadow-sm btn-modal-close" data-dismiss="modal"><i class="fas fa-fw fa-window-close"></i> Batal</button>
    <button type="submit" class="btn btn-danger shadow-sm"><i class="fas fa-fw fa-user-minus mr-2"></i>Hapus User</button>
</div>