/* ============================================================
   SALIKSIK — Admin submissions result row templates (compact)
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
  "pending":      { label: "For Approval", cls: "status-pending" },
  "for revision": { label: "For Revision", cls: "status-for-revision" },
  "revised":      { label: "Revised",      cls: "status-revised" },
  "published":    { label: "Published",    cls: "status-published" }
};

function statusBadge(status) {
  var meta = STATUS_META[status] || { label: escapeHtml(status), cls: "status-pending" };
  return '<span class="status-badge ' + meta.cls + '">' + meta.label + "</span>";
}

var TYPE_LABELS = {
  thesis: "Thesis",
  journal: "Journal",
  infographic: "Infographic",
  report: "Report"
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

function truncate(str, len) {
  str = String(str || "");
  if (!str) return "";
  return str.length > len ? escapeHtml(str.slice(0, len).trimEnd()) + "&hellip;" : escapeHtml(str);
}

/**
 * Compact table row. `o` fields:
 *   fileId, typeLabel, category, unit, title, subtitle, note,
 *   status, dateLabel, dateValue
 */
function resultRow(o) {
  var subtitle = o.subtitle
    ? '<br><span class="row-note" style="color:var(--navy-700);font-weight:600;">' + escapeHtml(o.subtitle) + "</span>"
    : "";
  var note = o.note
    ? '<br><span class="row-note">' +
      '<span class="row-note-label">' + o.noteLabel + "</span>" + truncate(o.note, 90) +
      "</span>"
    : "";

  return "" +
    "<tr>" +
      '<td><a href="submissions/view.php?id=' + o.fileId + '" class="card-title-link">' + escapeHtml(o.title) + "</a>" + subtitle + note + "</td>" +
      "<td>" + escapeHtml(o.typeLabel || "") + "</td>" +
      "<td>" + escapeHtml(o.category || "") + "</td>" +
      "<td>" + escapeHtml(o.unit || "") + "</td>" +
      "<td>" + statusBadge(o.status) + "</td>" +
      '<td class="text-muted" style="white-space:nowrap;">' + o.dateValue + "</td>" +
      '<td class="text-end"><a href="submissions/view.php?id=' + o.fileId + '" class="admin-btn" style="padding:.32rem 1rem;font-size:.76rem;">Review</a></td>' +
    "</tr>";
}

export function pendingThesisTemplate(result) {
  return resultRow({
    fileId: result.file_id,
    typeLabel: result.research_type,
    category: result.researchers_category,
    unit: result.research_unit,
    title: result.research_title,
    status: "pending",
    dateLabel: "",
    dateValue: fmtDate(result.submitted_on, false)
  });
}

export function revisionThesisTemplate(result) {
  return resultRow({
    fileId: result.file_id,
    typeLabel: result.research_type,
    category: result.researchers_category,
    unit: result.research_unit,
    title: result.research_title,
    note: result.feedback,
    noteLabel: "Feedback: ",
    status: "for revision",
    dateLabel: "",
    dateValue: fmtDate(result.returned_on, false)
  });
}

export function revisedThesisTemplate(result) {
  return resultRow({
    fileId: result.file_id,
    typeLabel: result.research_type,
    category: result.researchers_category,
    unit: result.research_unit,
    title: result.research_title,
    note: result.feedback,
    noteLabel: "Last feedback: ",
    status: "revised",
    dateLabel: "",
    dateValue: fmtDate(result.submitted_on, false)
  });
}

export function publishedThesisTemplate(result) {
  return resultRow({
    fileId: result.file_id,
    typeLabel: result.research_type,
    category: result.researchers_category,
    unit: result.research_unit,
    title: result.research_title,
    status: "published",
    dateLabel: "",
    dateValue: fmtDate(result.submitted_on, false)
  });
}

export function publishedInfographicTemplate(result) {
  var year = result.infographic_publication_date
    ? new Date(String(result.infographic_publication_date).replace(" ", "T")).getFullYear()
    : "";
  return resultRow({
    fileId: result.file_id,
    typeLabel: "Infographic",
    category: year ? String(year) : "",
    unit: "",
    title: result.infographic_title,
    status: "published",
    dateLabel: "",
    dateValue: fmtDate(result.submitted_on, false)
  });
}

export function publishedJournalTemplate(result) {
  return resultRow({
    fileId: result.file_id,
    typeLabel: "Journal",
    category: result.department,
    unit: "",
    title: result.journal_title,
    subtitle: result.journal_subtitle || "",
    status: "published",
    dateLabel: "",
    dateValue: fmtDate(result.submitted_on, false)
  });
}

export function publishedReportTemplate(result) {
  return resultRow({
    fileId: result.file_id,
    typeLabel: "Report",
    category: result.report_type,
    unit: result.report_year,
    title: result.report_title,
    status: "published",
    dateLabel: "",
    dateValue: fmtDate(result.submitted_on, false)
  });
}
