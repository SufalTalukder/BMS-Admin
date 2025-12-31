<footer id="footer" class="footer">
  <div class="copyright">
    &copy; <?php echo date('Y'); ?> <strong><span><?= PROJECT_NAME; ?> </span></strong>. All Rights Reserved
  </div>
</footer>

<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

<script src="https://code.jquery.com/jquery-3.6.0.min.js<?= '?v=' . v; ?>"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js<?= '?v=' . v; ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest<?= '?v=' . v; ?>" defer></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/umd/export.js<?= '?v=' . v; ?>" defer></script>
<script src="<?= base_url('assets/js/main.js') ?><?= '?v=' . v; ?>"></script>
<script src="<?= base_url('assets/vendor/apexcharts/apexcharts.min.js') ?><?= '?v=' . v; ?>"></script>
<script src="<?= base_url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') ?><?= '?v=' . v; ?>"></script>
<script src="<?= base_url('assets/vendor/chart.js/chart.umd.js') ?><?= '?v=' . v; ?>"></script>
<script src="<?= base_url('assets/vendor/echarts/echarts.min.js') ?><?= '?v=' . v; ?>"></script>
<script src="<?= base_url('assets/vendor/quill/quill.js') ?><?= '?v=' . v; ?>"></script>
<script src="<?= base_url('assets/vendor/tinymce/tinymce.min.js') ?><?= '?v=' . v; ?>"></script>
<script src="<?= base_url('assets/vendor/php-email-form/validate.js') ?><?= '?v=' . v; ?>"></script>

<script>
  function getExportFileName(ext) {
    const d = new Date();

    const pad = n => n.toString().padStart(2, "0");

    let day = pad(d.getDate());
    let month = pad(d.getMonth() + 1);
    let year = d.getFullYear().toString().slice(-2);

    let hours = d.getHours();
    let minutes = pad(d.getMinutes());
    let ampm = hours >= 12 ? "PM" : "AM";

    hours = hours % 12 || 12; // 12-hour format

    return `export-data-${day}-${month}-${year}-${hours}-${minutes}${ampm}.${ext}`;
  }

  function removeLoaderIfExists() {
    const loader = document.getElementById("loader-row");
    if (loader) loader.remove();
  }

  document.getElementById("export-csv").onclick = () => {
    removeLoaderIfExists();
    simpleDatatables.exportCSV(dataTable, {
      download: true,
      filename: getExportFileName("csv")
    });
  };

  document.getElementById("export-txt").onclick = () => {
    removeLoaderIfExists();
    simpleDatatables.exportTXT(dataTable, {
      download: true,
      filename: getExportFileName("txt")
    });
  };

  document.getElementById("export-sql").onclick = () => {
    removeLoaderIfExists();
    exportSQLCustom();
  };

  function exportSQLCustom() {
    const table = document.getElementById("demo-table");
    const rows = table.querySelectorAll("tbody tr");
    const headers = [...table.querySelectorAll("thead th")]
      .map(th => th.innerText.trim())
      .map(h => h.replace(/[^a-zA-Z0-9_]/g, "_")); // sanitize

    let sql = `INSERT INTO activity_log (${headers.join(", ")}) VALUES\n`;

    const values = [];

    rows.forEach(row => {
      const cols = [...row.querySelectorAll("td")];
      if (!cols.length) return;

      const rowValues = cols.map(td =>
        `'${td.innerText.replace(/'/g, "''").trim()}'`
      );

      values.push(`(${rowValues.join(", ")})`);
    });

    sql += values.join(",\n") + ";";

    downloadFile(sql, getExportFileName("sql"), "text/sql");
  }

  document.getElementById("export-excel").onclick = () => {
    removeLoaderIfExists();
    exportHTML(getExportFileName("xls"), "application/vnd.ms-excel");
  };

  document.getElementById("export-doc").onclick = () => {
    removeLoaderIfExists();
    exportHTML(getExportFileName("doc"), "application/msword");
  };

  document.getElementById("export-pdf").onclick = () => {
    removeLoaderIfExists();
    exportPDF(getExportFileName("pdf"));
  };

  function exportHTML(filename, mime) {
    const html = `
      <html>
        <head><meta charset="UTF-8"></head>
        <body>${document.getElementById("demo-table").outerHTML}</body>
      </html>`;
    downloadFile(html, filename, mime);
  }

  function exportPDF(filename) {
    const win = window.open("");
    win.document.write(`
      <html>
        <head>
          <title>${filename}</title>
          <style>
            table { width:100%; border-collapse:collapse }
            th, td { border:1px solid #000; padding:5px }
          </style>
        </head>
        <body>${document.getElementById("demo-table").outerHTML}</body>
      </html>
    `);
    win.document.close();
    win.print();
  }

  function downloadFile(content, filename, mime) {
    const a = document.createElement("a");
    a.href = URL.createObjectURL(new Blob([content], {
      type: mime
    }));
    a.download = filename;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
  }
</script>

</body>

</html>