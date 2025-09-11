<div class="modal fade" id="ModalDokumentasi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-simple">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                <h4 class="mb-3" id="formTitle"></h4>
                <form id="FormDokumentasi" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="judul" class="form-label">Judul</label>
                        <input type="text" id="judul" name="judul" class="form-control" />
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea id="deskripsi" name="deskripsi" class="form-control"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="gambar" class="form-label">Gambar</label>
                        <input type="file" id="gambar" name="gambar" class="form-control" />
                        <img id="previewGambar" src="" alt="" class="img-thumbnail mt-2"
                            style="max-height:150px; display:none;">
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" id="SimpanDokumentasi" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
