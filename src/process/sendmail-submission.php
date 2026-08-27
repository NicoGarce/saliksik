<?php 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/autoload.php';

function salikEmailHeader($mail) {
    $mail->addEmbeddedImage(__DIR__ . '/../../android-chrome-256x256.png', 'saliksiklogo');
    return '
    <div style="margin:0;padding:0;background:#f4f6fb;font-family:Arial,Helvetica,sans-serif;">
        <table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f6fb;padding:32px 16px;">
            <tr><td align="center">
                <table width="100%" cellpadding="0" cellspacing="0" style="max-width:560px;background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 2px 12px rgba(1,34,101,.08);">
                    <tr>
                        <td style="background:linear-gradient(135deg,#012265,#0e408e);padding:24px 32px;">
                            <table cellpadding="0" cellspacing="0" style="width:100%;">
                                <tr>
                                    <td style="width:44px;vertical-align:middle;">
                                        <img src="cid:saliksiklogo" alt="" style="width:40px;height:40px;border-radius:8px;display:block;">
                                    </td>
                                    <td style="padding-left:12px;vertical-align:middle;">
                                        <h1 style="margin:0;font-size:20px;font-weight:800;color:#ffffff;letter-spacing:-.02em;line-height:1;">SALIKSIK</h1>
                                        <p style="margin:2px 0 0;font-size:10px;color:rgba(255,255,255,.65);letter-spacing:.04em;text-transform:uppercase;">UPHSL Research Repository</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>';
}

function salikSubmissionTable() {
    return '
                    <tr>
                        <td style="padding:12px 32px 20px;">
                            <table width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #e6eaf2;border-radius:8px;overflow:hidden;font-size:13px;">
                                <tr>
                                    <td style="padding:10px 14px;background:#eef3fc;font-weight:700;color:#0e408e;width:120px;vertical-align:top;">Title</td>
                                    <td style="padding:10px 14px;color:#1f2937;">' . $_POST['textFieldResearchTitle'] . '</td>
                                </tr>
                                <tr>
                                    <td style="padding:10px 14px;background:#eef3fc;font-weight:700;color:#0e408e;vertical-align:top;">Resource Type</td>
                                    <td style="padding:10px 14px;color:#1f2937;">' . $_POST['dropdownResourceType'] . '</td>
                                </tr>
                                <tr>
                                    <td style="padding:10px 14px;background:#eef3fc;font-weight:700;color:#0e408e;vertical-align:top;">Author/s</td>
                                    <td style="padding:10px 14px;color:#1f2937;">' . $_POST['textFieldAuthorFirstName'] . ' ' . $_POST['textFieldAuthorLastName'] . '</td>
                                </tr>
                                <tr>
                                    <td style="padding:10px 14px;background:#eef3fc;font-weight:700;color:#0e408e;vertical-align:top;">Category</td>
                                    <td style="padding:10px 14px;color:#1f2937;">' . $_POST['dropdownResearchersCategory'] . '</td>
                                </tr>
                                <tr>
                                    <td style="padding:10px 14px;background:#eef3fc;font-weight:700;color:#0e408e;vertical-align:top;">Research Unit</td>
                                    <td style="padding:10px 14px;color:#1f2937;">' . $_POST['dropdownResearchUnit'] . '</td>
                                </tr>
                                <tr>
                                    <td style="padding:10px 14px;background:#eef3fc;font-weight:700;color:#0e408e;vertical-align:top;">Attached Files</td>
                                    <td style="padding:10px 14px;color:#1f2937;">' . $_SESSION['manuscriptFileNameForEmail'] . ', ' . $_SESSION['questionnaireFileNameForEmail'] . '</td>
                                </tr>
                            </table>
                        </td>
                    </tr>';
}

function salikEmailFooter() {
    return '
                    <tr>
                        <td style="padding:0 32px 24px;">
                            <p style="margin:0;font-size:12px;color:#64748b;line-height:1.5;">For any queries, email us at <a href="mailto:saliksik@uphsl.edu.ph" style="color:#0e408e;text-decoration:none;">saliksik@uphsl.edu.ph</a> or check <strong>My Submissions</strong> under your profile.</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:0 32px 24px;">
                            <p style="margin:0;font-size:12px;color:#64748b;line-height:1.5;">Thanks,<br><strong style="color:#0e408e;">The SALIKSIK Team</strong></p>
                        </td>
                    </tr>
                    <tr>
                        <td style="background:#f4f6fb;padding:16px 32px;text-align:center;border-top:1px solid #e6eaf2;">
                            <p style="margin:0;font-size:11px;color:#64748b;">This is a system generated message. Do not reply.<br>SALIKSIK: UPHSL Research Repository</p>
                        </td>
                    </tr>
                </table>
            </td></tr>
        </table>
    </div>';
}

function sendMailSubmit()
{
    $mail = new PHPMailer(true);

    try {
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'saliksik@uphsl.edu.ph';
        $mail->Password = 'iidx emoo fzsx pewg';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
        $mail->setFrom('saliksik@uphsl.edu.ph', 'SALIKSIK: UPHSL Research Repository');
        $mail->isHTML(true);
        $mail->Subject = "[SALIKSIK] Manuscript Received";

        if (empty($_POST['textFieldEmailAuthor1'] || $_POST['textFieldEmailAuthor2'] || $_POST['textFieldEmailAuthor3'] || $_POST['textFieldEmailAuthor4'])) {
            $mail->addAddress($_POST['textFieldEmail']);
            $mail->Body = salikEmailHeader($mail) . '
                    <tr><td style="padding:28px 32px 8px;"><h2 style="margin:0;font-size:16px;font-weight:700;color:#1f2937;">Manuscript Received</h2></td></tr>
                    <tr><td style="padding:8px 32px 20px;"><p style="margin:0;font-size:13px;color:#64748b;line-height:1.6;">Dear ' . $_POST['textFieldAuthorFirstName'] . ' ' . $_POST['textFieldAuthorLastName'] . ',</p>
                    <p style="margin:12px 0 0;font-size:13px;color:#64748b;line-height:1.6;">We have received your manuscript. Please check the submission details below:</p></td></tr>' . salikSubmissionTable() . salikEmailFooter();
            $mail->send();
        } else if (empty($_POST['textFieldEmailAuthor2'] || $_POST['textFieldEmailAuthor3'] || $_POST['textFieldEmailAuthor4'])) {
            $mail->addAddress($_POST['textFieldEmail']);
            $mail->addAddress($_POST['textFieldEmailAuthor1']);
            $mail->Body = salikEmailHeader($mail) . '
                    <tr><td style="padding:28px 32px 8px;"><h2 style="margin:0;font-size:16px;font-weight:700;color:#1f2937;">Manuscript Received</h2></td></tr>
                    <tr><td style="padding:8px 32px 20px;"><p style="margin:0;font-size:13px;color:#64748b;line-height:1.6;">Dear ' . $_POST['textFieldAuthorFirstName'] . ' ' . $_POST['textFieldAuthorLastName'] . ', ' . $_POST['textFieldFirstNameCoAuthor1'] . ' ' . $_POST['textFieldLastNameCoAuthor1'] . ',</p>
                    <p style="margin:12px 0 0;font-size:13px;color:#64748b;line-height:1.6;">We have received your manuscript. Please check the submission details below:</p></td></tr>' . salikSubmissionTable() . salikEmailFooter();
            $mail->send();
        } else if (empty($_POST['textFieldEmailAuthor3'] || $_POST['textFieldEmailAuthor4'])) {
            $mail->addAddress($_POST['textFieldEmail']);
            $mail->addAddress($_POST['textFieldEmailAuthor1']);
            $mail->addAddress($_POST['textFieldEmailAuthor2']);
            $mail->Body = salikEmailHeader($mail) . '
                    <tr><td style="padding:28px 32px 8px;"><h2 style="margin:0;font-size:16px;font-weight:700;color:#1f2937;">Manuscript Received</h2></td></tr>
                    <tr><td style="padding:8px 32px 20px;"><p style="margin:0;font-size:13px;color:#64748b;line-height:1.6;">Dear ' . $_POST['textFieldAuthorFirstName'] . ' ' . $_POST['textFieldAuthorLastName'] . ', ' . $_POST['textFieldFirstNameCoAuthor1'] . ' ' . $_POST['textFieldLastNameCoAuthor1'] . ', ' . $_POST['textFieldFirstNameCoAuthor2'] . ' ' . $_POST['textFieldLastNameCoAuthor2'] . ',</p>
                    <p style="margin:12px 0 0;font-size:13px;color:#64748b;line-height:1.6;">We have received your manuscript. Please check the submission details below:</p></td></tr>' . salikSubmissionTable() . salikEmailFooter();
            $mail->send();
        } else if (empty($_POST['textFieldEmailAuthor4'])) {
            $mail->addAddress($_POST['textFieldEmail']);
            $mail->addAddress($_POST['textFieldEmailAuthor1']);
            $mail->addAddress($_POST['textFieldEmailAuthor2']);
            $mail->addAddress($_POST['textFieldEmailAuthor3']);
            $mail->Body = salikEmailHeader($mail) . '
                    <tr><td style="padding:28px 32px 8px;"><h2 style="margin:0;font-size:16px;font-weight:700;color:#1f2937;">Manuscript Received</h2></td></tr>
                    <tr><td style="padding:8px 32px 20px;"><p style="margin:0;font-size:13px;color:#64748b;line-height:1.6;">Dear ' . $_POST['textFieldAuthorFirstName'] . ' ' . $_POST['textFieldAuthorLastName'] . ', ' . $_POST['textFieldFirstNameCoAuthor1'] . ' ' . $_POST['textFieldLastNameCoAuthor1'] . ', ' . $_POST['textFieldFirstNameCoAuthor2'] . ' ' . $_POST['textFieldLastNameCoAuthor2'] . ', ' . $_POST['textFieldFirstNameCoAuthor3'] . ' ' . $_POST['textFieldLastNameCoAuthor3'] . ',</p>
                    <p style="margin:12px 0 0;font-size:13px;color:#64748b;line-height:1.6;">We have received your manuscript. Please check the submission details below:</p></td></tr>' . salikSubmissionTable() . salikEmailFooter();
            $mail->send();
        } else {
            $mail->addAddress($_POST['textFieldEmail']);
            $mail->addAddress($_POST['textFieldEmailAuthor1']);
            $mail->addAddress($_POST['textFieldEmailAuthor2']);
            $mail->addAddress($_POST['textFieldEmailAuthor3']);
            $mail->addAddress($_POST['textFieldEmailAuthor4']);
            $mail->Body = salikEmailHeader($mail) . '
                    <tr><td style="padding:28px 32px 8px;"><h2 style="margin:0;font-size:16px;font-weight:700;color:#1f2937;">Manuscript Received</h2></td></tr>
                    <tr><td style="padding:8px 32px 20px;"><p style="margin:0;font-size:13px;color:#64748b;line-height:1.6;">Dear ' . $_POST['textFieldAuthorFirstName'] . ' ' . $_POST['textFieldAuthorLastName'] . ', ' . $_POST['textFieldFirstNameCoAuthor1'] . ' ' . $_POST['textFieldLastNameCoAuthor1'] . ', ' . $_POST['textFieldFirstNameCoAuthor2'] . ' ' . $_POST['textFieldLastNameCoAuthor2'] . ', ' . $_POST['textFieldFirstNameCoAuthor3'] . ' ' . $_POST['textFieldLastNameCoAuthor3'] . ', ' . $_POST['textFieldFirstNameCoAuthor4'] . ' ' . $_POST['textFieldLastNameCoAuthor4'] . ',</p>
                    <p style="margin:12px 0 0;font-size:13px;color:#64748b;line-height:1.6;">We have received your manuscript. Please check the submission details below:</p></td></tr>' . salikSubmissionTable() . salikEmailFooter();
            $mail->send();
        }
    } catch (Exception $e) {
        error_log("Mail error: " . $mail->ErrorInfo);
    }
}

function sendMailPublished() {
    $mail = new PHPMailer(true);

    try {
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'saliksik@uphsl.edu.ph';
        $mail->Password = 'iidx emoo fzsx pewg';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
        $mail->setFrom('saliksik@uphsl.edu.ph', 'SALIKSIK: UPHSL Research Repository');
        $mail->isHTML(true);
        $mail->Subject = "[SALIKSIK] Manuscript Published";

        if (empty($_POST['textFieldEmailAuthor1'] || $_POST['textFieldEmailAuthor2'] || $_POST['textFieldEmailAuthor3'] || $_POST['textFieldEmailAuthor4'])) {
            $mail->addAddress($_POST['textFieldEmail']);
            $mail->Body = salikEmailHeader($mail) . '
                    <tr><td style="padding:28px 32px 8px;"><h2 style="margin:0;font-size:16px;font-weight:700;color:#1f2937;">Manuscript Published</h2></td></tr>
                    <tr><td style="padding:8px 32px 20px;"><p style="margin:0;font-size:13px;color:#64748b;line-height:1.6;">Dear ' . $_POST['textFieldAuthorFirstName'] . ' ' . $_POST['textFieldAuthorLastName'] . ',</p>
                    <p style="margin:12px 0 0;font-size:13px;color:#64748b;line-height:1.6;">Your <strong style="color:#1f2937;">' . $_POST['dropdownResourceType'] . '</strong> submission entitled <strong style="color:#1f2937;">' . $_POST['textFieldResearchTitle'] . '</strong> has been approved and published in the SALIKSIK repository.</p></td></tr>
                    <tr><td style="padding:0 32px 24px;" align="center"><a href="https://www.saliksik-uphsl.com/repository/view-article.php?id=' . $_POST['fileId'] . '" style="display:inline-block;background:#012265;color:#ffffff;font-size:13px;font-weight:700;text-decoration:none;padding:12px 32px;border-radius:8px;">View Published Work</a></td></tr>' . salikEmailFooter();
            $mail->send();
        } else if (empty($_POST['textFieldEmailAuthor2'] || $_POST['textFieldEmailAuthor3'] || $_POST['textFieldEmailAuthor4'])) {
            $mail->addAddress($_POST['textFieldEmail']);
            $mail->addAddress($_POST['textFieldEmailAuthor1']);
            $mail->Body = salikEmailHeader($mail) . '
                    <tr><td style="padding:28px 32px 8px;"><h2 style="margin:0;font-size:16px;font-weight:700;color:#1f2937;">Manuscript Published</h2></td></tr>
                    <tr><td style="padding:8px 32px 20px;"><p style="margin:0;font-size:13px;color:#64748b;line-height:1.6;">Dear ' . $_POST['textFieldAuthorFirstName'] . ' ' . $_POST['textFieldAuthorLastName'] . ', ' . $_POST['textFieldFirstNameCoAuthor1'] . ' ' . $_POST['textFieldLastNameCoAuthor1'] . ',</p>
                    <p style="margin:12px 0 0;font-size:13px;color:#64748b;line-height:1.6;">Your <strong style="color:#1f2937;">' . $_POST['dropdownResourceType'] . '</strong> submission entitled <strong style="color:#1f2937;">' . $_POST['textFieldResearchTitle'] . '</strong> has been approved and published in the SALIKSIK repository.</p></td></tr>
                    <tr><td style="padding:0 32px 24px;" align="center"><a href="https://www.saliksik-uphsl.com/repository/view-article.php?id=' . $_POST['fileId'] . '" style="display:inline-block;background:#012265;color:#ffffff;font-size:13px;font-weight:700;text-decoration:none;padding:12px 32px;border-radius:8px;">View Published Work</a></td></tr>' . salikEmailFooter();
            $mail->send();
        } else if (empty($_POST['textFieldEmailAuthor3'] || $_POST['textFieldEmailAuthor4'])) {
            $mail->addAddress($_POST['textFieldEmail']);
            $mail->addAddress($_POST['textFieldEmailAuthor1']);
            $mail->addAddress($_POST['textFieldEmailAuthor2']);
            $mail->Body = salikEmailHeader($mail) . '
                    <tr><td style="padding:28px 32px 8px;"><h2 style="margin:0;font-size:16px;font-weight:700;color:#1f2937;">Manuscript Published</h2></td></tr>
                    <tr><td style="padding:8px 32px 20px;"><p style="margin:0;font-size:13px;color:#64748b;line-height:1.6;">Dear ' . $_POST['textFieldAuthorFirstName'] . ' ' . $_POST['textFieldAuthorLastName'] . ', ' . $_POST['textFieldFirstNameCoAuthor1'] . ' ' . $_POST['textFieldLastNameCoAuthor1'] . ', ' . $_POST['textFieldFirstNameCoAuthor2'] . ' ' . $_POST['textFieldLastNameCoAuthor2'] . ',</p>
                    <p style="margin:12px 0 0;font-size:13px;color:#64748b;line-height:1.6;">Your <strong style="color:#1f2937;">' . $_POST['dropdownResourceType'] . '</strong> submission entitled <strong style="color:#1f2937;">' . $_POST['textFieldResearchTitle'] . '</strong> has been approved and published in the SALIKSIK repository.</p></td></tr>
                    <tr><td style="padding:0 32px 24px;" align="center"><a href="https://www.saliksik-uphsl.com/repository/view-article.php?id=' . $_POST['fileId'] . '" style="display:inline-block;background:#012265;color:#ffffff;font-size:13px;font-weight:700;text-decoration:none;padding:12px 32px;border-radius:8px;">View Published Work</a></td></tr>' . salikEmailFooter();
            $mail->send();
        } else if (empty($_POST['textFieldEmailAuthor4'])) {
            $mail->addAddress($_POST['textFieldEmail']);
            $mail->addAddress($_POST['textFieldEmailAuthor1']);
            $mail->addAddress($_POST['textFieldEmailAuthor2']);
            $mail->addAddress($_POST['textFieldEmailAuthor3']);
            $mail->Body = salikEmailHeader($mail) . '
                    <tr><td style="padding:28px 32px 8px;"><h2 style="margin:0;font-size:16px;font-weight:700;color:#1f2937;">Manuscript Published</h2></td></tr>
                    <tr><td style="padding:8px 32px 20px;"><p style="margin:0;font-size:13px;color:#64748b;line-height:1.6;">Dear ' . $_POST['textFieldAuthorFirstName'] . ' ' . $_POST['textFieldAuthorLastName'] . ', ' . $_POST['textFieldFirstNameCoAuthor1'] . ' ' . $_POST['textFieldLastNameCoAuthor1'] . ', ' . $_POST['textFieldFirstNameCoAuthor2'] . ' ' . $_POST['textFieldLastNameCoAuthor2'] . ', ' . $_POST['textFieldFirstNameCoAuthor3'] . ' ' . $_POST['textFieldLastNameCoAuthor3'] . ',</p>
                    <p style="margin:12px 0 0;font-size:13px;color:#64748b;line-height:1.6;">Your <strong style="color:#1f2937;">' . $_POST['dropdownResourceType'] . '</strong> submission entitled <strong style="color:#1f2937;">' . $_POST['textFieldResearchTitle'] . '</strong> has been approved and published in the SALIKSIK repository.</p></td></tr>
                    <tr><td style="padding:0 32px 24px;" align="center"><a href="https://www.saliksik-uphsl.com/repository/view-article.php?id=' . $_POST['fileId'] . '" style="display:inline-block;background:#012265;color:#ffffff;font-size:13px;font-weight:700;text-decoration:none;padding:12px 32px;border-radius:8px;">View Published Work</a></td></tr>' . salikEmailFooter();
            $mail->send();
        } else {
            $mail->addAddress($_POST['textFieldEmail']);
            $mail->addAddress($_POST['textFieldEmailAuthor1']);
            $mail->addAddress($_POST['textFieldEmailAuthor2']);
            $mail->addAddress($_POST['textFieldEmailAuthor3']);
            $mail->addAddress($_POST['textFieldEmailAuthor4']);
            $mail->Body = salikEmailHeader($mail) . '
                    <tr><td style="padding:28px 32px 8px;"><h2 style="margin:0;font-size:16px;font-weight:700;color:#1f2937;">Manuscript Published</h2></td></tr>
                    <tr><td style="padding:8px 32px 20px;"><p style="margin:0;font-size:13px;color:#64748b;line-height:1.6;">Dear ' . $_POST['textFieldAuthorFirstName'] . ' ' . $_POST['textFieldAuthorLastName'] . ', ' . $_POST['textFieldFirstNameCoAuthor1'] . ' ' . $_POST['textFieldLastNameCoAuthor1'] . ', ' . $_POST['textFieldFirstNameCoAuthor2'] . ' ' . $_POST['textFieldLastNameCoAuthor2'] . ', ' . $_POST['textFieldFirstNameCoAuthor3'] . ' ' . $_POST['textFieldLastNameCoAuthor3'] . ', ' . $_POST['textFieldFirstNameCoAuthor4'] . ' ' . $_POST['textFieldLastNameCoAuthor4'] . ',</p>
                    <p style="margin:12px 0 0;font-size:13px;color:#64748b;line-height:1.6;">Your <strong style="color:#1f2937;">' . $_POST['dropdownResourceType'] . '</strong> submission entitled <strong style="color:#1f2937;">' . $_POST['textFieldResearchTitle'] . '</strong> has been approved and published in the SALIKSIK repository.</p></td></tr>
                    <tr><td style="padding:0 32px 24px;" align="center"><a href="https://www.saliksik-uphsl.com/repository/view-article.php?id=' . $_POST['fileId'] . '" style="display:inline-block;background:#012265;color:#ffffff;font-size:13px;font-weight:700;text-decoration:none;padding:12px 32px;border-radius:8px;">View Published Work</a></td></tr>' . salikEmailFooter();
            $mail->send();
        }
    } catch (Exception $e) {
        error_log("Mail error: " . $mail->ErrorInfo);
    }
}

function sendMailReturned() {
    $mail = new PHPMailer(true);

    try {
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'saliksik@uphsl.edu.ph';
        $mail->Password = 'iidx emoo fzsx pewg';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
        $mail->setFrom('saliksik@uphsl.edu.ph', 'SALIKSIK: UPHSL Research Repository');
        $mail->isHTML(true);
        $mail->Subject = "[SALIKSIK] Manuscript Returned";

        if (empty($_POST['textFieldEmailAuthor1'] || $_POST['textFieldEmailAuthor2'] || $_POST['textFieldEmailAuthor3'] || $_POST['textFieldEmailAuthor4'])) {
            $mail->addAddress($_POST['textFieldEmail']);
            $mail->Body = salikEmailHeader($mail) . '
                    <tr><td style="padding:28px 32px 8px;"><h2 style="margin:0;font-size:16px;font-weight:700;color:#1f2937;">Manuscript Returned</h2></td></tr>
                    <tr><td style="padding:8px 32px 20px;"><p style="margin:0;font-size:13px;color:#64748b;line-height:1.6;">Dear ' . $_POST['textFieldAuthorFirstName'] . ' ' . $_POST['textFieldAuthorLastName'] . ',</p>
                    <p style="margin:12px 0 0;font-size:13px;color:#64748b;line-height:1.6;">Your <strong style="color:#1f2937;">' . $_POST['dropdownResourceType'] . '</strong> submission entitled <strong style="color:#1f2937;">' . $_POST['textFieldResearchTitle'] . '</strong> has been reviewed by the Research and Development Center.</p>
                    <p style="margin:12px 0 0;font-size:13px;color:#64748b;line-height:1.6;">Please follow the feedback accordingly:</p></td></tr>
                    <tr><td style="padding:0 32px 20px;"><div style="background:#eef3fc;border-left:3px solid #0e408e;padding:12px 16px;border-radius:0 6px 6px 0;"><p style="margin:0;font-size:13px;color:#1f2937;font-style:italic;line-height:1.6;">' . $_POST['textAreaFeedbackThesis'] . '</div></td></tr>
                    <tr><td style="padding:0 32px 24px;"><p style="margin:0;font-size:13px;color:#64748b;line-height:1.6;">Once done, please resubmit your work.</p></td></tr>' . salikEmailFooter();
            $mail->send();
        } else if (empty($_POST['textFieldEmailAuthor2'] || $_POST['textFieldEmailAuthor3'] || $_POST['textFieldEmailAuthor4'])) {
            $mail->addAddress($_POST['textFieldEmail']);
            $mail->addAddress($_POST['textFieldEmailAuthor1']);
            $mail->Body = salikEmailHeader($mail) . '
                    <tr><td style="padding:28px 32px 8px;"><h2 style="margin:0;font-size:16px;font-weight:700;color:#1f2937;">Manuscript Returned</h2></td></tr>
                    <tr><td style="padding:8px 32px 20px;"><p style="margin:0;font-size:13px;color:#64748b;line-height:1.6;">Dear ' . $_POST['textFieldAuthorFirstName'] . ' ' . $_POST['textFieldAuthorLastName'] . ', ' . $_POST['textFieldFirstNameCoAuthor1'] . ' ' . $_POST['textFieldLastNameCoAuthor1'] . ',</p>
                    <p style="margin:12px 0 0;font-size:13px;color:#64748b;line-height:1.6;">Your <strong style="color:#1f2937;">' . $_POST['dropdownResourceType'] . '</strong> submission entitled <strong style="color:#1f2937;">' . $_POST['textFieldResearchTitle'] . '</strong> has been reviewed by the Research and Development Center.</p>
                    <p style="margin:12px 0 0;font-size:13px;color:#64748b;line-height:1.6;">Please follow the feedback accordingly:</p></td></tr>
                    <tr><td style="padding:0 32px 20px;"><div style="background:#eef3fc;border-left:3px solid #0e408e;padding:12px 16px;border-radius:0 6px 6px 0;"><p style="margin:0;font-size:13px;color:#1f2937;font-style:italic;line-height:1.6;">' . $_POST['textAreaFeedbackThesis'] . '</div></td></tr>
                    <tr><td style="padding:0 32px 24px;"><p style="margin:0;font-size:13px;color:#64748b;line-height:1.6;">Once done, please resubmit your work.</p></td></tr>' . salikEmailFooter();
            $mail->send();
        } else if (empty($_POST['textFieldEmailAuthor3'] || $_POST['textFieldEmailAuthor4'])) {
            $mail->addAddress($_POST['textFieldEmail']);
            $mail->addAddress($_POST['textFieldEmailAuthor1']);
            $mail->addAddress($_POST['textFieldEmailAuthor2']);
            $mail->Body = salikEmailHeader($mail) . '
                    <tr><td style="padding:28px 32px 8px;"><h2 style="margin:0;font-size:16px;font-weight:700;color:#1f2937;">Manuscript Returned</h2></td></tr>
                    <tr><td style="padding:8px 32px 20px;"><p style="margin:0;font-size:13px;color:#64748b;line-height:1.6;">Dear ' . $_POST['textFieldAuthorFirstName'] . ' ' . $_POST['textFieldAuthorLastName'] . ', ' . $_POST['textFieldFirstNameCoAuthor1'] . ' ' . $_POST['textFieldLastNameCoAuthor1'] . ', ' . $_POST['textFieldFirstNameCoAuthor2'] . ' ' . $_POST['textFieldLastNameCoAuthor2'] . ',</p>
                    <p style="margin:12px 0 0;font-size:13px;color:#64748b;line-height:1.6;">Your <strong style="color:#1f2937;">' . $_POST['dropdownResourceType'] . '</strong> submission entitled <strong style="color:#1f2937;">' . $_POST['textFieldResearchTitle'] . '</strong> has been reviewed by the Research and Development Center.</p>
                    <p style="margin:12px 0 0;font-size:13px;color:#64748b;line-height:1.6;">Please follow the feedback accordingly:</p></td></tr>
                    <tr><td style="padding:0 32px 20px;"><div style="background:#eef3fc;border-left:3px solid #0e408e;padding:12px 16px;border-radius:0 6px 6px 0;"><p style="margin:0;font-size:13px;color:#1f2937;font-style:italic;line-height:1.6;">' . $_POST['textAreaFeedbackThesis'] . '</div></td></tr>
                    <tr><td style="padding:0 32px 24px;"><p style="margin:0;font-size:13px;color:#64748b;line-height:1.6;">Once done, please resubmit your work.</p></td></tr>' . salikEmailFooter();
            $mail->send();
        } else if (empty($_POST['textFieldEmailAuthor4'])) {
            $mail->addAddress($_POST['textFieldEmail']);
            $mail->addAddress($_POST['textFieldEmailAuthor1']);
            $mail->addAddress($_POST['textFieldEmailAuthor2']);
            $mail->addAddress($_POST['textFieldEmailAuthor3']);
            $mail->Body = salikEmailHeader($mail) . '
                    <tr><td style="padding:28px 32px 8px;"><h2 style="margin:0;font-size:16px;font-weight:700;color:#1f2937;">Manuscript Returned</h2></td></tr>
                    <tr><td style="padding:8px 32px 20px;"><p style="margin:0;font-size:13px;color:#64748b;line-height:1.6;">Dear ' . $_POST['textFieldAuthorFirstName'] . ' ' . $_POST['textFieldAuthorLastName'] . ', ' . $_POST['textFieldFirstNameCoAuthor1'] . ' ' . $_POST['textFieldLastNameCoAuthor1'] . ', ' . $_POST['textFieldFirstNameCoAuthor2'] . ' ' . $_POST['textFieldLastNameCoAuthor2'] . ', ' . $_POST['textFieldFirstNameCoAuthor3'] . ' ' . $_POST['textFieldLastNameCoAuthor3'] . ',</p>
                    <p style="margin:12px 0 0;font-size:13px;color:#64748b;line-height:1.6;">Your <strong style="color:#1f2937;">' . $_POST['dropdownResourceType'] . '</strong> submission entitled <strong style="color:#1f2937;">' . $_POST['textFieldResearchTitle'] . '</strong> has been reviewed by the Research and Development Center.</p>
                    <p style="margin:12px 0 0;font-size:13px;color:#64748b;line-height:1.6;">Please follow the feedback accordingly:</p></td></tr>
                    <tr><td style="padding:0 32px 20px;"><div style="background:#eef3fc;border-left:3px solid #0e408e;padding:12px 16px;border-radius:0 6px 6px 0;"><p style="margin:0;font-size:13px;color:#1f2937;font-style:italic;line-height:1.6;">' . $_POST['textAreaFeedbackThesis'] . '</div></td></tr>
                    <tr><td style="padding:0 32px 24px;"><p style="margin:0;font-size:13px;color:#64748b;line-height:1.6;">Once done, please resubmit your work.</p></td></tr>' . salikEmailFooter();
            $mail->send();
        } else {
            $mail->addAddress($_POST['textFieldEmail']);
            $mail->addAddress($_POST['textFieldEmailAuthor1']);
            $mail->addAddress($_POST['textFieldEmailAuthor2']);
            $mail->addAddress($_POST['textFieldEmailAuthor3']);
            $mail->addAddress($_POST['textFieldEmailAuthor4']);
            $mail->Body = salikEmailHeader($mail) . '
                    <tr><td style="padding:28px 32px 8px;"><h2 style="margin:0;font-size:16px;font-weight:700;color:#1f2937;">Manuscript Returned</h2></td></tr>
                    <tr><td style="padding:8px 32px 20px;"><p style="margin:0;font-size:13px;color:#64748b;line-height:1.6;">Dear ' . $_POST['textFieldAuthorFirstName'] . ' ' . $_POST['textFieldAuthorLastName'] . ', ' . $_POST['textFieldFirstNameCoAuthor1'] . ' ' . $_POST['textFieldLastNameCoAuthor1'] . ', ' . $_POST['textFieldFirstNameCoAuthor2'] . ' ' . $_POST['textFieldLastNameCoAuthor2'] . ', ' . $_POST['textFieldFirstNameCoAuthor3'] . ' ' . $_POST['textFieldLastNameCoAuthor3'] . ', ' . $_POST['textFieldFirstNameCoAuthor4'] . ' ' . $_POST['textFieldLastNameCoAuthor4'] . ',</p>
                    <p style="margin:12px 0 0;font-size:13px;color:#64748b;line-height:1.6;">Your <strong style="color:#1f2937;">' . $_POST['dropdownResourceType'] . '</strong> submission entitled <strong style="color:#1f2937;">' . $_POST['textFieldResearchTitle'] . '</strong> has been reviewed by the Research and Development Center.</p>
                    <p style="margin:12px 0 0;font-size:13px;color:#64748b;line-height:1.6;">Please follow the feedback accordingly:</p></td></tr>
                    <tr><td style="padding:0 32px 20px;"><div style="background:#eef3fc;border-left:3px solid #0e408e;padding:12px 16px;border-radius:0 6px 6px 0;"><p style="margin:0;font-size:13px;color:#1f2937;font-style:italic;line-height:1.6;">' . $_POST['textAreaFeedbackThesis'] . '</div></td></tr>
                    <tr><td style="padding:0 32px 24px;"><p style="margin:0;font-size:13px;color:#64748b;line-height:1.6;">Once done, please resubmit your work.</p></td></tr>' . salikEmailFooter();
            $mail->send();
        }
    } catch (Exception $e) {
        error_log("Mail error: " . $mail->ErrorInfo);
    }
}