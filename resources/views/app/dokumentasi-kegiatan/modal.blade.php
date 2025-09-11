<div class="modal fade" id="ModalDokumentasi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-simple">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                <h4 class="mb-3" id="formTitle"></h4>
                <form id="FormDokumentasi" method="post" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3"><label for="gambar" class="w-100">
                                    <div class="border rounded d-flex align-items-center justify-content-center mb-2 overflow-hidden position-relative"
                                        style="width:100%; height:150px; background-color:#396c9f00; cursor: pointer;">
                                        <span id="textUpload" class="position-absolute">Pilih
                                            Gambar</span>
                                        <img id="previewGambar" src="" alt=""
                                            class="img-fluid h-100 w-auto">
                                    </div>
                                </label>

                                <label for="gambar" class="form-label">Upload Gambar</label>
                                <input type="file" id="gambar" name="gambar" class="form-control d-none" />
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="judul" class="form-label">Judul</label>
                                <input type="text" id="judul" name="judul" class="form-control" />
                            </div>
                            <div class="mb-3">
                                <label for="deskripsi" class="form-label">Deskripsi</label>
                                <textarea id="deskripsi" name="deskripsi" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-2 mt-3">
                        <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" id="SimpanDokumentasi" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
