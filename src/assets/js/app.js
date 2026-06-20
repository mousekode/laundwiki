// UAS Laundry System - Client Side Logic

document.addEventListener("DOMContentLoaded", function () {
  // 1. Automatic Pricing and Date Calculation for Order Form
  const orderForm = document.getElementById("orderForm");
  if (orderForm) {
    const beratInput = document.getElementById("berat");
    const layananSelect = document.getElementById("id_layanan");
    const paketSelect = document.getElementById("id_paket");
    const tglMasukInput = document.getElementById("tanggal_masuk");

    const priceDisplay = document.getElementById("priceDisplay");
    const dateDisplay = document.getElementById("dateDisplay");
    const detailDisplay = document.getElementById("calcDetails");

    function formatRupiah(number) {
      return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
      }).format(number);
    }

    function calculateTotal() {
      const berat = parseFloat(beratInput.value) || 0;
      const selectedLayanan =
        layananSelect.options[layananSelect.selectedIndex];
      const selectedPaket = paketSelect.options[paketSelect.selectedIndex];
      const tglMasukStr = tglMasukInput.value;

      if (!selectedLayanan.value || !selectedPaket.value || berat <= 0) {
        priceDisplay.textContent = "Rp 0";
        dateDisplay.textContent = "-";
        detailDisplay.textContent =
          "Masukkan berat, pilih layanan, dan pilih paket.";
        return;
      }

      // Get prices from dataset
      const hargaPerKg =
        parseFloat(selectedLayanan.getAttribute("data-harga")) || 0;
      const biayaTambahan =
        parseFloat(selectedPaket.getAttribute("data-tambahan")) || 0;
      const durasiHari =
        parseInt(selectedPaket.getAttribute("data-durasi")) || 0;

      // Calculate total price
      const totalHarga = hargaPerKg * berat + biayaTambahan;

      // Update price display
      priceDisplay.textContent = formatRupiah(totalHarga);

      // Format details message
      detailDisplay.textContent = `(${formatRupiah(hargaPerKg)}/kg x ${berat} kg) + biaya paket ${formatRupiah(biayaTambahan)}`;

      // Calculate estimated completion date
      if (tglMasukStr) {
        const dateMasuk = new Date(tglMasukStr);
        dateMasuk.setDate(dateMasuk.getDate() + durasiHari);

        const options = {
          weekday: "long",
          year: "numeric",
          month: "long",
          day: "numeric",
        };
        dateDisplay.textContent = dateMasuk.toLocaleDateString(
          "id-ID",
          options,
        );
      } else {
        dateDisplay.textContent = "Pilih tanggal masuk";
      }
    }

    // Set default date to today if empty
    if (!tglMasukInput.value) {
      const today = new Date().toISOString().split("T")[0];
      tglMasukInput.value = today;
    }

    // Add event listeners
    beratInput.addEventListener("input", calculateTotal);
    layananSelect.addEventListener("change", calculateTotal);
    paketSelect.addEventListener("change", calculateTotal);
    tglMasukInput.addEventListener("change", calculateTotal);

    // Initial run
    calculateTotal();
  }

  // 2. Client-Side Form Validation (Fallback for HTML5 validation)
  const forms = document.querySelectorAll(".validated-form");
  forms.forEach((form) => {
    form.addEventListener("submit", function (event) {
      let isValid = true;

      // Remove previous error markings
      form
        .querySelectorAll(".is-invalid")
        .forEach((el) => el.classList.remove("is-invalid"));
      form.querySelectorAll(".invalid-feedback").forEach((el) => el.remove());

      // Validate Pelanggan inputs
      const nama = form.querySelector('[name="nama"]');
      if (nama && nama.value.trim() === "") {
        markInvalid(nama, "Nama lengkap wajib diisi");
        isValid = false;
      }

      const telepon = form.querySelector('[name="telepon"]');
      if (telepon) {
        const phoneVal = telepon.value.trim();
        const phoneRegex = /^[0-9]{8,15}$/;
        if (phoneVal === "") {
          markInvalid(telepon, "Nomor telepon wajib diisi");
          isValid = false;
        } else if (!phoneRegex.test(phoneVal)) {
          markInvalid(telepon, "Nomor telepon harus berupa angka (8-15 digit)");
          isValid = false;
        }
      }

      const alamat = form.querySelector('[name="alamat"]');
      if (alamat && alamat.value.trim() === "") {
        markInvalid(alamat, "Alamat wajib diisi");
        isValid = false;
      }

      // Validate Layanan/Paket inputs
      const namaLayanan = form.querySelector('[name="nama_layanan"]');
      if (namaLayanan && namaLayanan.value.trim() === "") {
        markInvalid(namaLayanan, "Nama layanan wajib diisi");
        isValid = false;
      }

      const harga = form.querySelector('[name="harga_per_kg"]');
      if (harga && (harga.value === "" || parseFloat(harga.value) < 0)) {
        markInvalid(harga, "Harga per kg harus diisi dengan angka positif");
        isValid = false;
      }

      const namaPaket = form.querySelector('[name="nama_paket"]');
      if (namaPaket && namaPaket.value.trim() === "") {
        markInvalid(namaPaket, "Nama paket wajib diisi");
        isValid = false;
      }

      const tambahan = form.querySelector('[name="biaya_tambahan"]');
      if (
        tambahan &&
        (tambahan.value === "" || parseFloat(tambahan.value) < 0)
      ) {
        markInvalid(
          tambahan,
          "Biaya tambahan harus diisi dengan angka positif",
        );
        isValid = false;
      }

      const durasi = form.querySelector('[name="durasi_hari"]');
      if (durasi && (durasi.value === "" || parseInt(durasi.value) < 0)) {
        markInvalid(durasi, "Durasi hari wajib diisi");
        isValid = false;
      }

      // Validate Transaksi inputs
      const pelangganSelect = form.querySelector('[name="id_pelanggan"]');
      if (pelangganSelect && pelangganSelect.value === "") {
        markInvalid(pelangganSelect, "Silakan pilih pelanggan");
        isValid = false;
      }

      const layananSel = form.querySelector('[name="id_layanan"]');
      if (layananSel && layananSel.value === "") {
        markInvalid(layananSel, "Silakan pilih layanan");
        isValid = false;
      }

      const paketSel = form.querySelector('[name="id_paket"]');
      if (paketSel && paketSel.value === "") {
        markInvalid(paketSel, "Silakan pilih paket");
        isValid = false;
      }

      const berat = form.querySelector('[name="berat"]');
      if (berat && (berat.value === "" || parseFloat(berat.value) <= 0)) {
        markInvalid(berat, "Berat pakaian harus lebih besar dari 0");
        isValid = false;
      }

      if (!isValid) {
        event.preventDefault();
        event.stopPropagation();
      }
    });
  });

  function markInvalid(element, message) {
    element.classList.add("is-invalid");
    const feedback = document.createElement("div");
    feedback.className = "invalid-feedback";
    feedback.innerText = message;
    element.parentNode.appendChild(feedback);
  }

  // 3. Client-Side Live Search / Table Filter for Instant UX
  const searchBar = document.getElementById("searchBar");
  const filterTable = document.querySelector(".custom-table");
  if (searchBar && filterTable) {
    searchBar.addEventListener("input", function () {
      const query = searchBar.value.toLowerCase().trim();
      const rows = filterTable.querySelectorAll("tbody tr");
      let foundCount = 0;

      rows.forEach((row) => {
        // If it's a "no items found" row, skip
        if (row.classList.contains("no-rows")) {
          row.remove();
          return;
        }

        const text = row.textContent.toLowerCase();
        if (text.includes(query)) {
          row.style.display = "";
          foundCount++;
        } else {
          row.style.display = "none";
        }
      });

      // Remove existing empty state row
      const existingEmptyRow = filterTable.querySelector(".empty-filter-row");
      if (existingEmptyRow) {
        existingEmptyRow.remove();
      }

      // Show empty text if no matching records
      if (foundCount === 0 && rows.length > 0) {
        const tbody = filterTable.querySelector("tbody");
        const emptyRow = document.createElement("tr");
        emptyRow.className = "empty-filter-row";
        const colCount = filterTable.querySelectorAll("thead th").length;
        emptyRow.innerHTML = `<td colspan="${colCount}" class="text-center empty-state" style="text-align: center; color: var(--text-muted); padding: 2rem;">Tidak ada hasil pencarian yang cocok untuk "${searchBar.value}"</td>`;
        tbody.appendChild(emptyRow);
      }
    });
  }
});
