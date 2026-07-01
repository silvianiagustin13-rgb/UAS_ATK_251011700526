<?php include 'partials/header.php'; ?>

<div class="main-content">

<div class="page-header">
    <h1><i class="fas fa-user-shield me-2" style="color:#0E8C7F;"></i>Manajemen <span>User</span></h1>
    <button id="btnTambah" class="btn-os-primary">
        <i class="fas fa-user-plus"></i> Tambah User
    </button>
</div>

<div class="os-card">
  <div class="card-body">
    <div style="overflow-x:auto;">
    <table class="os-table w-100" id="tabel_user">
        <thead>
            <tr>
                <th>No</th>
                <th>Username</th>
                <th>Password</th>
                <th>Dibuat Pada</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
    </div>
  </div>
</div>

</div>

<div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius:16px; border:none; overflow:hidden;">
      <div class="modal-header" style="background:linear-gradient(135deg,#14305C,#0E8C7F); color:white; border:none;">
        <h5 class="modal-title" id="userModalLabel" style="font-weight:800; font-family:'Poppins',sans-serif;">
            <i class="fas fa-user-cog me-2"></i><span id="modalTitleText">Tambah User</span>
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" style="padding:24px;">
        <form id="userForm">
            <input type="hidden" id="user_id" name="user_id">
            <div class="mb-3">
                <label class="form-label"><i class="fas fa-user me-1" style="color:#0E8C7F;"></i>Username</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan username" required>
            </div>
            <div class="mb-3">
                <label class="form-label"><i class="fas fa-lock me-1" style="color:#0E8C7F;"></i>Password</label>
                <input type="text" class="form-control" id="password" name="password" placeholder="Masukkan password" required>
                <small style="color:#94A3B8;">Password ditampilkan dalam teks biasa hanya untuk keperluan tugas akademik (UAS).</small>
            </div>
        </form>
      </div>
      <div class="modal-footer" style="border:none; padding:16px 24px; background:#F7FAFC;">
        <button type="button" class="btn" data-bs-dismiss="modal"
                style="border-radius:10px; font-weight:700; background:#EEF2F9; color:#14305C; border:none; padding:8px 20px;">
            Batal
        </button>
        <button type="button" id="btnSimpan" class="btn-os-primary">
            <i class="fas fa-save me-1"></i> Simpan
        </button>
      </div>
    </div>
  </div>
</div>

<script>
let table;
let currentUserId = null;

$(document).ready(function() {
    toastr.options = { positionClass:'toast-top-right', timeOut:3000, progressBar:true, closeButton:true };

    table = $('#tabel_user').DataTable({
        language: { url: 'https://cdn.datatables.net/plug-ins/1.13.3/i18n/id.json' },
        ajax: {
            url: 'user/read.php',
            dataSrc: 'data'
        },
        columns: [
            { data: null, render: (d, t, r, m) => m.row + 1 },
            { data: 'username' },
            { data: 'password', render: d => '•'.repeat(Math.min(d.length, 10)) },
            { data: 'created_at', render: d => d ? new Date(d).toLocaleString('id-ID') : '-' },
            {
                data: null,
                orderable: false,
                render: (d, t, row) => `
                    <button class="btn-action btn-edit btn-edit-user" data-id="${row.id}"><i class="fas fa-edit"></i> Edit</button>
                    <button class="btn-action btn-delete btn-delete-user" data-id="${row.id}" data-username="${row.username}"><i class="fas fa-trash"></i> Hapus</button>
                `
            }
        ]
    });

    $('#btnTambah').on('click', function() {
        currentUserId = null;
        $('#modalTitleText').text('Tambah User');
        $('#userForm')[0].reset();
        $('#user_id').val('');
        $('#userModal').modal('show');
    });

    $(document).on('click', '.btn-edit-user', function() {
        const id = $(this).data('id');
        currentUserId = id;
        $.get('user/get.php', { id: id }, function(res) {
            if (res.success) {
                $('#modalTitleText').text('Edit User');
                $('#user_id').val(res.data.id);
                $('#username').val(res.data.username);
                $('#password').val(res.data.password);
                $('#userModal').modal('show');
            } else {
                toastr.error(res.message, 'Gagal!');
            }
        }, 'json');
    });

    $('#btnSimpan').on('click', function() {
        const id       = $('#user_id').val();
        const username = $('#username').val().trim();
        const password = $('#password').val().trim();

        if (!username || !password) {
            toastr.warning('Username dan password wajib diisi!', 'Perhatian');
            return;
        }

        const url  = id ? 'user/update.php' : 'user/create.php';
        const data = id ? { user_id: id, username, password } : { username, password };

        $.post(url, data, function(res) {
            if (res.success) {
                toastr.success(res.message, 'Berhasil!');
                $('#userModal').modal('hide');
                table.ajax.reload();
            } else {
                toastr.error(res.message, 'Gagal!');
            }
        }, 'json').fail(function() {
            toastr.error('Terjadi kesalahan pada server.', 'Gagal!');
        });
    });

    $(document).on('click', '.btn-delete-user', function() {
        const id       = $(this).data('id');
        const username = $(this).data('username');

        if (!confirm('Hapus user "' + username + '"?\n\nData tidak dapat dikembalikan!')) return;

        $.post('user/delete.php', { id: id }, function(res) {
            if (res.success) {
                toastr.success(res.message, 'Berhasil!');
                table.ajax.reload();
            } else {
                toastr.error(res.message, 'Gagal!');
            }
        }, 'json').fail(function() {
            toastr.error('Terjadi kesalahan pada server.', 'Gagal!');
        });
    });
});
</script>

<?php include 'partials/footer.php'; ?>