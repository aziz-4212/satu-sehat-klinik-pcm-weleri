<div class="modal fade" id="modal-delete">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Hapus data</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Yakin Ingin Menghapus Data?</p>
            </div>
            <div class="modal-footer justify-content-between">
                <input type="hidden" name="id_delete" id="id_delete">
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn bg-teal" onclick="destroy()">hapus</button>
            </div>
        </div>
    </div>
</div>
<script>
    function remove(id_delete){
		$('#id_delete').val(id_delete);
    }

    function destroy(){
        var id_delete = $('#id_delete').val();
        showOverlay();
        $.ajax({
            type: 'DELETE',
            dataType: 'json',
			url: "/config/"+id_delete,
            data: {
                '_token' : '{{ csrf_token() }}',
                _method: 'DELETE',
                id_delete: id_delete,
            },
            success: function(data){
                console.log(data);
                hideOverlay();
                if(data.status == "success") {
                    setTimeout(function(){ 
                        window.location = "{{ route('config.index') }}";
                    }, 500);
                    toastr.success("Data Berhasil Di Hapus");
                }else {
                    toastr.error("Data Gagal Di Hapus");
                }
            },
            error: function(error) {
                alert("Server/network error\r\n" + error);
            }
        });
    }
</script>