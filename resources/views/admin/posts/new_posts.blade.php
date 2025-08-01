<x-header>{{ $judul }}</x-header>
<div class="content-wrapper">
    <x-breadcrumb>{{ $judul }}</x-breadcrumb>

    <section class="content">
        <div class="container-fluid">

            <form action="/admin/blog/posts/store" method="post" id="formAddPosts" enctype="multipart/form-data">
                @csrf
                <div class="row">

                    <div class="col-lg-8">
                        <div class="card card-primary card-outline shadow-lg">
                            <div class="card-header">
                                <input class="form-control @error('post_title') is-invalid @enderror" type="text"
                                    placeholder="Tambahkan judul berita" name="post_title" id="post_title"
                                    value="{{ old('post_title') }}">
                                @error('post_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="card-body">
                                <textarea id="summernote" name="post_content" id="post_content">{{ old('post_content') }}</textarea>
                                @error('post_content')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card card-primary card-outline shadow-lg">
                            <div class="card-header">
                                <h6 class="m-0"><i class='fas fa-list-ul spaced-icon'></i>Ketegori</h6>
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless table-sm">
                                    <tbody>
                                        @foreach ($categories as $category)
                                            <tr>
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="post_categories[]" id="category_{{ $category->id }}"
                                                            value="{{ $category->id }}"
                                                            {{ in_array($category->id, old('post_categories', [])) ? 'checked' : '' }}>
                                                        <label class="form-check-label"
                                                            for="category_{{ $category->id }}">
                                                            {{ $category->name }}
                                                        </label>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @error('post_categories')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                                <input type="hidden" name="selectedCategories" id="selectedCategories">
                            </div>
                            <div class="card-footer">
                                <button type="button" class="btn btn-warning btn-sm float-right" data-toggle="modal"
                                    data-target="#addKategori">
                                    <i class='fas fa-plus spaced-icon'></i>Kategori
                                </button>
                            </div>
                        </div>

                        <div class="card card-primary card-outline shadow-lg">
                            <div class="card-header">
                                <h6 class="m-0"><i class='fas fa-shield-alt spaced-icon'></i>Publikasi</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>Status</label>
                                            <select
                                                class="form-control select2bs4 @error('post_status') is-invalid @enderror"
                                                style="width: 100%;" name="post_status" id="post_status">
                                                <option value="Publish"
                                                    {{ old('post_status') == 'Publish' ? 'selected' : '' }}>Diterbitkan
                                                </option>
                                                <option value="Draft"
                                                    {{ old('post_status') == 'Draft' ? 'selected' : '' }}>Konsep
                                                </option>
                                            </select>
                                            @error('post_status')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Komentar</label>
                                        <select
                                            class="form-control select2bs4 @error('post_comment_status') is-invalid @enderror"
                                            style="width: 100%;" name="post_comment_status" id="post_comment_status">
                                            <option value="open"
                                                {{ old('post_comment_status') == 'open' ? 'selected' : '' }}>Diizinkan
                                            </option>
                                            <option value="close"
                                                {{ old('post_comment_status') == 'close' ? 'selected' : '' }}>Tidak
                                                diizinkan</option>
                                        </select>
                                        @error('post_comment_status')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm">
                                    <div class="form-group row">
                                        <label for="foto" class="col-sm-6 col-form-label">Gambar</label>
                                        <div class="col-sm-12">
                                            <div class="custom-file">
                                                <input type="file"
                                                    class="custom-file-input @error('post_image') is-invalid @enderror"
                                                    name="post_image" id="customFile">
                                                <label class="custom-file-label" for="selectedFileName"
                                                    id="selectedFileName">Pilih File Foto</label>
                                                @error('post_image')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="container-fluid mb-3">
                                        <img id="previewImage" src="#" alt="Preview Image"
                                            style="max-width: 100%; max-height: 200px; display: none;">
                                    </div>

                                    <div class="btn-group mr-2" role="group" aria-label="First group">
                                        <a href="{{ route('admin.blog.posts') }}" class="btn btn-warning btn-sm">
                                            <i class='fas fa-times spaced-icon'></i>Batal
                                        </a>
                                    </div>
                                    <div class="btn-group mr-2" role="group" aria-label="Second group">
                                        <button type="submit" class="btn btn-success btn-sm"><i
                                                class='fas fa-paper-plane spaced-icon'></i>Simpan Post</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card card-primary card-outline shadow-lg">
                            <div class="card-header">
                                <h6 class="m-0"><i class='fas fa-tags spaced-icon'></i>Tags</h6>
                            </div>
                            <div class="card-body">
                                <select class="form-control select2 @error('post_tags') is-invalid @enderror"
                                    name="post_tags[]" id="tags" multiple="multiple">
                                    <!-- Pilihan tags yang ada dapat dimuat di sini jika diperlukan -->
                                </select>
                                @error('post_tags')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
            </form>

        </div>
    </section>

</div>

<!-- Modal -->
<div class="modal fade" id="addKategori" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Kategori</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="/new_posts/kategori/simpan" method="post" id="formTambahKategori">
                @csrf
                <div class="modal-body">
                    <!-- Input untuk nama kategori -->
                    <div class="form-group">
                        <label for="category_name_modal">Nama Kategori</label>
                        <input class="form-control" type="text" placeholder="Tambahkan kategori...."
                            name="category_name" id="category_name_modal" required>
                    </div>

                    <!-- Input untuk keterangan kategori -->
                    <div class="form-group">
                        <label for="category_keterangan_modal">Keterangan</label>
                        <textarea class="form-control" placeholder="Tambahkan keterangan...." name="category_keterangan"
                            id="category_keterangan_modal" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<x-footer></x-footer>

<script>
    $(document).ready(function() {
        @if (Session::has('toastr'))
            let toastrData = {!! json_encode(Session::get('toastr')) !!};
            toastr[toastrData.type](toastrData.message);
        @endif
    });
</script>

<script>
    $(document).ready(function() {
        @if (Session::has('toastr'))
            let toastrData = {!! json_encode(Session::get('toastr')) !!};
            toastr.options = {
                progressBar: true,
                positionClass: 'toast-top-right',
                showDuration: 300,
                hideDuration: 1000,
                timeOut: 5000,
                extendedTimeOut: 1000,
                preventDuplicates: true,
                closeButton: true,
            };
            toastr[toastrData.type](toastrData.message);
        @endif


        $(document).ready(function() {

            $('#formTambahKategori').submit(function(event) {
                event.preventDefault();

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        toastr.success(response.message);
                        $('#addKategori').modal('hide');
                        location.reload();
                    },
                    error: function(xhr) {
                        $.each(xhr.responseJSON.errors, function(key, value) {
                            toastr.error(value);
                        });
                    }
                });
            });
        });
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const fileInput = document.querySelector("input[name='post_image']");
        const selectedFileName = document.querySelector("#selectedFileName");
        const previewImage = document.querySelector("#previewImage");

        fileInput.addEventListener("change", function() {
            const allowedExtensions = ['jpg', 'jpeg', 'png', 'svg'];
            const maxFileSize = 500 * 1024;
            const file = this.files[0];

            if (file) {
                const fileName = file.name;
                const fileExtension = fileName.split('.').pop().toLowerCase();
                const fileSize = file.size;

                if (allowedExtensions.includes(fileExtension)) {
                    if (fileSize <= maxFileSize) {
                        selectedFileName.textContent = fileName;
                        previewImage.style.display = "block";
                        previewImage.src = URL.createObjectURL(file);
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Ukuran File Terlalu Besar!",
                            text: "Ukuran file foto tidak boleh melebihi 500 KB."
                        });
                        this.value = '';
                        selectedFileName.textContent = "Pilih File Foto";
                        previewImage.style.display = "none";
                    }
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Jenis File Tidak Diijinkan!",
                        text: "Anda hanya dapat mengimpor file dengan ekstensi .jpg, .jpeg, .png, atau .svg."
                    });
                    this.value = '';
                    selectedFileName.textContent = "Pilih File Foto";
                    previewImage.style.display = "none";
                }
            } else {

                selectedFileName.textContent = "Pilih File Foto";
                previewImage.style.display = "none";
            }
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#tags').select2({
            tags: true, // Mengizinkan pembuatan tags baru
            tokenSeparators: [',', ' '],
            placeholder: "Pilih atau buat tags"
        });
    });
</script>
