import {
  pendingThesisTemplate,
  revisionThesisTemplate,
  revisedThesisTemplate,
  publishedThesisTemplate,
  publishedInfographicTemplate,
  publishedJournalTemplate,
  publishedReportTemplate,
} from "./templates.js?v=4";
$(document).ready(function () {
  submitData();
});
$("#submission-status-dropdown").on("change", function () {
  submitData();
});
$("#submission-category-dropdown").on("change", function () {
  submitData();
});
$("#admin-search-button").on("click", function () {
  submitData();
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

function submitData() {
  var formData = new FormData();
  formData.append("title_query", $("#search-submissions-admin").val());
  formData.append("status_view", $("#submission-status-dropdown").val());
  formData.append("sort_by", $("#submission-category-dropdown").val());
  $.ajax({
    method: "POST",
    url: "../src/process/get-submissions.php",
    contentType: false,
    processData: false,
    data: formData,
  }).done(function (data) {
    updateCounters(data["result_count"]);
    loadData(data["result"]);
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
      }
    } else if (result["status"] == "for revision") {
      if (result["file_type"] == "thesis") {
        rows += revisionThesisTemplate(result);
      }
    } else if (result["status"] == "revised") {
      if (result["file_type"] == "thesis") {
        rows += revisedThesisTemplate(result);
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
