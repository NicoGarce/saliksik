<?php

if (!isset($_SESSION['isLoggedIn'])) {
    header("location: ../../../error.php");
    die();
}

?>

<div class="col-lg-10 px-5 col-md-12 col-xs-12 main-column" id="infographicsPanel">

    <!-- container for alert messages -->
    <div id='alert-container-infographic'>

    </div>
    <!-- container for alert messages -->
    <h1 class="my-2">File Upload Information</h1>
    <hr>
    <form onsubmit="submitFormInfographic(event)" name="infographic-form">

        <div class="row my-2">
            <div>
                <label class="fw-bold my-2">Title/Topic<span class="text-danger"> *</span></label>
                <input type="text" class="form-control" name="textFieldInfographicsTitle" required>
            </div>
        </div>

        <div class="row">
            <!-- <div class="col-lg-3">
                <label class="py-2 fw-bold">Research Unit<span class="text-danger"> *</span></label>
            </div>
            <div class="col-lg-3 d-none d-lg-block">
                <label class="py-2 fw-bold">Researcher's Category<span class="text-danger"> *</span></label>
            </div> -->
            <div class="col-lg-3 d-none d-lg-block">
                <label class="py-2 fw-bold">Publication Date<span class="text-danger"> *</span></label>
            </div>
        </div>
        <div class="row">
            <!-- <div class="col-lg-3 col-sm-12 py-2">
                <select class="form-select" aria-label="Default select example" name="dropdownResearchUnit">
                    <option value="Basic Education" selected>Basic Education</option>
                    <option value="Senior High School">Senior High School</option>
                    <option value="Arts and Sciences">Arts and Sciences</option>
                    <option value="Business and Accountancy">Business and Accountancy</option>
                    <option value="Computer Studies">Computer Studies</option>
                    <option value="Criminology">Criminology</option>
                    <option value="Education">Education</option>
                    <option value="Engineering, Architecture and Aviation">Engineering, Architecture and Aviation</option>
                    <option value="Law">Law</option>
                    <option value="Maritime Education">Maritime Education</option>
                    <option value="International Hospitality Management">International Hospitality Management</option>
                    <option value="Graduate School">Graduate School</option>
                    <option value="Support Services">Support Services</option>
                </select>
            </div>
            <div class="col-lg-3 d-sm-block d-lg-none">
                <label class="py-2 fw-bold">Researcher's Category<span class="text-danger"> *</span></label>
            </div>
            <div class="col-lg-3 col-sm-12 py-2">
                <select class="form-select" aria-label="Default select example" name="dropdownResearchersCategory" id ="dropdownResearchersCategory">
                    <option value="undergraduate" selected>Undergraduate</option>
                    <option value="postgraduate">Postgraduate</option>
                    <option value="faculty">Faculty</option>
                    <option value="non_teaching_staff">Non-teaching Staff</option>
                    <option value="school_head">School Head</option>
                </select>
            </div> -->
            <div class="col-lg-3 d-sm-block d-lg-none">
                <label class="py-2 fw-bold">Publication Date<span class="text-danger"> *</span></label>
            </div>
            <div class="col-lg-4 col-sm-12 py-2">
                <input type="date" class="form-control" name="publication_date" value="<?= date('Y-m-d') ?>" required>
            </div>
        </div>

        <div class="row my-2">
            <label class="py-2 fw-bold">Corresponding Author<span class="text-danger"> *</span></label>
            <div class="col-lg-4 col-sm-12 py-2">
                <input type="text" class="form-control" name="textFieldAuthorFirstName" placeholder="First Name*" required>
            </div>
            <div class="col-lg-2 col-sm-12 py-2">
                <input type="text" class="form-control" name="textFieldAuthorMiddleInitial" placeholder="Middle Initial">
            </div>
            <div class="col-lg-4 col-sm-12 py-2">
                <input type="text" class="form-control" name="textFieldAuthorLastName" placeholder="Surname*" required>
            </div>
            <div class="col-lg-2 col-sm-12 py-2">
                <input type="text" class="form-control" name="textFieldAuthorNameExtension" placeholder="Extension">
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 col-sm-12">
                <label class="fw-bold">Email</label>
                <input type="text" class="form-control" name="textFieldEmail">
                <label class="text-secondary my-2">Consider your active email address</label>
            </div>
        </div>
        <div class="row my-2">
            <label class="py-2 fw-bold">Graphics Editor</label>
            <div class="col-lg-4 col-sm-12 py-2">
                <input type="text" class="form-control" name="textFieldGraphicsEditorFirstName" placeholder="First Name">
            </div>
            <div class="col-lg-2 col-sm-12 py-2">
                <input type="text" class="form-control" name="textFieldGraphicsEditorMiddleInitial" placeholder="Middle Initial">
            </div>
            <div class="col-lg-4 col-sm-12 py-2">
                <input type="text" class="form-control" name="textFieldGraphicsEditorLastName" placeholder="Surname">
            </div>
            <div class="col-lg-2 col-sm-12 py-2">
                <input type="text" class="form-control" name="textFieldGraphicsEditorNameExtension" placeholder="Extension">
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 col-sm-12">
                <label class="fw-bold">Email</label>
                <input type="text" class="form-control" name="textFieldGraphicsEditorEmail">
                <label class="text-secondary my-2">Consider your active email address</label>
            </div>
        </div>
        <div class="row my-3">
            <div class="col-lg-4 col-sm-12">
                <label class="fw-bold">No. of Co-Authors</label>
                <select id="dropdownInfographicsCoAuthors" class="form-select my-3" aria-label="Default select example" name="dropdownCoAuthors" onchange="showInfographicsCoAuthorsField();" required>
                    <option value="0" selected>0</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                </select>
                <label class="text-secondary">Max 4 co-authors allowed</label>
            </div>
        </div>
        <div class="row" id="co-author-1-info-panel">
            <label class="py-2 fw-bold">Co-Author 1</label>
            <div class="col-lg-4 col-sm-12 py-2">
                <input type="text" class="form-control" name="textFieldFirstNameCoAuthor1" placeholder="First Name*">
            </div>
            <div class="col-lg-2 col-sm-12 py-2">
                <input type="text" class="form-control" name="textFieldMiddleInitialCoAuthor1" placeholder="Middle Initial">
            </div>
            <div class="col-lg-4 col-sm-12 py-2">
                <input type="text" class="form-control" name="textFieldLastNameCoAuthor1" placeholder="Surname*">
            </div>
            <div class="col-lg-2 col-sm-12 py-2">
                <input type="text" class="form-control" name="textFieldNameExtCoAuthor1" placeholder="Extension">
            </div>
            <div class="col-lg-6 col-sm-12 my-2">
                <label class="fw-bold">Email</label>
                <input type="text" class="form-control" name="textFieldEmailAuthor1">
            </div>
        </div>
        <div class="row" id="co-author-2-info-panel">
            <label class="py-2 fw-bold">Co-Author 2</label>
            <div class="col-lg-4 col-sm-12 py-2">
                <input type="text" class="form-control" name="textFieldFirstNameCoAuthor2" placeholder="First Name*">
            </div>
            <div class="col-lg-2 col-sm-12 py-2">
                <input type="text" class="form-control" name="textFieldMiddleInitialCoAuthor2" placeholder="Middle Initial">
            </div>
            <div class="col-lg-4 col-sm-12 py-2">
                <input type="text" class="form-control" name="textFieldLastNameCoAuthor2" placeholder="Surname*">
            </div>
            <div class="col-lg-2 col-sm-12 py-2">
                <input type="text" class="form-control" name="textFieldNameExtCoAuthor2" placeholder="Extension">
            </div>
            <div class="col-lg-6 col-sm-12 my-2">
                <label class="fw-bold">Email</label>
                <input type="text" class="form-control" name="textFieldEmailAuthor2">
            </div>
        </div>
        <div class="row" id="co-author-3-info-panel">
            <label class="py-2 fw-bold">Co-Author 3</label>
            <div class="col-lg-4 col-sm-12 py-2">
                <input type="text" class="form-control" name="textFieldFirstNameCoAuthor3" placeholder="First Name*">
            </div>
            <div class="col-lg-2 col-sm-12 py-2">
                <input type="text" class="form-control" name="textFieldMiddleInitialCoAuthor3" placeholder="Middle Initial">
            </div>
            <div class="col-lg-4 col-sm-12 py-2">
                <input type="text" class="form-control" name="textFieldLastNameCoAuthor3" placeholder="Surname*">
            </div>
            <div class="col-lg-2 col-sm-12 py-2">
                <input type="text" class="form-control" name="textFieldNameExtCoAuthor3" placeholder="Extension">
            </div>
            <div class="col-lg-6 col-sm-12 my-2">
                <label class="fw-bold">Email</label>
                <input type="text" class="form-control" name="textFieldEmailAuthor3">
            </div>
        </div>
        <div class="row" id="co-author-4-info-panel">
            <label class="py-2 fw-bold">Co-Author 4</label>
            <div class="col-lg-4 col-sm-12 py-2">
                <input type="text" class="form-control" name="textFieldFirstNameCoAuthor4" placeholder="First Name*">
            </div>
            <div class="col-lg-2 col-sm-12 py-2">
                <input type="text" class="form-control" name="textFieldMiddleInitialCoAuthor4" placeholder="Middle Initial">
            </div>
            <div class="col-lg-4 col-sm-12 py-2">
                <input type="text" class="form-control" name="textFieldLastNameCoAuthor4" placeholder="Surname*">
            </div>
            <div class="col-lg-2 col-sm-12 py-2">
                <input type="text" class="form-control" name="textFieldNameExtCoAuthor4" placeholder="Extension">
            </div>
            <div class="col-lg-6 col-sm-12 my-2">
                <label class="fw-bold">Email</label>
                <input type="text" class="form-control" name="textFieldEmailAuthor4">
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="mb-2">
                    <label class="form-label fw-bold">Description<span class="text-danger"> *</span></label>
                    <textarea class="form-control" name="textareaDescription" rows="10" required></textarea>
                </div>
            </div>
        </div>
        <div class="row my-3">
            <label class="fw-bold mb-1">Attached Files</label>
            <div class="col">
                <p class="my-3">File1.pdf</p>
                <p class="text-danger remove-attachment"><i class="fas fa-trash-alt"></i> Remove attachment</p>
                <input class="form-control my-2" type="file" name="" accept=".pdf, .jpg, .jpeg, .png" required>

                <p class="my-3">File2.pdf</p>
                <p class="text-danger remove-attachment"><i class="fas fa-trash-alt"></i> Remove attachment</p>
                <input class="form-control my-2" type="file" name="" accept=".pdf, .jpg, .jpeg, .png" required>
            </div>
        </div>
        <hr>

        <div class="row">
            <div class="col">
                <a href="../../pages/users/my-submissions.php"><button type="button" class="btn btn-secondary button-cancel rounded-0" id="">Cancel</button></a>
                <button type="submit" class="btn btn-primary button-submit-research rounded-0" id="">Save</button>
            </div>
        </div>
    </form>
</div>
<script>
    var alertContainerInfographic = document.getElementById("alert-container-infographic")
    var form = document.forms.namedItem("infographic-form");

    function submitFormInfographic(event) {
        event.preventDefault();

        var formdata = new FormData(form);
        postInfographic(formdata).then(data => checkResponseInfographic(JSON.parse(data)));
        window.scrollTo(0, 0);
    }

    function postInfographic(data) {
        return new Promise((resolve, reject) => {
            var http = new XMLHttpRequest();
            http.open("POST", "../../process/infographic-submission.php");
            http.onload = () => http.status == 200 ? resolve(http.response) : reject(Error(http.statusText));
            http.onerror = (e) => reject(Error(`Networking error: ${e}`));
            http.send(data);

        });
    }

    function checkResponseInfographic(data) {
        if (data.response === "type_error") {
            alertContainerInfographic.innerHTML = `<div class="alert alert-danger alert-dismissible fade show" role="alert" id = "file-type-alert"><strong>File upload failed!</strong> Check to make sure the file is in <strong>PDF</strong> format, or that the file to be uploaded is attached.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>`;
        }
        if (data.response === "generic_error") {
            alertContainerInfographic.innerHTML = `<div class="alert alert-danger alert-dismissible fade show" role="alert" id = "file-type-alert"><strong>File upload failed!</strong> Check to make sure the file is <strong>less than 10 MB</strong> or that the file to be submitted is attached.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>`;
        }
        if (data.response === "size_error") {
            alertContainerInfographic.innerHTML = `<div class="alert alert-danger alert-dismissible fade show" role="alert" id = "file-type-alert"><strong>File upload failed!</strong> The file size is too large. The maximum allowed size is 10 MB.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>`;
        }
        if (data.response === "duplicate_error") {
            alertContainerInfographic.innerHTML = `<div class="alert alert-danger alert-dismissible fade show" role="alert" id = "file-type-alert"><strong>File upload failed!</strong> There is already a file with the same name uploaded to the database.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>`;
        }
        if (data.response === "success") {
            form.reset();
            alertContainerInfographic.innerHTML = `<div class="alert alert-success alert-dismissible fade show" role="alert"><strong>File upload success!</strong> Wait for your submission to be approved by the administration. You can view the submission status by checking <strong>My Submissions</strong> under <strong>My Profile</strong>.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>`;
        }

    }
</script>