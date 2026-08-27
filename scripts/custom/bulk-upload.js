$(function () {
    var state = {
        type: null,
        csvData: null,
        csvFileName: null,
        primaryFiles: [],
        secondaryFiles: [],
        assignments: {},
        processing: false
    };

    var typeNameMap = {
        thesis: 'Thesis / Dissertation',
        journal: 'Research Journal',
        infographic: 'Infographics',
        report: 'Reports'
    };

    var typeConfig = {
        thesis: {
            hasSecondary: true,
            secondaryLabel: 'Questionnaires',
            secondaryDesc: 'Upload the questionnaire PDFs for each submission (optional)',
            primaryAccept: '.pdf',
            secondaryAccept: '.pdf',
            primaryIcon: 'fa-file-pdf',
            primaryIconColor: '#dc2626',
            secondaryIcon: 'fa-file-alt',
            secondaryIconColor: '#6366f1',
            primaryTitle: 'Research Papers',
            primaryDesc: 'Upload the main research paper PDFs in the same order as your CSV rows',
            primarySuffix: '-Thesis.pdf',
            secondarySuffix: '-Questionnaire.pdf'
        },
        journal: {
            hasSecondary: true,
            secondaryLabel: 'Cover Images',
            secondaryDesc: 'Upload the front cover images (PNG/JPG) in the same order as the journal files',
            primaryAccept: '.pdf',
            secondaryAccept: '.png,.jpg,.jpeg',
            primaryIcon: 'fa-file-pdf',
            primaryIconColor: '#dc2626',
            secondaryIcon: 'fa-file-image-o',
            secondaryIconColor: '#2563eb',
            primaryTitle: 'Journal Files',
            primaryDesc: 'Upload the journal PDFs in the same order as your CSV rows',
            primarySuffix: '-Journal.pdf',
            secondarySuffix: '-Cover'
        },
        infographic: {
            hasSecondary: false,
            primaryAccept: '.pdf',
            primaryIcon: 'fa-file-pdf',
            primaryIconColor: '#dc2626',
            primaryTitle: 'Infographic Files',
            primaryDesc: 'Upload the infographic PDFs in the same order as your CSV rows',
            primarySuffix: '-Infographic.pdf'
        },
        report: {
            hasSecondary: true,
            secondaryLabel: 'Cover Images',
            secondaryDesc: 'Upload the front cover images (PNG/JPG) in the same order as the report files',
            primaryAccept: '.pdf',
            secondaryAccept: '.png,.jpg,.jpeg',
            primaryIcon: 'fa-file-pdf',
            primaryIconColor: '#dc2626',
            secondaryIcon: 'fa-file-image-o',
            secondaryIconColor: '#2563eb',
            primaryTitle: 'Report Files',
            primaryDesc: 'Upload the report PDFs in the same order as your CSV rows',
            primarySuffix: '-Report.pdf',
            secondarySuffix: '-Cover'
        }
    };

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

    function slugify(text) {
        if (!text || text === 'Untitled') return 'Untitled';
        return text
            .toLowerCase()
            .replace(/[^\w\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .replace(/^-|-$/g, '')
            .substring(0, 80);
    }

    function autoGenerateFilename(title, suffix, rowIndex) {
        var slug = slugify(title);
        var label = slug + '-Row' + (rowIndex + 1);
        if (suffix.charAt(0) === '.') {
            return label + suffix;
        }
        return label + suffix;
    }

    function getSecondaryExtFromFilename(originalName) {
        var parts = originalName.split('.');
        if (parts.length > 1) return '.' + parts.pop().toLowerCase();
        return '.png';
    }

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
            salikAlert('#csv-alert', 'danger', '<strong>Invalid file type!</strong> Please select a CSV file.');
            return;
        }
        salikAlert('#csv-alert', null, '');
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
            salikAlert('#csv-alert', 'danger', '<strong>Invalid CSV!</strong> The file must have a header row and at least one data row.');
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
            salikAlert('#csv-alert', 'warning',
                '<strong>CSV loaded with warnings.</strong> ' + rows.length + ' rows found, but <strong>' + errCount +
                '</strong> row' + (errCount === 1 ? '' : 's') + ' missing a title. Fix them or those rows will be skipped.');
        } else {
            $('#csv-error-count').hide();
            salikAlert('#csv-alert', 'success', '<strong>CSV loaded successfully!</strong> ' + rows.length + ' row' + (rows.length === 1 ? '' : 's') + ' ready. Now upload your files in step 3.');
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
        buildStep3();
        goToStep(3);
    });

    /* ===================== STEP 3: FILE UPLOAD ===================== */

    function buildStep3() {
        var cfg = typeConfig[state.type];
        var container = $('#step3-dropzones');
        container.empty();

        var primaryHtml = '<div class="bulk-dropzone-section">' +
            '<div class="bulk-dropzone-header">' +
            '<i class="fas ' + cfg.primaryIcon + '" style="color: ' + cfg.primaryIconColor + ';"></i>' +
            '<div class="bulk-dropzone-header-text">' +
            '<div class="bulk-dropzone-title">' + escHtml(cfg.primaryTitle) + '</div>' +
            '<p class="bulk-dropzone-desc">' + escHtml(cfg.primaryDesc) + '</p>' +
            '</div></div>' +
            '<div class="bulk-dropzone" id="primary-dropzone">' +
            '<i class="fas fa-cloud-upload"></i>' +
            '<h6>Drop files here</h6>' +
            '<p>or <span class="browse-link">browse</span> — you can select multiple files</p>' +
            '<input type="file" id="primary-file-input" accept="' + cfg.primaryAccept + '" multiple>' +
            '</div>' +
            '<div class="bulk-file-count"><span><span id="primary-count">0</span> files</span>' +
            '<button type="button" class="btn btn-sm btn-outline-danger bulk-clear-btn" data-target="primary" style="display:none;font-size:.75rem;padding:.25rem .6rem;"><i class="fas fa-trash me-1"></i>Clear</button></div>' +
            '<ul class="bulk-file-list" id="primary-file-list"></ul>' +
            '</div>';
        container.append(primaryHtml);

        if (cfg.hasSecondary) {
            var secHtml = '<div class="bulk-dropzone-section">' +
                '<div class="bulk-dropzone-header">' +
                '<i class="fas ' + cfg.secondaryIcon + '" style="color: ' + cfg.secondaryIconColor + ';"></i>' +
                '<div class="bulk-dropzone-header-text">' +
                '<div class="bulk-dropzone-title">' + escHtml(cfg.secondaryLabel) + '</div>' +
                '<p class="bulk-dropzone-desc">' + escHtml(cfg.secondaryDesc) + '</p>' +
                '</div></div>' +
                '<div class="bulk-dropzone" id="secondary-dropzone">' +
                '<i class="fas fa-cloud-upload"></i>' +
                '<h6>Drop files here</h6>' +
                '<p>or <span class="browse-link">browse</span> — you can select multiple files</p>' +
                '<input type="file" id="secondary-file-input" accept="' + cfg.secondaryAccept + '" multiple>' +
                '</div>' +
                '<div class="bulk-file-count"><span><span id="secondary-count">0</span> files</span>' +
                '<button type="button" class="btn btn-sm btn-outline-danger bulk-clear-btn" data-target="secondary" style="display:none;font-size:.75rem;padding:.25rem .6rem;"><i class="fas fa-trash me-1"></i>Clear</button></div>' +
                '<ul class="bulk-file-list" id="secondary-file-list"></ul>' +
                '</div>';
            container.append(secHtml);
        }

        bindDropzoneEvents();
    }

    function bindDropzoneEvents() {
        var pDrop = $('#primary-dropzone');
        var pInput = $('#primary-file-input');

        pDrop.off('click dragover dragleave drop');
        pDrop.on('click', function () { pInput.trigger('click'); });
        pDrop.on('dragover', function (e) { e.preventDefault(); $(this).addClass('dragover'); });
        pDrop.on('dragleave drop', function () { $(this).removeClass('dragover'); });
        pDrop.on('drop', function (e) {
            e.preventDefault();
            addFiles(e.originalEvent.dataTransfer.files, 'primary');
        });
        pInput.off('change').on('change', function () {
            addFiles(this.files, 'primary');
            $(this).val('');
        });

        if (state.type && typeConfig[state.type].hasSecondary) {
            var sDrop = $('#secondary-dropzone');
            var sInput = $('#secondary-file-input');

            sDrop.off('click dragover dragleave drop');
            sDrop.on('click', function () { sInput.trigger('click'); });
            sDrop.on('dragover', function (e) { e.preventDefault(); $(this).addClass('dragover'); });
            sDrop.on('dragleave drop', function () { $(this).removeClass('dragover'); });
            sDrop.on('drop', function (e) {
                e.preventDefault();
                addFiles(e.originalEvent.dataTransfer.files, 'secondary');
            });
            sInput.off('change').on('change', function () {
                addFiles(this.files, 'secondary');
                $(this).val('');
            });
        }

        $(document).off('click', '.bulk-clear-btn').on('click', '.bulk-clear-btn', function () {
            var target = $(this).data('target');
            if (target === 'primary') state.primaryFiles = [];
            else state.secondaryFiles = [];
            renderFileList(target);
        });
    }

    function addFiles(fileList, target) {
        var cfg = typeConfig[state.type];
        var acceptKey = target === 'primary' ? 'primaryAccept' : 'secondaryAccept';
        var acceptList = cfg[acceptKey].split(',').map(function (a) { return a.trim().toLowerCase(); });
        var arr = target === 'primary' ? state.primaryFiles : state.secondaryFiles;

        for (var i = 0; i < fileList.length; i++) {
            var f = fileList[i];
            var ext = '.' + f.name.split('.').pop().toLowerCase();
            if (acceptList.indexOf(ext) === -1) continue;
            var isDup = arr.some(function (p) { return p.name === f.name; });
            if (!isDup) arr.push(f);
        }
        renderFileList(target);
    }

    function renderFileList(target) {
        var arr = target === 'primary' ? state.primaryFiles : state.secondaryFiles;
        var listId = target === 'primary' ? '#primary-file-list' : '#secondary-file-list';
        var countId = target === 'primary' ? '#primary-count' : '#secondary-count';
        var list = $(listId);
        list.empty();
        $(countId).text(arr.length);

        var clearBtn = $(listId).closest('.bulk-dropzone-section').find('.bulk-clear-btn');
        clearBtn.toggle(arr.length > 0);

        arr.forEach(function (f, i) {
            var ext = f.name.split('.').pop().toLowerCase();
            var icon = (ext === 'pdf') ? 'fa-file-pdf' : 'fa-file-image-o';
            var iconColor = (ext === 'pdf') ? 'color: #dc2626;' : 'color: #2563eb;';
            list.append(
                '<li class="bulk-file-item">' +
                '<span class="bulk-file-pos">' + (i + 1) + '</span>' +
                '<i class="fas ' + icon + '" style="' + iconColor + '"></i>' +
                '<span class="file-name">' + escHtml(f.name) + '</span>' +
                '<span class="file-size">' + formatSize(f.size) + '</span>' +
                '<button type="button" class="file-remove" data-target="' + target + '" data-idx="' + i + '" title="Remove"><i class="fas fa-times"></i></button>' +
                '</li>'
            );
        });
    }

    $(document).on('click', '.file-remove', function () {
        var idx = parseInt($(this).data('idx'));
        var target = $(this).data('target');
        if (target === 'primary') state.primaryFiles.splice(idx, 1);
        else state.secondaryFiles.splice(idx, 1);
        renderFileList(target);
    });

    $('#btn-step3-back').on('click', function () { goToStep(2); });
    $('#btn-step3-next').on('click', function () {
        buildAssignments();
        goToStep(4);
    });

    /* ===================== STEP 4: ASSIGNMENTS ===================== */

    function buildAssignments() {
        var rows = state.csvData.rows;
        var cfg = typeConfig[state.type];
        var showSecondary = cfg.hasSecondary;
        var container = $('#assign-rows-container');
        container.empty();

        $('#summary-secondary-col').toggle(showSecondary);
        if (showSecondary) {
            $('#summary-secondary-label').text(cfg.secondaryLabel + ' Matched');
            $('#step4-description').html(
                'Files are auto-named from your CSV titles and matched <strong>by upload order</strong>. ' +
                'Drag rows to reorder. Edit filenames if needed. Rows without a primary file will be skipped.'
            );
        } else {
            $('#step4-description').html(
                'Files are auto-named from your CSV titles and matched <strong>by upload order</strong>. ' +
                'Drag rows to reorder. Edit filenames if needed. Rows without a file will be skipped.'
            );
        }

        var matched = 0, secondaryMatched = 0, unmatched = 0;

        rows.forEach(function (r, i) {
            var autoPrimary = (i < state.primaryFiles.length) ? i : -1;
            var autoSecondary = (showSecondary && i < state.secondaryFiles.length) ? i : -1;

            if (autoPrimary >= 0) matched++;
            else unmatched++;
            if (showSecondary && autoSecondary >= 0) secondaryMatched++;

            var title = r.data.research_title || r.data.journal_title || r.data.infographic_title || r.data.report_title || 'Untitled';
            var ext = (autoPrimary >= 0) ? state.primaryFiles[i].name.split('.').pop().toLowerCase() : 'pdf';
            var autoPrimaryName = autoGenerateFilename(title, cfg.primarySuffix, i);
            var autoSecondaryName = '';
            if (showSecondary && autoSecondary >= 0) {
                var secFile = state.secondaryFiles[autoSecondary];
                var secExt = secFile.name.split('.').pop().toLowerCase();
                if (cfg.secondarySuffix.charAt(0) === '.') {
                    autoSecondaryName = slugify(title) + '-Row' + (i + 1) + cfg.secondarySuffix;
                } else {
                    autoSecondaryName = slugify(title) + '-Row' + (i + 1) + cfg.secondarySuffix + '.' + secExt;
                }
            }

            state.assignments[i] = {
                primaryIdx: autoPrimary,
                secondaryIdx: autoSecondary,
                customPrimaryName: autoPrimary >= 0 ? autoPrimaryName : '',
                customSecondaryName: autoSecondaryName
            };

            var html = '<div class="bulk-assign-row" data-row="' + i + '" draggable="true">' +
                '<span class="bulk-drag-handle" title="Drag to reorder"><i class="fas fa-grip-vertical"></i></span>' +
                '<span class="row-num">Row ' + (i + 1) + '</span>';

            /* Primary file select + filename input */
            html += '<div class="bulk-assign-group">' +
                '<div class="bulk-assign-label">Primary File</div>' +
                '<select class="form-select form-select-sm admin-select assign-select-primary" data-row="' + i + '">';
            html += '<option value="-1">-- No File --</option>';
            state.primaryFiles.forEach(function (f, fi) {
                var sel = (fi === autoPrimary) ? ' selected' : '';
                html += '<option value="' + fi + '"' + sel + '>File ' + (fi + 1) + '. ' + escHtml(f.name) + '</option>';
            });
            html += '</select></div>';

            /* Editable filename for primary */
            html += '<div class="bulk-assign-group bulk-assign-filename-group">' +
                '<div class="bulk-assign-label">Saved As</div>' +
                '<input type="text" class="form-control form-control-sm admin-input assign-filename-primary" data-row="' + i + '" ' +
                'value="' + escHtml(autoPrimary >= 0 ? autoPrimaryName : '') + '" ' +
                'placeholder="Filename will be auto-generated" ' +
                (autoPrimary < 0 ? 'disabled' : '') + '></div>';

            /* Preview button for primary */
            html += '<button type="button" class="btn btn-sm btn-outline-primary assign-preview-btn" data-row="' + i + '" data-target="primary" data-file-idx="' + autoPrimary + '" ' +
                (autoPrimary < 0 ? 'disabled' : '') + ' title="Preview PDF"><i class="fas fa-eye"></i></button>';

            if (showSecondary) {
                html += '<div class="bulk-assign-divider"></div>';

                /* Secondary file select */
                html += '<div class="bulk-assign-group">' +
                    '<div class="bulk-assign-label">' + escHtml(cfg.secondaryLabel) + '</div>' +
                    '<select class="form-select form-select-sm admin-select assign-select-secondary" data-row="' + i + '">';
                html += '<option value="-1">-- None --</option>';
                state.secondaryFiles.forEach(function (f, fi) {
                    var sel = (fi === autoSecondary) ? ' selected' : '';
                    html += '<option value="' + fi + '"' + sel + '>File ' + (fi + 1) + '. ' + escHtml(f.name) + '</option>';
                });
                html += '</select></div>';

                /* Editable filename for secondary */
                var secExt = (autoSecondary >= 0) ? state.secondaryFiles[autoSecondary].name.split('.').pop().toLowerCase() : 'png';
                html += '<div class="bulk-assign-group bulk-assign-filename-group">' +
                    '<div class="bulk-assign-label">Saved As</div>' +
                    '<input type="text" class="form-control form-control-sm admin-input assign-filename-secondary" data-row="' + i + '" ' +
                    'value="' + escHtml(autoSecondaryName) + '" ' +
                    'placeholder="Filename will be auto-generated" ' +
                    (autoSecondary < 0 ? 'disabled' : '') + '></div>';
            }

            /* Row title (truncated) */
            html += '<span class="row-title" title="' + escHtml(title) + '">' + escHtml(title) + '</span>';

            /* Status icon */
            var statusIcon = autoPrimary >= 0
                ? '<i class="fas fa-check-circle assign-status matched"></i>'
                : '<i class="fas fa-circle assign-status unmatched" style="font-size:.6rem;"></i>';
            html += statusIcon + '</div>';

            container.append(html);
        });

        /* Initialize drag-and-drop reordering */
        initDragReorder();

        $('#summary-total').text(rows.length);
        $('#summary-matched').text(matched);
        if (showSecondary) $('#summary-secondary').text(secondaryMatched);
        $('#summary-unmatched').text(unmatched);
    }

    /* ---- Drag & drop reordering of assign rows ---- */

    var draggedRow = null;

    function initDragReorder() {
        var container = document.getElementById('assign-rows-container');
        if (!container) return;

        container.addEventListener('dragstart', function (e) {
            var row = e.target.closest('.bulk-assign-row');
            if (!row) return;
            draggedRow = row;
            row.classList.add('dragging');
            e.dataTransfer.effectAllowed = 'move';
            e.dataTransfer.setData('text/plain', '');
        });

        container.addEventListener('dragover', function (e) {
            e.preventDefault();
            e.dataTransfer.dropEffect = 'move';
            var afterElement = getDragAfterElement(container, e.clientY);
            if (draggedRow) {
                if (afterElement == null) {
                    container.appendChild(draggedRow);
                } else {
                    container.insertBefore(draggedRow, afterElement);
                }
            }
        });

        container.addEventListener('dragend', function (e) {
            if (draggedRow) {
                draggedRow.classList.remove('dragging');
                draggedRow = null;
                reindexAssignRows();
            }
        });
    }

    function getDragAfterElement(container, y) {
        var elements = Array.from(container.querySelectorAll('.bulk-assign-row:not(.dragging)'));
        return elements.reduce(function (closest, child) {
            var box = child.getBoundingClientRect();
            var offset = y - box.top - box.height / 2;
            if (offset < 0 && offset > closest.offset) {
                return { offset: offset, element: child };
            }
            return closest;
        }, { offset: Number.NEGATIVE_INFINITY }).element;
    }

    function reindexAssignRows() {
        var newAssignments = {};
        var newRows = [];
        var rows = state.csvData.rows;

        $('#assign-rows-container .bulk-assign-row').each(function (newIdx) {
            var oldIdx = parseInt($(this).data('row'));
            newAssignments[newIdx] = state.assignments[oldIdx];
            newAssignments[newIdx].customPrimaryName = $(this).find('.assign-filename-primary').val() || '';
            newAssignments[newIdx].customSecondaryName = $(this).find('.assign-filename-secondary').val() || '';
            $(this).data('row', newIdx);
            $(this).find('.row-num').text('Row ' + (newIdx + 1));
            $(this).find('.assign-select-primary, .assign-select-secondary, .assign-filename-primary, .assign-filename-secondary, .assign-preview-btn').attr('data-row', newIdx);
            newRows[newIdx] = rows[oldIdx];
        });

        state.assignments = newAssignments;
        state.csvData.rows = newRows;

        /* Re-apply event bindings for selects and inputs */
        $('#assign-rows-container .bulk-assign-row').each(function (newIdx) {
            var $row = $(this);
            var assignment = state.assignments[newIdx];
            var $status = $row.find('.assign-status');
            $status.removeClass('matched unmatched');
            if (assignment.primaryIdx >= 0) {
                $status.addClass('matched').html('<i class="fas fa-check-circle"></i>');
            } else {
                $status.addClass('unmatched').html('<i class="fas fa-circle" style="font-size:.6rem;"></i>');
            }
        });

        updateSummaryCounts();
    }

    /* ---- Select changes ---- */

    $(document).on('change', '.assign-select-primary', function () {
        var row = parseInt($(this).data('row'));
        var val = parseInt($(this).val());
        var cfg = typeConfig[state.type];
        state.assignments[row].primaryIdx = val;

        var $row = $(this).closest('.bulk-assign-row');
        var $status = $row.find('.assign-status');
        var $filenameInput = $row.find('.assign-filename-primary');
        var $previewBtn = $row.find('.assign-preview-btn');

        $status.removeClass('matched unmatched');
        if (val >= 0) {
            $status.addClass('matched').html('<i class="fas fa-check-circle"></i>');
            var file = state.primaryFiles[val];
            var title = state.csvData.rows[row].data.research_title || state.csvData.rows[row].data.journal_title || state.csvData.rows[row].data.infographic_title || state.csvData.rows[row].data.report_title || 'Untitled';
            var autoName = autoGenerateFilename(title, cfg.primarySuffix, row);
            $filenameInput.val(autoName).prop('disabled', false);
            $previewBtn.prop('disabled', false).data('file-idx', val);
        } else {
            $status.addClass('unmatched').html('<i class="fas fa-circle" style="font-size:.6rem;"></i>');
            $filenameInput.val('').prop('disabled', true);
            $previewBtn.prop('disabled', true);
        }

        state.assignments[row].customPrimaryName = $filenameInput.val();
        updateSummaryCounts();
    });

    $(document).on('change', '.assign-select-secondary', function () {
        var row = parseInt($(this).data('row'));
        var val = parseInt($(this).val());
        var cfg = typeConfig[state.type];
        state.assignments[row].secondaryIdx = val;

        var $row = $(this).closest('.bulk-assign-row');
        var $filenameInput = $row.find('.assign-filename-secondary');

        if (val >= 0) {
            var file = state.secondaryFiles[val];
            var title = state.csvData.rows[row].data.research_title || state.csvData.rows[row].data.journal_title || state.csvData.rows[row].data.infographic_title || state.csvData.rows[row].data.report_title || 'Untitled';
            var secExt = file.name.split('.').pop().toLowerCase();
            var autoName;
            if (cfg.secondarySuffix.charAt(0) === '.') {
                autoName = slugify(title) + '-Row' + (row + 1) + cfg.secondarySuffix;
            } else {
                autoName = slugify(title) + '-Row' + (row + 1) + cfg.secondarySuffix + '.' + secExt;
            }
            $filenameInput.val(autoName).prop('disabled', false);
        } else {
            $filenameInput.val('').prop('disabled', true);
        }

        state.assignments[row].customSecondaryName = $filenameInput.val();
        updateSummaryCounts();
    });

    /* ---- Editable filename inputs ---- */

    $(document).on('input', '.assign-filename-primary', function () {
        var row = parseInt($(this).data('row'));
        state.assignments[row].customPrimaryName = $(this).val();
    });

    $(document).on('input', '.assign-filename-secondary', function () {
        var row = parseInt($(this).data('row'));
        state.assignments[row].customSecondaryName = $(this).val();
    });

    /* ---- Preview button ---- */

    $(document).on('click', '.assign-preview-btn', function (e) {
        e.preventDefault();
        var idx = parseInt($(this).data('file-idx'));
        var target = $(this).data('target');
        if (idx < 0) return;

        var file;
        if (target === 'primary') file = state.primaryFiles[idx];
        else file = state.secondaryFiles[idx];
        if (!file) return;

        var ext = file.name.split('.').pop().toLowerCase();
        if (ext !== 'pdf') {
            var url = URL.createObjectURL(file);
            window.open(url, '_blank');
            return;
        }

        var url = URL.createObjectURL(file);
        $('#pdf-preview-frame').attr('src', url);
        $('#pdf-preview-filename').text(file.name);
        var modal = new bootstrap.Modal(document.getElementById('pdfPreviewModal'));
        modal.show();
    });

    $('#pdfPreviewModal').on('hidden.bs.modal', function () {
        $('#pdf-preview-frame').attr('src', '');
    });

    /* ---- Summary counts ---- */

    function updateSummaryCounts() {
        var all = Object.values(state.assignments);
        var matched = all.filter(function (a) { return a.primaryIdx >= 0; }).length;
        var secondaryMatched = all.filter(function (a) { return a.secondaryIdx >= 0; }).length;
        var unmatched = all.filter(function (a) { return a.primaryIdx < 0; }).length;
        $('#summary-matched').text(matched);
        if (typeConfig[state.type].hasSecondary) $('#summary-secondary').text(secondaryMatched);
        $('#summary-unmatched').text(unmatched);
    }

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
        salikAlert('#done-alert', null, '');
        $('#process-results').empty();
        $('#process-progress-bar').css('width', '0%');
        $('#process-progress-text').text('Preparing upload...');

        var bar = document.getElementById('process-progress-bar');
        if (bar && typeof bar.scrollIntoView === 'function') {
            try { bar.scrollIntoView({ behavior: 'smooth', block: 'center' }); } catch (e) { bar.scrollIntoView(true); }
        }

        $('#btn-step4-back, #btn-step4-next, .bulk-type-card').prop('disabled', true).addClass('pe-none');

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
            var assignment = state.assignments[processed] || { primaryIdx: -1, secondaryIdx: -1, customPrimaryName: '', customSecondaryName: '' };

            if (assignment.primaryIdx < 0) {
                skipCount++;
                addResultRow(processed + 1, 'skipped', 'No primary file assigned — skipped');
                processed++;
                updateProgress(processed, totalRows);
                processNext();
                return;
            }

            var matchedFile = state.primaryFiles[assignment.primaryIdx];
            if (!matchedFile) {
                skipCount++;
                addResultRow(processed + 1, 'skipped', 'Primary file not found');
                processed++;
                updateProgress(processed, totalRows);
                processNext();
                return;
            }

            var formData = new FormData();
            formData.append('type', state.type);
            formData.append('csv_row', JSON.stringify(row.data));
            formData.append('pdf_file', matchedFile, matchedFile.name);

            if (assignment.customPrimaryName) {
                formData.append('custom_file_name', assignment.customPrimaryName);
            }

            if (assignment.secondaryIdx >= 0) {
                var secFile = state.secondaryFiles[assignment.secondaryIdx];
                if (secFile) {
                    formData.append('secondary_file', secFile, secFile.name);
                    if (assignment.customSecondaryName) {
                        formData.append('custom_file_name2', assignment.customSecondaryName);
                    }
                }
            }

            $.ajax({
                url: '../src/process/bulk-upload-process.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function (res) {
                    if (res && res.response === 'success') {
                        successCount++;
                        var msg = (res.title || 'Uploaded successfully');
                        if (res.has_secondary) msg += ' + ' + res.secondary_type;
                        addResultRow(processed + 1, 'success', msg);
                    } else if (res && res.response === 'duplicate_error') {
                        errorCount++;
                        addResultRow(processed + 1, 'error', 'Duplicate: a file with the same name already exists');
                    } else if (res && res.response === 'size_error') {
                        errorCount++;
                        addResultRow(processed + 1, 'error', 'File too large (max 10 MB)');
                    } else if (res && res.response === 'type_error') {
                        errorCount++;
                        addResultRow(processed + 1, 'error', 'Invalid file type');
                    } else if (res && res.response === 'feature_disabled') {
                        errorCount++;
                        addResultRow(processed + 1, 'error', 'Submissions are currently disabled');
                    } else if (res && res.response === 'error') {
                        errorCount++;
                        addResultRow(processed + 1, 'error', 'Error' + (res.errorText ? ': ' + res.errorText : ''));
                    } else {
                        errorCount++;
                        addResultRow(processed + 1, 'error', (res && (res.errorText || res.message)) || 'Upload rejected');
                    }
                },
                error: function (xhr, textStatus) {
                    errorCount++;
                    var detail = xhr.status === 0 ? 'Connection lost' : 'Server error (' + xhr.status + ')';
                    addResultRow(processed + 1, 'error', detail);
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
        $('#btn-step4-back, #btn-step4-next').prop('disabled', false).removeClass('pe-none');
        $('#processing-active').hide();
        $('#processing-done').show();
        var total = state.csvData.rows.length;
        var parts = [];
        if (success > 0) parts.push('<strong>' + success + '</strong> uploaded');
        if (errors > 0) parts.push('<strong>' + errors + '</strong> failed');
        if (skipped > 0) parts.push('<strong>' + skipped + '</strong> skipped');
        $('#done-summary-text').html(parts.join(' &middot; ') + ' out of ' + total + ' total rows.');

        var $iconBox = $('#processing-done > div').first();
        var $icon = $iconBox.find('i');
        var $title = $('#processing-done h5');

        if (errors === 0 && success > 0) {
            salikAlert('#done-alert', 'success', '<strong>Bulk upload complete!</strong> All ' + success + ' item' + (success === 1 ? '' : 's') + ' were uploaded and are published.');
            $iconBox.css('background', '#dcfce7');
            $icon.attr('class', 'fas fa-check').css('color', '#16a34a');
            $title.text('Upload Complete');
        } else if (success === 0) {
            salikAlert('#done-alert', 'danger',
                '<strong>Upload failed!</strong> No items were uploaded.' +
                (errors > 0 ? ' ' + errors + ' item' + (errors === 1 ? '' : 's') + ' failed.' : '') +
                (skipped > 0 ? ' ' + skipped + ' row' + (skipped === 1 ? ' was' : 's were') + ' skipped.' : '') +
                ' Fix the issues and try again.');
            $iconBox.css('background', '#fef2f2');
            $icon.attr('class', 'fas fa-times').css('color', '#dc2626');
            $title.text('Upload Failed');
        } else {
            salikAlert('#done-alert', 'warning',
                '<strong>Bulk upload finished with errors.</strong> ' + success + ' of ' + total + ' items uploaded. Review the failures above.');
            $iconBox.css('background', '#fef9c3');
            $icon.attr('class', 'fas fa-exclamation-triangle').css('color', '#ca8a04');
            $title.text('Completed with Errors');
        }
    }

    $('#btn-start-over').on('click', function () {
        state.type = null;
        state.csvData = null;
        state.csvFileName = null;
        state.primaryFiles = [];
        state.secondaryFiles = [];
        state.assignments = {};
        state.processing = false;

        $('.bulk-type-card').removeClass('selected');
        $('#btn-step1-next').prop('disabled', true);
        $('#summary-secondary-col').hide();
        $('#step3-dropzones').empty();
        resetStep2();
        goToStep(1);
    });
});
