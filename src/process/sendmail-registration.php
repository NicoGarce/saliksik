<?php
// EMAIL SEND FOR ACCOUNT RELATED SUCH AS LOGIN, FORGOT PASSWORD

if (!isset($_SESSION['email'])) {
    header('Location: ../../error.php');
    exit();
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../../vendor/autoload.php';

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
    $mail->addEmbeddedImage(__DIR__ . '/../../android-chrome-256x256.png', 'saliksiklogo');

    $verificationCode = uniqid();
    $_SESSION['verificationCode'] = strtoupper(substr($verificationCode, 7));
    $recipient = $_SESSION['email'];

    $mail->addAddress($recipient);
    $mail->Subject = '[SALIKSIK] Verify Your Account';
    $mail->Body = '
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
                        <td style="padding:28px 32px 8px;">
                            <h2 style="margin:0;font-size:16px;font-weight:700;color:#1f2937;">Verify Your Account</h2>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:8px 32px 20px;">
                            <p style="margin:0;font-size:13px;color:#64748b;line-height:1.6;">Hello ' . htmlspecialchars($_SESSION['firstname'] . ' ' . $_SESSION['lastname']) . ',</p>
                            <p style="margin:12px 0 0;font-size:13px;color:#64748b;line-height:1.6;">A registration attempt was made using <strong style="color:#1f2937;">' . htmlspecialchars($recipient) . '</strong>. Enter the code below to verify your account.</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:0 32px 24px;" align="center">
                            <div style="background:#eef3fc;border:2px dashed #0e408e;border-radius:10px;padding:20px 32px;text-align:center;display:inline-block;">
                                <p style="margin:0 0 6px;font-size:11px;font-weight:700;color:#0e408e;text-transform:uppercase;letter-spacing:.06em;">Your Verification Code</p>
                                <p style="margin:0;font-size:32px;font-weight:800;color:#012265;letter-spacing:.15em;">' . $_SESSION['verificationCode'] . '</p>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:0 32px 24px;">
                            <p style="margin:0;font-size:12px;color:#64748b;line-height:1.5;">If you did not attempt to register, you can safely ignore this email. The registration will be cancelled and your email will not be used.</p>
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
    $mail->AltBody = "Verify Your Account\nHello " . $_SESSION['firstname'] . " " . $_SESSION['lastname'] . "\nYour verification code: " . $_SESSION['verificationCode'] . "\nIf you did not attempt to register, ignore this message.";

    $mail->send();
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}