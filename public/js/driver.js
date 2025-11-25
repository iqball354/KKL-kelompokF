document.addEventListener("DOMContentLoaded", function () {
    // -------------------------
    // Helper HTML templates
    // -------------------------
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
        return `<div class="card mb-2 p-2 link-row">
            <div class="d-flex justify-content-between align-items-center">
                <input type="url" name="link[]" class="form-control me-2" placeholder="Masukkan URL">
                <button type="button" class="btn btn-danger btn-sm remove-link">Hapus</button>
            </div>
        </div>`;
    }

    // -------------------------
    // Tambahkan input Nama Dosen di modal
    // -------------------------
    function addNamaDosenInputManual(modalSelector, defaultValue = "") {
        const modalBody = document.querySelector(
            modalSelector + " .modal-body"
        );
        if (!modalBody) return;
        if (!modalBody.querySelector(".nama-dosen-input")) {
            const div = document.createElement("div");
            div.classList.add("mb-3");
            div.innerHTML = `
                <label>Nama Dosen</label>
                <input type="text" name="nama_dosen" class="form-control nama-dosen-input" value="${defaultValue}" placeholder="Ketik Nama Dosen">
            `;
            modalBody.insertBefore(div, modalBody.firstChild);
        }
    }

    // -------------------------
    // Gunakan input nama dosen di modal tambah dan edit
    // -------------------------
    addNamaDosenInputManual("#addExpertModal");

    document.querySelectorAll(".editExpertModal").forEach((modal) => {
        const id = modal.dataset.id;
        const defaultNama = modal.dataset.nama || "";
        addNamaDosenInputManual("#editExpertModal-" + id, defaultNama);
    });

    // -------------------------
    // Toggle doc types
    // -------------------------
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

    document.querySelectorAll(".toggle-doc-edit").forEach((btn) => {
        btn.addEventListener("click", function () {
            let type = this.dataset.type;
            let id = this.dataset.id;
            let container = document.getElementById(
                "edit-container-" + type + "-" + id
            );
            if (container)
                container.style.display =
                    container.style.display === "none" ? "block" : "none";
        });
    });

    // -------------------------
    // Add/remove bidang keahlian
    // -------------------------
    document
        .getElementById("add-bidang")
        ?.addEventListener("click", function () {
            let container = document.getElementById("bidang-container");
            let div = document.createElement("div");
            div.classList.add("input-group", "mb-2");
            div.innerHTML =
                '<input type="text" name="bidang_keahlian[]" class="form-control"><button type="button" class="btn btn-danger remove-bidang">X</button>';
            container.appendChild(div);
        });

    document.querySelectorAll(".add-bidang-btn").forEach((btn) => {
        btn.addEventListener("click", function () {
            let id = this.dataset.id;
            let container = document.getElementById(
                "edit-bidang-container-" + id
            );
            let div = document.createElement("div");
            div.classList.add("input-group", "mb-2");
            div.innerHTML =
                '<input type="text" name="bidang_keahlian[]" class="form-control"><button type="button" class="btn btn-danger remove-bidang">X</button>';
            container.appendChild(div);
        });
    });

    // -------------------------
    // Add/remove documents & links
    // -------------------------
    function addDoc(btn, type, targetSelector) {
        let target = targetSelector
            ? document.querySelector(targetSelector)
            : btn.closest(".doc-container");
        let wrapper = document.createElement("div");
        wrapper.innerHTML = createNewDocRowHTML(type);
        target.insertBefore(wrapper, btn);
    }

    document.querySelectorAll(".add-doc").forEach((btn) => {
        btn.addEventListener("click", function () {
            addDoc(btn, this.dataset.type);
        });
    });

    document.querySelectorAll(".add-doc-btn").forEach((btn) => {
        btn.addEventListener("click", function () {
            let type = this.dataset.type;
            let id = this.dataset.id;
            addDoc(
                btn,
                type,
                "#edit-container-" + type + "-" + id + " .doc-new-list"
            );
        });
    });

    function addLink(btn, targetSelector) {
        let container = targetSelector
            ? document.querySelector(targetSelector)
            : btn.closest(".doc-container").querySelector(".link-list");
        let wrapper = document.createElement("div");
        wrapper.innerHTML = createLinkRowHTML();
        container.appendChild(wrapper);
    }

    document.querySelectorAll(".add-link").forEach((btn) => {
        btn.addEventListener("click", function () {
            addLink(btn);
        });
    });

    document.querySelectorAll(".add-link-btn").forEach((btn) => {
        btn.addEventListener("click", function () {
            let id = this.dataset.id;
            addLink(btn, "#edit-container-link-" + id + " .link-list");
        });
    });

    // -------------------------
    // Remove buttons
    // -------------------------
    document.addEventListener("click", function (e) {
        if (e.target.classList.contains("remove-bidang"))
            e.target.parentElement.remove();
        if (
            e.target.classList.contains("remove-doc") ||
            e.target.classList.contains("remove-doc-existing")
        )
            e.target.closest(".doc-row")?.remove();
        if (e.target.classList.contains("remove-link"))
            e.target.closest(".link-row")?.remove();
    });

    // -------------------------
    // File preview
    // -------------------------
    document.addEventListener("change", function (e) {
        if (e.target.classList.contains("drive-input")) {
            let fileName = e.target.files.length
                ? e.target.files[0].name
                : "Tidak ada file";
            let preview = e.target
                .closest(".drive-upload-wrapper")
                .querySelector(".preview-file");
            if (preview) preview.textContent = fileName;
        }
    });
});
