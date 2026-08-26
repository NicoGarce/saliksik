$(function () {
    var state = {
        type: null,
        csvData: null,
        csvFileName: null,
        pdfFiles: [],
        assignments: {},
        processing: false
    };

    var typeNameMap = {
        thesis: 'Thesis',
        journal: 'Journal',
        infographic: 'Infographic',
        report: 'Report'
    };

    /* ===================== STEP NAVIGATION ===================== */

    function goToStep(step) {
        $('.bulk-step-content').removeClass('active');
        $('#bulk-step-' + step).addClass('active');

        $('.bulk-step').each(function () {
            var s = parseInt($(this).data('step'));
            $(this).removeClass('active done');
            if (s === step) $(this).addClass('active');
            else if (s < step) $(this).addClass('done');
        });

        $('.bulk-step-connector').each(function (i) {
            $(this).toggleClass('done', i < step - 1);
        });

        $('html, body').animate({ scrollTop: 0 }, 250);
    }

    /* ===================== STEP 1: TYPE SELECTION ===================== */

    $('.bulk-type-card').on('click', function () {
        $('.bulk-type-card').removeClass('selected');
        $(this).addClass('selected');
        state.type = $(this).data('type');
        $('#btn-step1-next').prop('disabled', false);
    });

    $('#btn-step1-next').on('click', function () {
        if (!state.type) return;
        $('#template-type-label').text(typeNameMap[state.type]);
        $('#btn-download-template').attr('href', '../src/process/bulk-download-template.php?type=' + state.type);
        resetStep2();
        goToStep(2);
    });

    /* ===================== STEP 2: CSV UPLOAD ===================== */

    function resetStep2() {
        state.csvData = null;
        state.csvFileName = null;
        $('#csv-file-info').hide();
        $('#csv-preview-container').hide();
        $('#btn-step2-next').prop('disabled', true);
        $('#csv-file-input').val('');
    }

    var csvDropzone = $('#csv-dropzone');
    var csvInput = $('#csv-file-input');

    csvDropzone.on('click', function () { csvInput.trigger('click'); });

    csvDropzone.on('dragover', function (e) {
        e.preventDefault();
        $(this).addClass('dragover');
    });
    csvDropzone.on('dragleave drop', function () { $(this).removeClass('dragover'); });

    csvDropzone.on('drop', function (e) {
        e.preventDefault();
        var files = e.originalEvent.dataTransfer.files;
        if (files.length) handleCSVFile(files[0]);
    });

    csvInput.on('change', function () {
        if (this.files.length) handleCSVFile(this.files[0]);
    });

    function handleCSVFile(file) {
        if (!file.name.toLowerCase().endsWith('.csv')) {
            alert('Please select a CSV file.');
            return;
        }
        state.csvFileName = file.name;
        $('#csv-file-name').text(file.name);
        $('#csv-file-size').text(formatSize(file.size));
        $('#csv-file-info').show();

        var reader = new FileReader();
        reader.onload = function (e) {
            parseCSV(e.target.result);
        };
        reader.readAsText(file);
    }

    $('#csv-file-remove').on('click', function () {
        resetStep2();
    });

    function parseCSV(text) {
        var lines = text.split(/\r?\n/).filter(function (l) { return l.trim() !== ''; });
        if (lines.length < 2) {
            alert('CSV must have a header row and at least one data row.');
            return;
        }

        var headers = parseCSVLine(lines[0]);
        var rows = [];
        var errors = [];

        for (var i = 1; i < lines.length; i++) {
            var vals = parseCSVLine(lines[i]);
            var row = {};
            var rowErrors = [];

            headers.forEach(function (h, idx) {
                row[h.trim()] = (vals[idx] || '').trim();
            });

            if (!row.filename) rowErrors.push('Missing filename');
            if (!row.research_title && !row.journal_title && !row.infographic_title && !row.report_title) {
                rowErrors.push('Missing title');
            }

            rows.push({ data: row, errors: rowErrors, rowNum: i });
        }

        state.csvData = { headers: headers, rows: rows };

        renderPreview(headers, rows);
        $('#csv-row-count').text(rows.length);

        var errCount = rows.filter(function (r) { return r.errors.length > 0; }).length;
        if (errCount > 0) {
            $('#csv-error-count').show().find('span').text(errCount);
        } else {
            $('#csv-error-count').hide();
        }

        $('#csv-preview-container').show();
        $('#btn-step2-next').prop('disabled', rows.length === 0);
    }

    function parseCSVLine(line) {
        var result = [];
        var current = '';
        var inQuotes = false;

        for (var i = 0; i < line.length; i++) {
            var ch = line[i];
            if (inQuotes) {
                if (ch === '"') {
                    if (i + 1 < line.length && line[i + 1] === '"') {
                        current += '"';
                        i++;
                    } else {
                        inQuotes = false;
                    }
                } else {
                    current += ch;
                }
            } else {
                if (ch === '"') {
                    inQuotes = true;
                } else if (ch === ',') {
                    result.push(current);
                    current = '';
                } else {
                    current += ch;
                }
            }
        }
        result.push(current);
        return result;
    }

    function renderPreview(headers, rows) {
        var showHeaders = headers.slice(0, 8);
        var html = '<table class="table"><thead><tr>';
        html += '<th>#</th>';
        showHeaders.forEach(function (h) {
            html += '<th>' + escHtml(h) + '</th>';
        });
        if (headers.length > 8) html += '<th>...+' + (headers.length - 8) + ' more</th>';
        html += '</tr></thead><tbody>';

        rows.forEach(function (r) {
            var hasErr = r.errors.length > 0;
            html += '<tr class="' + (hasErr ? 'row-error' : 'row-ok') + '">';
            html += '<td><span class="bulk-row-badge ' + (hasErr ? 'err' : 'ok') + '">' + r.rowNum + '</span></td>';
            showHeaders.forEach(function (h) {
                var val = r.data[h] || '';
                html += '<td title="' + escHtml(val) + '">' + escHtml(val) + '</td>';
            });
            if (headers.length > 8) html += '<td></td>';
            html += '</tr>';
        });

        html += '</tbody></table>';
        $('#csv-preview-table-wrap').html(html);
    }

    $('#btn-step2-back').on('click', function () { goToStep(1); });
    $('#btn-step2-next').on('click', function () {
        resetStep3();
        goToStep(3);
    });

    /* ===================== STEP 3: PDF UPLOAD ===================== */

    function resetStep3() {
        state.pdfFiles = [];
        renderPDFList();
    }

    var pdfDropzone = $('#pdf-dropzone');
    var pdfInput = $('#pdf-file-input');

    pdfDropzone.on('click', function () { pdfInput.trigger('click'); });

    pdfDropzone.on('dragover', function (e) {
        e.preventDefault();
        $(this).addClass('dragover');
    });
    pdfDropzone.on('dragleave drop', function () { $(this).removeClass('dragover'); });

    pdfDropzone.on('drop', function (e) {
        e.preventDefault();
        var files = e.originalEvent.dataTransfer.files;
        addPDFFiles(files);
    });

    pdfInput.on('change', function () {
        addPDFFiles(this.files);
        $(this).val('');
    });

    function addPDFFiles(fileList) {
        for (var i = 0; i < fileList.length; i++) {
            var f = fileList[i];
            if (!f.name.toLowerCase().endsWith('.pdf')) continue;
            var duplicate = state.pdfFiles.some(function (p) { return p.name === f.name; });
            if (!duplicate) state.pdfFiles.push(f);
        }
        renderPDFList();
    }

    function renderPDFList() {
        var list = $('#pdf-file-list');
        list.empty();
        $('#pdf-count').text(state.pdfFiles.length);
        $('#btn-clear-pdfs').toggle(state.pdfFiles.length > 0);

        state.pdfFiles.forEach(function (f, i) {
            list.append(
                '<li class="bulk-file-item">' +
                '<i class="fas fa-file-pdf"></i>' +
                '<span class="file-name">' + escHtml(f.name) + '</span>' +
                '<span class="file-size">' + formatSize(f.size) + '</span>' +
                '<button type="button" class="file-remove" data-idx="' + i + '" title="Remove"><i class="fas fa-times"></i></button>' +
                '</li>'
            );
        });
    }

    $(document).on('click', '#pdf-file-list .file-remove', function () {
        var idx = parseInt($(this).data('idx'));
        state.pdfFiles.splice(idx, 1);
        renderPDFList();
    });

    $('#btn-clear-pdfs').on('click', function () {
        state.pdfFiles = [];
        renderPDFList();
    });

    $('#btn-step3-back').on('click', function () { goToStep(2); });
    $('#btn-step3-next').on('click', function () {
        buildAssignments();
        goToStep(4);
    });

    /* ===================== STEP 4: ASSIGNMENTS ===================== */

    function buildAssignments() {
        var rows = state.csvData.rows;
        var pdfNames = state.pdfFiles.map(function (f) { return f.name; });
        var container = $('#assign-rows-container');
        container.empty();

        var matched = 0, unmatched = 0;

        rows.forEach(function (r, i) {
            var csvFilename = (r.data.filename || '').trim();
            var autoMatch = '';
            if (csvFilename && pdfNames.indexOf(csvFilename) !== -1) {
                autoMatch = csvFilename;
                matched++;
            } else {
                unmatched++;
            }

            state.assignments[i] = autoMatch;

            var title = r.data.research_title || r.data.journal_title || r.data.infographic_title || r.data.report_title || 'Untitled';
            var selectHtml = '<select class="form-select form-select-sm admin-select assign-select" data-row="' + i + '">';
            selectHtml += '<option value="">-- No PDF --</option>';
            pdfNames.forEach(function (pn) {
                var sel = (pn === autoMatch) ? ' selected' : '';
                selectHtml += '<option value="' + escHtml(pn) + '"' + sel + '>' + escHtml(pn) + '</option>';
            });
            selectHtml += '</select>';

            var statusIcon = autoMatch
                ? '<i class="fas fa-check-circle assign-status matched"></i>'
                : '<i class="fas fa-circle assign-status unmatched" style="font-size:.6rem;"></i>';

            container.append(
                '<div class="bulk-assign-row">' +
                '<span class="row-num">Row ' + (i + 1) + '</span>' +
                '<span class="row-title" title="' + escHtml(title) + '">' + escHtml(title) + '</span>' +
                selectHtml +
                statusIcon +
                '</div>'
            );
        });

        $('#summary-total').text(rows.length);
        $('#summary-matched').text(matched);
        $('#summary-unmatched').text(unmatched);
    }

    $(document).on('change', '.assign-select', function () {
        var row = parseInt($(this).data('row'));
        var val = $(this).val();
        state.assignments[row] = val;

        var $row = $(this).closest('.bulk-assign-row');
        var $status = $row.find('.assign-status');
        $status.removeClass('matched unmatched');
        if (val) {
            $status.addClass('matched').html('<i class="fas fa-check-circle"></i>');
        } else {
            $status.addClass('unmatched').html('<i class="fas fa-circle" style="font-size:.6rem;"></i>');
        }

        var matched = Object.values(state.assignments).filter(function (v) { return v !== ''; }).length;
        var unmatched = Object.values(state.assignments).filter(function (v) { return v === ''; }).length;
        $('#summary-matched').text(matched);
        $('#summary-unmatched').text(unmatched);
    });

    $('#btn-step4-back').on('click', function () { goToStep(3); });
    $('#btn-step4-next').on('click', function () {
        if (state.processing) return;
        startProcessing();
    });

    /* ===================== STEP 5: PROCESSING ===================== */

    function startProcessing() {
        state.processing = true;
        goToStep(5);
        $('#processing-active').show();
        $('#processing-done').hide();
        $('#process-results').empty();
        $('#process-progress-bar').css('width', '0%');
        $('#process-progress-text').text('Preparing upload...');

        var rows = state.csvData.rows;
        var totalRows = rows.length;
        var processed = 0;
        var successCount = 0;
        var errorCount = 0;
        var skipCount = 0;

        function processNext() {
            if (processed >= totalRows) {
                finishProcessing(successCount, errorCount, skipCount);
                return;
            }

            var row = rows[processed];
            var pdfName = state.assignments[processed] || '';

            if (!pdfName) {
                skipCount++;
                addResultRow(processed + 1, 'skipped', 'No PDF assigned — skipped');
                processed++;
                updateProgress(processed, totalRows);
                processNext();
                return;
            }

            var matchedFile = null;
            for (var i = 0; i < state.pdfFiles.length; i++) {
                if (state.pdfFiles[i].name === pdfName) {
                    matchedFile = state.pdfFiles[i];
                    break;
                }
            }

            if (!matchedFile) {
                skipCount++;
                addResultRow(processed + 1, 'skipped', 'PDF file not found: ' + pdfName);
                processed++;
                updateProgress(processed, totalRows);
                processNext();
                return;
            }

            var formData = new FormData();
            formData.append('type', state.type);
            formData.append('csv_row', JSON.stringify(row.data));
            formData.append('pdf_file', matchedFile, matchedFile.name);

            $.ajax({
                url: '../src/process/bulk-upload-process.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function (res) {
                    if (res.response === 'success') {
                        successCount++;
                        addResultRow(processed + 1, 'success', res.title || 'Uploaded successfully');
                    } else {
                        errorCount++;
                        addResultRow(processed + 1, 'error', res.errorText || res.response || 'Unknown error');
                    }
                },
                error: function () {
                    errorCount++;
                    addResultRow(processed + 1, 'error', 'Request failed');
                },
                complete: function () {
                    processed++;
                    updateProgress(processed, totalRows);
                    processNext();
                }
            });
        }

        processNext();
    }

    function updateProgress(current, total) {
        var pct = Math.round((current / total) * 100);
        $('#process-progress-bar').css('width', pct + '%');
        $('#process-progress-text').text('Processing row ' + current + ' of ' + total + ' (' + pct + '%)');
    }

    function addResultRow(rowNum, type, msg) {
        var icon = type === 'success'
            ? '<i class="fas fa-check-circle"></i>'
            : type === 'error'
                ? '<i class="fas fa-times-circle"></i>'
                : '<i class="fas fa-forward"></i>';
        var cls = type === 'success' ? 'success' : (type === 'error' ? 'error' : 'success');
        $('#process-results').append(
            '<div class="bulk-result-item ' + cls + '">' +
            '<span class="result-row">Row ' + rowNum + '</span>' +
            icon +
            '<span class="result-msg">' + escHtml(msg) + '</span>' +
            '</div>'
        );
    }

    function finishProcessing(success, errors, skipped) {
        state.processing = false;
        $('#processing-active').hide();
        $('#processing-done').show();
        var parts = [];
        if (success > 0) parts.push('<strong>' + success + '</strong> uploaded');
        if (errors > 0) parts.push('<strong>' + errors + '</strong> failed');
        if (skipped > 0) parts.push('<strong>' + skipped + '</strong> skipped');
        $('#done-summary-text').html(parts.join(' &middot; ') + ' out of ' + state.csvData.rows.length + ' total rows.');
    }

    $('#btn-start-over').on('click', function () {
        state.type = null;
        state.csvData = null;
        state.csvFileName = null;
        state.pdfFiles = [];
        state.assignments = {};
        state.processing = false;

        $('.bulk-type-card').removeClass('selected');
        $('#btn-step1-next').prop('disabled', true);
        resetStep2();
        resetStep3();
        goToStep(1);
    });

    /* ===================== HELPERS ===================== */

    function formatSize(bytes) {
        if (bytes < 1024) return bytes + ' B';
        if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
        return (bytes / 1048576).toFixed(1) + ' MB';
    }

    function escHtml(str) {
        var div = document.createElement('div');
        div.appendChild(document.createTextNode(str));
        return div.innerHTML;
    }
});
