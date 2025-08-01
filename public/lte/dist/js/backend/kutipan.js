


$(document).ready(function() {
  // Tampilkan modal edit ketika tombol edit diklik
  $('#kutipanTable').on('click', '.edit-btn', function() {
      var id = $(this).data('id');

      // Ambil data kategori berdasarkan ID menggunakan AJAX
      $.ajax({
          url: '/admin/blog/kutipan/' + id + '/fetch',
          type: 'GET',
          success: function(response) {
              $('#editId').val(response.id);
              $('#editQuote').val(response.quote);
              $('#editQuoteBy').val(response.quote_by);
              $('#editModal').modal('show');
          },
          error: function(xhr) {
              console.log('Error:', xhr);
          }
      });
  });

  // Submit form edit kategori
  $('#editForm').submit(function(e) {
    e.preventDefault();

    var id = $('#editId').val();
    var formData = {
        quote: $('#editQuote').val(),
        quote_by: $('#editQuoteBy').val(),
        _token: $('input[name=_token]').val(), // Pastikan token CSRF disertakan
    };

    // Kirim permintaan AJAX untuk menyimpan perubahan
    $.ajax({
        url: '/admin/blog/kutipan/' + id + '/update',
        type: 'PUT',
        data: formData,
        success: function(response) {
            $('#editModal').modal('hide');
            $('#kutipanTable').DataTable().ajax.reload();
            toastr.success(response.message);
        },
        error: function(xhr) {
            $.each(xhr.responseJSON.errors, function(key, value) {
                toastr.error(value);
            });
        }
    });
});

});










