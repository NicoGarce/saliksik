/* ============================================================
   SALIKSIK — Shared submission helpers
   Standardized AJAX submit: progress bar, button locking,
   full success/failure alerts, network error handling.
   Requires jQuery 3.6.
   ============================================================ */

(function () {
    'use strict';

    var RESPONSE_MESSAGES = {
        type_error: '<strong>File upload failed!</strong> Check to make sure the file is in <strong>PDF</strong> format, or that the file to be uploaded is attached.',
        input_error: '<strong>Submission failed!</strong> One or more fields have invalid values. Please review the form and try again.',
        invalid_email: '<strong>Invalid Email!</strong> Please check all the email fields and enter a valid email address.',
        size_error: '<strong>File upload failed!</strong> The file size is too large. The maximum allowed size is 10 MB.',
        generic_error: '<strong>File upload failed!</strong> Check to make sure the file is <strong>less than 10 MB</strong> or that the file to be submitted is attached.',
        duplicate_error: '<strong>File upload failed!</strong> There is already a file with the same name uploaded to the database.',
        feature_disabled: '<strong>Submissions are currently disabled.</strong> Please check back later or contact the administrator.',
        error: null /* uses server errorText */
    };

    function escHtml(str) {
        return String(str == null ? '' : str)
            .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;').replace(/'/g, '&#39;');
    }

    /**
     * Render a dismissible Bootstrap alert inside a container and scroll it into view.
     * @param {string|jQuery} container selector of the alert container
     * @param {'success'|'danger'|'warning'} kind
     * @param {string} html inner HTML (already escaped where needed)
     */
    function salikAlert(container, kind, html) {
        var $c = $(container);
        if (!$c.length) return;
        if (!kind) { $c.empty(); return; }
        var cls = kind === 'success' ? 'alert-success' : (kind === 'warning' ? 'alert-warning' : 'alert-danger');
        var icon = kind === 'success' ? 'fa-circle-check' : (kind === 'warning' ? 'fa-triangle-exclamation' : 'fa-circle-xmark');
        $c.html(
            '<div class="alert ' + cls + ' alert-dismissible fade show d-flex align-items-start gap-2" role="alert">' +
            '<i class="fas ' + icon + ' mt-1"></i>' +
            '<div>' + html + '</div>' +
            '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
            '</div>'
        );
        try {
            $c[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
        } catch (e) {
            window.scrollTo(0, 0);
        }
    }

    /**
     * Standardized form submission with upload progress, button locking,
     * and complete response handling.
     *
     * @param {Object} o
     *   form           selector/form element
     *   url            POST target
     *   progressBar    selector of .progress-bar element (optional)
     *   progressWrap   selector of .progress container (optional)
     *   statusLabel    selector of "Uploading..." label (optional)
     *   alertContainer selector where alerts render
     *   submitButton   selector of the submit button (input or button)
     *   onSuccess      extra callback after success (form reset etc.)
     */
    function salikSubmit(o) {
        var $form = $(o.form);
        var $bar = $(o.progressBar || []);
        var $wrap = $(o.progressWrap || []);
        var $label = $(o.statusLabel || []);
        var $btn = $(o.submitButton);

        function setProgress(pct, text) {
            $bar.attr('aria-valuenow', pct).css('width', pct + '%').text(text != null ? text : pct + '%');
        }

        function setUploadingState(busy) {
            $btn.prop('disabled', busy);
            var original = $btn.data('original-html');
            if (!original) {
                original = $btn.is('input') ? $btn.val() : $btn.html();
                $btn.data('original-html', original);
            }
            if ($btn.is('input')) {
                $btn.val(busy ? 'Uploading...' : original);
            } else {
                $btn.html(busy ? '<span class="spinner-border spinner-border-sm me-1"></span>Uploading...' : original);
            }
        }

        function resetProgress() {
            $wrap.prop('hidden', true);
            $label.prop('hidden', true);
            setProgress(0, '0%');
        }

        function finish(kind, html, afterSuccess) {
            resetProgress();
            setUploadingState(false);
            salikAlert(o.alertContainer, kind, html);
            if (kind === 'success' && typeof afterSuccess === 'function') afterSuccess();
        }

        $form.on('submit', function (event) {
            event.preventDefault();
            if ($btn.prop('disabled') && $btn.data('uploading') === true) return;

            var formData = new FormData(this);
            $btn.data('uploading', true);
            setUploadingState(true);
            $(o.alertContainer).empty();
            if ($wrap.length) $wrap.prop('hidden', false);
            if ($label.length) {
                $label.text('Uploading your file...');
                $label.prop('hidden', false);
            }
            setProgress(0, '0%');

            $.ajax({
                xhr: function () {
                    var xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener('progress', function (e) {
                        if (e.lengthComputable) {
                            var pct = Math.round((e.loaded / e.total) * 100);
                            setProgress(pct);
                            if (pct >= 100 && $label.length) {
                                $label.text('Upload complete. Processing your submission...');
                            }
                        }
                    });
                    return xhr;
                },
                method: 'POST',
                url: o.url,
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json'
            }).done(function (data) {
                var resp = data && data.response;

                if (resp === 'success' || resp === 'success_admin') {
                    var msg = resp === 'success'
                        ? '<strong>Submission successful!</strong> Wait for your submission to be approved by the administration. You can view the submission status under <strong>My Profile &rarr; My Submissions</strong>.'
                        : '<strong>Upload successful!</strong> You can now view the submission inside the <strong>Repository</strong>.';
                    finish('success', msg, o.onSuccess);
                    return;
                }

                var custom = RESPONSE_MESSAGES[resp];
                if (resp === 'error') {
                    finish('danger',
                        '<strong>Database error!</strong> Your submission could not be saved.' +
                        (data.errorText ? '<br><small class="text-muted">' + escHtml(data.errorText) + '</small>' : ''));
                } else if (custom) {
                    finish('danger', custom);
                } else {
                    finish('danger', '<strong>Unexpected server response.</strong> Please try again.');
                }
            }).fail(function (xhr, textStatus, errorThrown) {
                var msg;
                if (textStatus === 'timeout') {
                    msg = '<strong>Request timed out!</strong> The upload took too long. Please check your connection and try again.';
                } else if (xhr.status >= 500) {
                    msg = '<strong>Server error (' + xhr.status + ').</strong> Something went wrong on our side. Please try again later.';
                } else if (textStatus === 'parsererror') {
                    msg = '<strong>Unexpected server response.</strong> The submission may not have been saved. Please verify under <strong>My Submissions</strong> before retrying.';
                } else if (xhr.status === 0) {
                    msg = '<strong>Connection lost!</strong> Could not reach the server. Please check your internet connection and try again.';
                } else {
                    msg = '<strong>Upload failed!</strong> ' + escHtml(errorThrown || textStatus) + '. Please try again.';
                }
                finish('danger', msg);
            }).always(function () {
                $btn.data('uploading', false);
            });
        });
    }

    window.salikAlert = salikAlert;
    window.salikSubmit = salikSubmit;
    window.salikEscHtml = escHtml;
})();
