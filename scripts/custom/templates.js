/* ============================================================
   SALIKSIK — Admin submissions result card templates
   ============================================================ */

var entityMap = {
  "&": "&amp;",
  "<": "&lt;",
  ">": "&gt;",
  '"': "&quot;",
  "'": "&#39;",
  "/": "&#x2F;",
  "`": "&#x60;",
  "=": "&#x3D;",
};

function escapeHtml(string) {
  if (string === null || string === undefined) return "";
  return String(string).replace(/[&<>"'`=\/]/g, function (s) {
    return entityMap[s];
  });
}

var STATUS_META = {
  "pending":      { label: "For Approval", cls: "status-pending",      icon: "fa-hourglass-half" },
  "for revision": { label: "For Revision", cls: "status-for-revision", icon: "fa-rotate-left" },
  "revised":      { label: "Revised",      cls: "status-revised",      icon: "fa-file-circle-check" },
  "published":    { label: "Published",    cls: "status-published",    icon: "fa-book-open" }
};

var TYPE_ICONS = {
  "thesis": "fa-graduation-cap",
  "journal": "fa-newspaper",
  "infographic": "fa-image",
  "report": "fa-clipboard-list"
};

function fmtDate(value, withTime) {
  if (!value) return "";
  var d = new Date(String(value).replace(" ", "T"));
  if (isNaN(d.getTime())) return escapeHtml(value);
  var opts = withTime
    ? { month: "short", day: "numeric", year: "numeric", hour: "numeric", minute: "2-digit" }
    : { month: "short", day: "numeric", year: "numeric" };
  return d.toLocaleString("en-US", opts);
}

function statusBadge(status) {
  var meta = STATUS_META[status] || { label: escapeHtml(status), cls: "status-pending", icon: "fa-circle" };
  return '<span class="status-badge ' + meta.cls + '"><i class="fas ' + meta.icon + '" style="font-size:.6rem;"></i>' + meta.label + "</span>";
}

function truncate(str, len) {
  str = String(str || "");
  if (!str) return "";
  return str.length > len ? escapeHtml(str.slice(0, len).trimEnd()) + "&hellip;" : escapeHtml(str);
}

/**
 * Unified result card. `o` fields:
 *   fileId, fileType, title, subtitle, kicker[], badgeText (date line),
 *   abstract, feedback, dateLabel+dateValue OR customMeta html
 */
function resultCard(o) {
  var kicker = o.kicker.filter(Boolean).map(escapeHtml).join(" &middot; ");
  var icon = TYPE_ICONS[o.fileType] || "fa-file";
  var feedback = "";
  if (o.feedback !== undefined) {
    feedback =
      '<div class="feedback-box"><p class="feedback-label mb-1"><i class="fas fa-comment-dots me-1"></i>Feedback Returned</p>' +
      '<p class="mb-0">' + (escapeHtml(o.feedback) || "<em>No feedback text provided.</em>") + "</p></div>";
  }

  return '' +
    '<div class="col-xl-6">' +
      '<div class="submission-card">' +
        '<div class="results-meta-row mb-1">' +
          '<span class="card-kicker mb-0"><i class="fas ' + icon + ' me-1"></i>' + kicker + "</span>" +
          statusBadge(o.status)
        + "</div>" +
        (o.subtitle
          ? '<a href="submissions/view.php?id=' + o.fileId + '" class="card-title-link d-block">' + escapeHtml(o.title) + "</a>" +
            '<p class="mb-1" style="font-size:.84rem;color:var(--muted);font-weight:600;">' + escapeHtml(o.subtitle) + "</p>"
          : '<a href="submissions/view.php?id=' + o.fileId + '" class="card-title-link d-block mb-1">' + escapeHtml(o.title) + "</a>") +
        (o.abstract ? '<p class="card-body-text mb-2">' + truncate(o.abstract, 160) + "</p>" : "") +
        feedback +
        '<div class="card-actions d-flex align-items-center justify-content-between gap-2 flex-wrap">' +
          '<span class="card-date"><i class="far fa-clock me-1"></i>' + o.dateLabel + " " + o.dateValue + "</span>" +
          '<a href="submissions/view.php?id=' + o.fileId + '" class="admin-btn" style="padding:.4rem 1.1rem;font-size:.8rem;"><i class="fas fa-folder-open me-1"></i>Review</a>'
        + "</div>" +
      "</div>" +
    "</div>";
}

export function pendingThesisTemplate(result) {
  return resultCard({
    fileId: result.file_id,
    fileType: "thesis",
    status: "pending",
    kicker: [result.research_type, result.researchers_category, result.research_unit],
    title: result.research_title,
    abstract: result.research_abstract,
    dateLabel: "Submitted",
    dateValue: fmtDate(result.submitted_on, true)
  });
}

export function revisionThesisTemplate(result) {
  return resultCard({
    fileId: result.file_id,
    fileType: "thesis",
    status: "for revision",
    kicker: [result.research_type, result.researchers_category, result.research_unit],
    title: result.research_title,
    feedback: result.feedback,
    dateLabel: "Returned",
    dateValue: fmtDate(result.returned_on, true)
  });
}

export function revisedThesisTemplate(result) {
  return resultCard({
    fileId: result.file_id,
    fileType: "thesis",
    status: "revised",
    kicker: [result.research_type, result.researchers_category, result.research_unit],
    title: result.research_title,
    feedback: result.feedback,
    dateLabel: "Resubmitted",
    dateValue: fmtDate(result.submitted_on, true)
  });
}

export function publishedThesisTemplate(result) {
  return resultCard({
    fileId: result.file_id,
    fileType: "thesis",
    status: "published",
    kicker: [result.research_type, result.researchers_category, result.research_unit],
    title: result.research_title,
    abstract: result.research_abstract,
    dateLabel: "Submitted",
    dateValue: fmtDate(result.submitted_on, false),
    feedback: undefined
  });
}

export function publishedInfographicTemplate(result) {
  return resultCard({
    fileId: result.file_id,
    fileType: "infographic",
    status: "published",
    kicker: ["Infographic", result.infographic_publication_date ? new Date(String(result.infographic_publication_date).replace(" ", "T")).getFullYear() : ""],
    title: result.infographic_title,
    abstract: result.infographic_description,
    dateLabel: "Submitted",
    dateValue: fmtDate(result.submitted_on, false)
  });
}

export function publishedJournalTemplate(result) {
  return resultCard({
    fileId: result.file_id,
    fileType: "journal",
    status: "published",
    kicker: ["Journal", result.department],
    title: result.journal_title,
    subtitle: result.journal_subtitle || "",
    abstract: result.journal_description,
    dateLabel: "Submitted",
    dateValue: fmtDate(result.submitted_on, false)
  });
}

export function publishedReportTemplate(result) {
  return resultCard({
    fileId: result.file_id,
    fileType: "report",
    status: "published",
    kicker: ["Report", result.report_type, result.report_year],
    title: result.report_title,
    dateLabel: "Submitted",
    dateValue: fmtDate(result.submitted_on, false)
  });
}
