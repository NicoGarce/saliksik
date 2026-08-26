import {
  pendingThesisTemplate,
  revisionThesisTemplate,
  revisedThesisTemplate,
  publishedThesisTemplate,
  publishedInfographicTemplate,
  publishedJournalTemplate,
} from "./user-submissions-template.js";

$(document).ready(function () {
  $.ajax({
    method: "POST",
    url: "../src/process/get-user-submissions.php",
    contentType: false,
    processData: false,
  }).done(function (data) {
    loadData(data);
  });
});

function loadData(data) {
  data.forEach((result) => {
    if (result["status"] == "pending") {
      if (result["file_type"] == "thesis") {
        $("#pending-container").append(pendingThesisTemplate(result));
      }
    } else if (result["status"] == "for revision") {
      if (result["file_type"] == "thesis") {
        $("#revision-container").append(revisionThesisTemplate(result));
      }
    } else if (result["status"] == "revised") {
      if (result["file_type"] == "thesis") {
        $("#revised-container").append(revisedThesisTemplate(result));
      }
    } else if (result["status"] == "published") {
      if (result["file_type"] == "thesis") {
        $("#published-container").append(publishedThesisTemplate(result));
      } else if (result["file_type"] == "journal") {
        $("#published-container").append(publishedJournalTemplate(result));
      } else if (result["file_type"] == "infographic") {
        $("#published-container").append(publishedInfographicTemplate(result));
      }
    }
  });

  var pendingCount = $("#pending-container .submission-card").length;
  var revisionCount = $("#revision-container .submission-card").length;
  var revisedCount = $("#revised-container .submission-card").length;
  var publishedCount = $("#published-container .submission-card").length;

  if (pendingCount === 0) $("#pending-container-wrap").hide();
  if (revisionCount === 0) $("#revision-container-wrap").hide();
  if (revisedCount === 0) $("#revised-container-wrap").hide();

  if (
    pendingCount === 0 &&
    revisionCount === 0 &&
    revisedCount === 0
  ) {
    $(".submissions").append(
      '<p class="empty-note mb-1"><i class="far fa-folder-open me-2"></i>No active submissions. Start a new one from the button above.</p>'
    );
  }

  if (publishedCount === 0) {
    $("#published-container").html(
      '<p class="empty-note mb-1"><i class="far fa-bookmark me-2"></i>Nothing published yet.</p>'
    );
  }
}
