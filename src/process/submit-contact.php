<?php

session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
    exit;
}

$name    = trim($_POST['name'] ?? '');
$email   = trim($_POST['email'] ?? '');
$subject = trim($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');

if ($name === '' || $email === '' || $subject === '' || $message === '') {
    echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid email address']);
    exit;
}

include '../../includes/connection.php';

if (mysqli_connect_errno()) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
    exit;
}

$stmt = mysqli_prepare($connection, 'INSERT INTO contact_submissions (name, email, subject, message) VALUES (?, ?, ?, ?)');
mysqli_stmt_bind_param($stmt, 'ssss', $name, $email, $subject, $message);

if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require '../../vendor/autoload.php';

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'saliksik@uphsl.edu.ph';
        $mail->Password   = 'iidx emoo fzsx pewg';
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        $mail->setFrom('saliksik@uphsl.edu.ph', 'SALIKSIK: UPHSL Research Repository');
        $mail->addAddress('saliksik@uphsl.edu.ph');
        $mail->isHTML(true);
        $mail->addEmbeddedImage(__DIR__ . '/../../android-chrome-256x256.png', 'saliksiklogo');

        $mail->Subject = '[SALIKSIK Contact Form] ' . htmlspecialchars($subject);
        $mail->Body    = '
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
                        </tr>
                        <tr>
                            <td style="padding:28px 32px 12px;">
                                <h2 style="margin:0 0 4px;font-size:16px;font-weight:700;color:#1f2937;">New Contact Form Submission</h2>
                                <p style="margin:0;font-size:13px;color:#64748b;">You received a new message through the contact form.</p>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:8px 32px 24px;">
                                <table width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #e6eaf2;border-radius:8px;overflow:hidden;font-size:13px;">
                                    <tr>
                                        <td style="padding:10px 14px;background:#eef3fc;font-weight:700;color:#0e408e;width:90px;vertical-align:top;">Name</td>
                                        <td style="padding:10px 14px;color:#1f2937;">' . htmlspecialchars($name) . '</td>
                                    </tr>
                                    <tr>
                                        <td style="padding:10px 14px;background:#eef3fc;font-weight:700;color:#0e408e;vertical-align:top;">Email</td>
                                        <td style="padding:10px 14px;color:#1f2937;"><a href="mailto:' . htmlspecialchars($email) . '" style="color:#0e408e;text-decoration:none;">' . htmlspecialchars($email) . '</a></td>
                                    </tr>
                                    <tr>
                                        <td style="padding:10px 14px;background:#eef3fc;font-weight:700;color:#0e408e;vertical-align:top;">Subject</td>
                                        <td style="padding:10px 14px;color:#1f2937;">' . htmlspecialchars($subject) . '</td>
                                    </tr>
                                    <tr>
                                        <td style="padding:10px 14px;background:#eef3fc;font-weight:700;color:#0e408e;vertical-align:top;">Message</td>
                                        <td style="padding:10px 14px;color:#1f2937;line-height:1.5;">' . nl2br(htmlspecialchars($message)) . '</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:0 32px 24px;">
                                <p style="margin:0;font-size:12px;color:#64748b;text-align:center;line-height:1.5;">This is a system generated message from the SALIKSIK contact form.<br>Do not reply directly to this email.</p>
                            </td>
                        </tr>
                        <tr>
                            <td style="background:#f4f6fb;padding:16px 32px;text-align:center;border-top:1px solid #e6eaf2;">
                                <p style="margin:0;font-size:11px;color:#64748b;">SALIKSIK: UPHSL Research Repository &middot; University of Perpetual Help System Laguna</p>
                            </td>
                        </tr>
                    </table>
                </td></tr>
            </table>
        </div>';
        $mail->AltBody = "New Contact Form Submission\nName: $name\nEmail: $email\nSubject: $subject\nMessage: $message";
        $mail->send();
    } catch (Exception $e) {
        // Email failed but submission was saved
    }

    echo json_encode(['status' => 'success', 'message' => 'Message sent successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to save message']);
}

mysqli_close($connection);

?>