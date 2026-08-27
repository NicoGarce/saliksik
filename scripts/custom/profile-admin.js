import {
  pendingThesisTemplate,
  pendingJournalTemplate,
  pendingInfographicTemplate,
  pendingReportTemplate,
  revisionThesisTemplate,
  revisionJournalTemplate,
  revisionInfographicTemplate,
  revisionReportTemplate,
  revisedThesisTemplate,
  revisedJournalTemplate,
  revisedInfographicTemplate,
  revisedReportTemplate,
  publishedThesisTemplate,
  publishedInfographicTemplate,
  publishedJournalTemplate,
  publishedReportTemplate,
} from "./templates.js?v=5";
$(document).ready(function () {
  submitData(1);
});
$("#submission-status-dropdown").on("change", function () {
  submitData(1);
});
$("#submission-category-dropdown").on("change", function () {
  submitData(1);
});
$("#admin-search-button").on("click", function () {
  submitData(1);
});
$("#search-submissions-admin").on("keypress", function (e) {
  if (e.which === 13) { submitData(1); }
});
// =========================== counters ==============================
$("#pending-container").on("click", function () {
  $("#submission-status-dropdown").val("pending").trigger("change");
});
$("#revision-container").on("click", function () {
  $("#submission-status-dropdown").val("for revision").trigger("change");
});
$("#revised-container").on("click", function () {
  $("#submission-status-dropdown").val("revised").trigger("change");
});
$("#published-container").on("click", function () {
  $("#submission-status-dropdown").val("published").trigger("change");
});
$("#submissions-container").on("click", function () {
  $("#submission-status-dropdown").val("submissions").trigger("change");
});
// =========================== counters ==============================

$(document).on("click", ".submissions-page-btn[data-page]", function () {
  var page = $(this).data("page");
  if (page && page !== "current") submitData(page);
});

function submitData(page) {
  var formData = new FormData();
  formData.append("title_query", $("#search-submissions-admin").val());
  formData.append("status_view", $("#submission-status-dropdown").val());
  formData.append("sort_by", $("#submission-category-dropdown").val());
  formData.append("page", page);
  formData.append("per_page", 10);
  $.ajax({
    method: "POST",
    url: "../src/process/get-submissions.php",
    contentType: false,
    processData: false,
    data: formData,
  }).done(function (data) {
    updateCounters(data["result_count"]);
    loadData(data["result"]);
    renderPagination(data["pagination"]);
  });
}

function loadData(data) {
  var $container = $("#results-container");
  if (data.length == 0) {
    $container.html(
      '<p class="empty-note mb-1"><i class="fas fa-inbox me-2"></i>No submissions match your search or filter.</p>'
    );
    return;
  }

  var rows = "";
  data.forEach((result) => {
    if (result["status"] == "pending") {
      if (result["file_type"] == "thesis") {
        rows += pendingThesisTemplate(result);
      } else if (result["file_type"] == "journal") {
        rows += pendingJournalTemplate(result);
      } else if (result["file_type"] == "infographic") {
        rows += pendingInfographicTemplate(result);
      } else if (result["file_type"] == "report") {
        rows += pendingReportTemplate(result);
      }
    } else if (result["status"] == "for revision") {
      if (result["file_type"] == "thesis") {
        rows += revisionThesisTemplate(result);
      } else if (result["file_type"] == "journal") {
        rows += revisionJournalTemplate(result);
      } else if (result["file_type"] == "infographic") {
        rows += revisionInfographicTemplate(result);
      } else if (result["file_type"] == "report") {
        rows += revisionReportTemplate(result);
      }
    } else if (result["status"] == "revised") {
      if (result["file_type"] == "thesis") {
        rows += revisedThesisTemplate(result);
      } else if (result["file_type"] == "journal") {
        rows += revisedJournalTemplate(result);
      } else if (result["file_type"] == "infographic") {
        rows += revisedInfographicTemplate(result);
      } else if (result["file_type"] == "report") {
        rows += revisedReportTemplate(result);
      }
    } else if (result["status"] == "published") {
      if (result["file_type"] == "thesis") {
        rows += publishedThesisTemplate(result);
      } else if (result["file_type"] == "journal") {
        rows += publishedJournalTemplate(result);
      } else if (result["file_type"] == "infographic") {
        rows += publishedInfographicTemplate(result);
      } else if (result["file_type"] == "report") {
        rows += publishedReportTemplate(result);
      }
    }
  });

  $container.html(
    '<div class="table-responsive">' +
      '<table class="admin-table results-table">' +
        "<thead><tr>" +
          "<th>Title</th><th>Type</th><th>Category</th><th>Unit</th><th>Status</th><th>Submitted</th>" +
          '<th class="text-end">Action</th>' +
        "</tr></thead>" +
        "<tbody>" + rows + "</tbody>" +
      "</table>" +
    "</div>"
  );

  $("#results-count").text(data.length + (data.length === 1 ? " item" : " items"));
}

function renderPagination(pag) {
  var $el = $("#submissions-pagination");
  if (!pag || pag.total_pages <= 1) { $el.empty(); return; }

  var html = '<div class="submissions-pagination">';
  html += '<span class="submissions-page-info">Page ' + pag.page + ' of ' + pag.total_pages + ' (' + pag.total_rows + ' total)</span>';
  html += '<div class="submissions-pagination-nav">';
  html += '<button class="submissions-page-btn" data-page="' + Math.max(1, pag.page - 1) + '"' + (pag.page <= 1 ? ' disabled' : '') + '>&laquo;</button>';

  var start = Math.max(1, pag.page - 2);
  var end = Math.min(pag.total_pages, pag.page + 2);

  if (start > 1) html += '<button class="submissions-page-btn" data-page="1">1</button>';
  if (start > 2) html += '<span class="submissions-page-btn" style="border:none;cursor:default;">...</span>';

  for (var i = start; i <= end; i++) {
    if (i === pag.page) {
      html += '<button class="submissions-page-btn active" data-page="current">' + i + '</button>';
    } else {
      html += '<button class="submissions-page-btn" data-page="' + i + '">' + i + '</button>';
    }
  }

  if (end < pag.total_pages - 1) html += '<span class="submissions-page-btn" style="border:none;cursor:default;">...</span>';
  if (end < pag.total_pages) html += '<button class="submissions-page-btn" data-page="' + pag.total_pages + '">' + pag.total_pages + '</button>';

  html += '<button class="submissions-page-btn" data-page="' + Math.min(pag.total_pages, pag.page + 1) + '"' + (pag.page >= pag.total_pages ? ' disabled' : '') + '>&raquo;</button>';
  html += '</div></div>';

  $el.html(html);
}

function updateCounters(data) {
  var total_submissions = 0;
  data.forEach((element) => {
    total_submissions += element.count;
    if (element.status == "pending") {
      $("#pending-container .display-4").html(element.count);
    } else if (element.status == "for revision") {
      $("#revision-container .display-4").html(element.count);
    } else if (element.status == "revised") {
      $("#revised-container .display-4").html(element.count);
    } else if (element.status == "published") {
      $("#published-container .display-4").html(element.count);
    }
  });
  $("#submissions-container .display-4").html(total_submissions);
}
