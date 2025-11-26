document.addEventListener("DOMContentLoaded", function () {
    // ------------------------------------------------------
    // TEMPLATE BARU (HTML Row Dokumen & Link)
    // ------------------------------------------------------
    function createNewDocRowHTML(type) {
        return `
        <div class="doc-row new-doc-row" data-type-row="${type}">
            <div class="drive-upload-wrapper">
                <label class="drive-upload-box">
                    <i class="fas fa-file-alt"></i>
                    <span class="drive-text">Upload File</span>
                    <input type="file" name="dokumen_${type}[]" class="file-hidden drive-input">
                </label>
                <div class="preview-file"></div>
            </div>

            <div class="row mb-2">
                <div class="col-6">
                    <input type="text" name="deskripsi_${type}[]" class="form-control" placeholder="Deskripsi">
                </div>
                <div class="col-4">
                    <input type="number" name="tahun_${type}[]" class="form-control" placeholder="Tahun" min="1900" max="2100">
                </div>
                <div class="col-2 d-flex align-items-start">
                    <button type="button" class="btn btn-danger btn-sm remove-doc ms-auto">Hapus</button>
                </div>
            </div>
        </div>`;
    }

    function createLinkRowHTML() {
        return `
        <div class="card mb-2 p-2 link-row">
            <div class="d-flex justify-content-between align-items-center">
                <input type="url" name="link[]" class="form-control me-2" placeholder="Masukkan URL">
                <button type="button" class="btn btn-danger btn-sm remove-link">Hapus</button>
            </div>
        </div>`;
    }

    // ------------------------------------------------------
    // TOGGLE CONTAINER UNTUK TAMBAH
    // ------------------------------------------------------
    document.querySelectorAll(".toggle-doc").forEach((btn) => {
        btn.addEventListener("click", function () {
            let type = this.dataset.type;

            document.querySelectorAll(".doc-container").forEach((c) => {
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
    // TOGGLE CONTAINER UNTUK EDIT
    // ------------------------------------------------------
    document.querySelectorAll(".toggle-doc-edit").forEach((btn) => {
        btn.addEventListener("click", function () {
            let type = this.dataset.type;
            let id = this.dataset.id;

            let cont = document.getElementById(`edit-container-${type}-${id}`);
            if (cont)
                cont.style.display =
                    cont.style.display === "none" ? "block" : "none";
        });
    });

    // ------------------------------------------------------
    // TAMBAH / REMOVE BIDANG KEAHLIAN
    // ------------------------------------------------------
    document
        .getElementById("add-bidang")
        ?.addEventListener("click", function () {
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
            let container = document.getElementById(
                `edit-bidang-container-${id}`
            );

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
    // ADD DOC
    // ------------------------------------------------------
    function addDoc(btn, type, customTarget = null) {
        let target = customTarget
            ? document.querySelector(customTarget)
            : btn.closest(".doc-container");

        let wrap = document.createElement("div");
        wrap.innerHTML = createNewDocRowHTML(type);
        target.insertBefore(wrap, btn);
    }

    document.querySelectorAll(".add-doc").forEach((btn) => {
        btn.addEventListener("click", function () {
            addDoc(btn, this.dataset.type);
        });
    });

    document.querySelectorAll(".add-doc-btn").forEach((btn) => {
        btn.addEventListener("click", function () {
            addDoc(
                btn,
                this.dataset.type,
                `#edit-container-${this.dataset.type}-${this.dataset.id} .doc-new-list`
            );
        });
    });

    // ------------------------------------------------------
    // ADD LINK
    // ------------------------------------------------------
    function addLink(btn, target = null) {
        let container = target
            ? document.querySelector(target)
            : btn.closest(".doc-container").querySelector(".link-list");

        let wrap = document.createElement("div");
        wrap.innerHTML = createLinkRowHTML();
        container.appendChild(wrap);
    }

    document.querySelectorAll(".add-link").forEach((btn) => {
        btn.addEventListener("click", function () {
            addLink(btn);
        });
    });

    document.querySelectorAll(".add-link-btn").forEach((btn) => {
        btn.addEventListener("click", function () {
            addLink(btn, `#edit-container-link-${this.dataset.id} .link-list`);
        });
    });

    // ------------------------------------------------------
    // REMOVE + KONFIRMASI POPUP
    // ------------------------------------------------------
    document.addEventListener("click", function (e) {
        const target = e.target;

        if (
            target.classList.contains("remove-bidang") ||
            target.classList.contains("remove-doc") ||
            target.classList.contains("remove-doc-existing") ||
            target.classList.contains("remove-link")
        ) {
            e.preventDefault();

            if (!confirm("Yakin ingin menghapus item ini?")) return;

            if (target.classList.contains("remove-bidang"))
                target.parentElement.remove();

            if (
                target.classList.contains("remove-doc") ||
                target.classList.contains("remove-doc-existing")
            )
                target.closest(".doc-row")?.remove();

            if (target.classList.contains("remove-link"))
                target.closest(".link-row")?.remove();
        }
    });

    // ------------------------------------------------------
    // FILE PREVIEW
    // ------------------------------------------------------
    document.addEventListener("change", function (e) {
        if (e.target.classList.contains("drive-input")) {
            let name = e.target.files.length
                ? e.target.files[0].name
                : "Tidak ada file";
            let preview = e.target
                .closest(".drive-upload-wrapper")
                .querySelector(".preview-file");
            if (preview) preview.textContent = name;
        }
    });
});
