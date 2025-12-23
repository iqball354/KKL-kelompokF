document.addEventListener("DOMContentLoaded", function () {
    // ------------------------------------------------------
    // TEMPLATE DOKUMEN
    // ------------------------------------------------------
    function createNewDocRowHTML(type) {
        let div = document.createElement("div");
        div.classList.add("doc-row", "mb-2");
        div.innerHTML = `
            <div class="drive-upload-wrapper">
                <label class="drive-upload-box">
                    <i class="fas fa-file-alt"></i>
                    <span class="drive-text">Upload File</span>
                    <input type="file" name="dokumen_${type}[]" class="file-hidden drive-input">
                </label>
            </div>
            <input type="text" name="deskripsi_${type}[]" class="form-control mt-1" placeholder="Deskripsi">
            <input type="number" name="tahun_${type}[]" class="form-control mt-1" placeholder="Tahun" min="1900" max="2100">
            <button type="button" class="btn btn-danger btn-sm mt-1 remove-doc">Hapus</button>
        `;
        return div;
    }

    // ------------------------------------------------------
    // TEMPLATE LINK DENGAN DESKRIPSI
    // ------------------------------------------------------
    function createLinkRowHTML() {
        let div = document.createElement("div");
        div.classList.add("link-row", "mb-2", "d-flex", "flex-column", "align-items-start");
        div.innerHTML = `
            <div class="d-flex gap-2 w-100 mb-1">
                <input type="url" name="link[]" class="form-control flex-grow-2" placeholder="Masukkan URL">
                <input type="text" name="deskripsi_link[]" class="form-control flex-grow-1" placeholder="Deskripsi Link">
                <button type="button" class="btn btn-danger btn-sm remove-link">Hapus</button>
            </div>
        `;
        return div;
    }

    // ------------------------------------------------------
    // TAMBAH BIDANG KEAHLIAN
    // ------------------------------------------------------
    document.getElementById("add-bidang")?.addEventListener("click", function () {
        let container = document.getElementById("bidang-container");
        let div = document.createElement("div");
        div.classList.add("input-group", "mb-2");
        div.innerHTML = `
            <input type="text" name="bidang_keahlian[]" class="form-control">
            <button type="button" class="btn btn-danger remove-bidang">X</button>
        `;
        container.appendChild(div);
    });

    document.querySelectorAll(".add-bidang-btn").forEach((btn) => {
        btn.addEventListener("click", function () {
            let id = this.dataset.id;
            let container = document.getElementById(`edit-bidang-container-${id}`);
            let div = document.createElement("div");
            div.classList.add("input-group", "mb-2");
            div.innerHTML = `
                <input type="text" name="bidang_keahlian[]" class="form-control">
                <button type="button" class="btn btn-danger remove-bidang">X</button>
            `;
            container.appendChild(div);
        });
    });

    // ------------------------------------------------------
    // TOGGLE DOKUMEN
    // ------------------------------------------------------
    document.querySelectorAll(".toggle-doc").forEach((btn) => {
        btn.addEventListener("click", function () {
            let type = this.dataset.type;
            this.closest(".modal-body")
                .querySelectorAll(".doc-container")
                .forEach((c) => {
                    c.style.display =
                        c.dataset.type === type
                            ? c.style.display === "none"
                                ? "block"
                                : "none"
                            : "none";
                });
        });
    });

    // ------------------------------------------------------
    // TAMBAH DOKUMEN
    // ------------------------------------------------------
    document.querySelectorAll(".add-doc, .add-doc-btn").forEach((btn) => {
        btn.addEventListener("click", function () {
            let type = this.dataset.type;
            let container =
                this.closest(".doc-container").querySelector(".doc-list");
            container.appendChild(createNewDocRowHTML(type));
        });
    });

    // ------------------------------------------------------
    // TAMBAH LINK
    // ------------------------------------------------------
    document.querySelectorAll(".add-link, .add-link-btn").forEach((btn) => {
        btn.addEventListener("click", function () {
            let container =
                this.closest(".doc-container").querySelector(".link-list");
            container.appendChild(createLinkRowHTML());
        });
    });

    // ------------------------------------------------------
    // HAPUS BIDANG / DOKUMEN / LINK
    // ------------------------------------------------------
    document.addEventListener("click", function (e) {
        if (e.target.classList.contains("remove-bidang")) {
            e.target.parentElement.remove();
        }
        if (
            e.target.classList.contains("remove-doc") ||
            e.target.classList.contains("remove-doc-existing")
        ) {
            e.target.closest(".doc-row")?.remove();
        }
        if (e.target.classList.contains("remove-link")) {
            e.target.closest(".link-row")?.remove();
        }
    });

    // ------------------------------------------------------
    // PREVIEW FILE UPLOAD
    // ------------------------------------------------------
    document.addEventListener("change", function (e) {
        if (e.target.classList.contains("drive-input")) {
            let preview = e.target
                .closest(".drive-upload-wrapper")
                .querySelector(".drive-text");
            preview.textContent = e.target.files.length
                ? e.target.files[0].name
                : "Upload File";
        }
    });
});
