var options = {
  month: "long",
  day: "2-digit",
  year: "numeric",
  hour: "numeric",
  minute: "numeric",
  second: "numeric",
};

export function pendingThesisTemplate(result) {
  var date = new Date(result.submitted_on);
  var strDate = date.toLocaleString("default", options);
  var template = `<div class="submission-card">
    <div class="d-flex align-items-center justify-content-between gap-2 flex-wrap">
      <span class="card-kicker">${escapeHtml(result.research_type)} &middot; ${escapeHtml(result.researchers_category)} &middot; ${escapeHtml(result.research_unit)}</span>
      <a href="submissions/view.php?id=${escapeHtml(result.file_id)}" class="editReviseButton"><i class="fas fa-edit me-1"></i>Edit</a>
    </div>
    <h4 class="mb-2" style="font-size: 1.05rem; font-weight: 700; color: var(--navy-900);">${escapeHtml(result.research_title)}</h4>
    <p class="card-date mb-0"><i class="far fa-clock me-1"></i>Submitted on ${strDate}</p>
  </div>`;
  return template;
}

export function revisionThesisTemplate(result) {
  var date = new Date(result.returned_on);
  var strDate = date.toLocaleString("default", options);
  var template = `<div class="submission-card">
    <div class="d-flex align-items-center justify-content-between gap-2 flex-wrap">
      <span class="card-kicker">${escapeHtml(result.research_type)} &middot; ${escapeHtml(result.researchers_category)} &middot; ${escapeHtml(result.research_unit)}</span>
      <a href="submissions/view.php?id=${escapeHtml(result.file_id)}" class="editReviseButton"><i class="fas fa-pen me-1"></i>Revise</a>
    </div>
    <h4 class="mb-2" style="font-size: 1.05rem; font-weight: 700; color: var(--navy-900);">${escapeHtml(result.research_title)}</h4>
    <p class="card-date mb-0"><i class="far fa-clock me-1"></i>Returned on ${strDate}</p>
    <div class="feedback-box">
      <p class="feedback-label"><i class="fas fa-comment-dots me-1"></i>Admin Feedback</p>
      <p class="mb-0">${escapeHtml(result.feedback) || "No feedback provided."}</p>
    </div>
  </div>`;
  return template;
}

export function revisedThesisTemplate(result) {
  var date = result.returned_on ? new Date(result.returned_on) : null;
  var strDate = date ? date.toLocaleString("default", options) : "";
  var template = `<div class="submission-card">
    <div class="d-flex align-items-center justify-content-between gap-2 flex-wrap">
      <span class="card-kicker">${escapeHtml(result.research_type)} &middot; ${escapeHtml(result.researchers_category)} &middot; ${escapeHtml(result.research_unit)}</span>
      <a href="submissions/view.php?id=${escapeHtml(result.file_id)}" class="editReviseButton"><i class="fas fa-eye me-1"></i>View</a>
    </div>
    <h4 class="mb-2" style="font-size: 1.05rem; font-weight: 700; color: var(--navy-900);">${escapeHtml(result.research_title)}</h4>${
      strDate ? `<p class="card-date mb-0"><i class="far fa-clock me-1"></i>Revised on ${strDate}</p>` : ""
    }
    <div class="feedback-box">
      <p class="feedback-label"><i class="fas fa-comment-dots me-1"></i>Admin Feedback</p>
      <p class="mb-0">${escapeHtml(result.feedback) || "No feedback provided."}</p>
    </div>
  </div>`;
  return template;
}

export function publishedThesisTemplate(result) {
  var date = new Date(result.published_on);
  var strDate = date.toLocaleString("default", options);
  var template = `<div class="submission-card">
    <span class="card-kicker">${escapeHtml(result.research_type)} &middot; ${escapeHtml(result.researchers_category)} &middot; ${escapeHtml(result.research_unit)}</span>
    <a href="../repository/view-article.php?id=${escapeHtml(result.file_id)}" class="card-title-link d-block mb-2">${escapeHtml(result.research_title)}</a>
    <p style="font-size: .86rem; color: var(--ink);" class="mb-2">${truncate(escapeHtml(result.research_abstract), 220)}</p>
    <p class="card-date mb-0"><i class="fas fa-check-circle me-1" style="color:#16a34a;"></i>Published on ${strDate}</p>
  </div>`;
  return template;
}

export function publishedInfographicTemplate(result) {
  var date = new Date(result.published_on);
  var strDate = date.toLocaleString("default", options);
  var template = `<div class="submission-card">
    <span class="card-kicker">Infographic${result.infographic_publication_year ? " &middot; " + escapeHtml(result.infographic_publication_year) : ""}</span>
    <a href="../repository/view-article.php?id=${escapeHtml(result.file_id)}" class="card-title-link d-block mb-2">${escapeHtml(result.infographic_title)}</a>
    <p style="font-size: .86rem; color: var(--ink);" class="mb-2">${truncate(escapeHtml(result.infographic_description), 220)}</p>
    <p class="card-date mb-0"><i class="fas fa-check-circle me-1" style="color:#16a34a;"></i>Published on ${strDate}</p>
  </div>`;
  return template;
}

export function publishedJournalTemplate(result) {
  var date = new Date(result.published_on);
  var strDate = date.toLocaleString("default", options);
  var template = `<div class="submission-card">
    <span class="card-kicker">Journal${result.department ? " &middot; " + escapeHtml(result.department) : ""}</span>
    <a href="../repository/view-article.php?id=${escapeHtml(result.file_id)}" class="card-title-link d-block mb-2">${escapeHtml(result.journal_title)}</a>
    ${result.journal_subtitle ? `<p class="fw-semibold mb-2" style="color: var(--muted); font-size: .9rem;">${escapeHtml(result.journal_subtitle)}</p>` : ""}
    <p style="font-size: .86rem; color: var(--ink);" class="mb-2">${truncate(escapeHtml(result.journal_description), 220)}</p>
    <p class="card-date mb-0"><i class="fas fa-check-circle me-1" style="color:#16a34a;"></i>Published on ${strDate}</p>
  </div>`;
  return template;
}

function truncate(str, len) {
  if (!str) return "";
  return str.length > len ? str.slice(0, len).trimEnd() + "&hellip;" : str;
}

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
