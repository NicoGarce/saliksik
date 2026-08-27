<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './vendor/autoload.php';

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
    $mail->addEmbeddedImage(__DIR__ . '/../android-chrome-256x256.png', 'saliksiklogo');

    $subject = '[SALIKSIK] ISO 25010 Software Evaluation Survey';
    $recipient1 = 'c' . $_POST['recipient1'] . '@uphsl.edu.ph';
    $recipient2 = 'c' . $_POST['recipient2'] . '@uphsl.edu.ph';
    $recipient3 = 'c' . $_POST['recipient3'] . '@uphsl.edu.ph';
    $recipient4 = 'c' . $_POST['recipient4'] . '@uphsl.edu.ph';
    $recipient5 = 'c' . $_POST['recipient5'] . '@uphsl.edu.ph';
    $recipient6 = 'c' . $_POST['recipient6'] . '@uphsl.edu.ph';
    $recipient7 = 'c' . $_POST['recipient7'] . '@uphsl.edu.ph';
    $recipient8 = 'c' . $_POST['recipient8'] . '@uphsl.edu.ph';
    $recipient9 = 'c' . $_POST['recipient9'] . '@uphsl.edu.ph';
    $recipient10 = 'c' . $_POST['recipient10'] . '@uphsl.edu.ph';

    $mail->addAddress($recipient1);
    $mail->addAddress($recipient2);
    $mail->addAddress($recipient3);
    $mail->addAddress($recipient4);
    $mail->addAddress($recipient5);
    $mail->addAddress($recipient6);
    $mail->addAddress($recipient7);
    $mail->addAddress($recipient8);
    $mail->addAddress($recipient9);
    $mail->addAddress($recipient10);
    $mail->Subject = $subject;
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
                            <h2 style="margin:0;font-size:16px;font-weight:700;color:#1f2937;">ISO 25010 Software Evaluation Survey</h2>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:8px 32px 20px;">
                            <p style="margin:0;font-size:13px;color:#64748b;line-height:1.6;">Good day, Perpetualite!</p>
                            <p style="margin:12px 0 0;font-size:13px;color:#64748b;line-height:1.6;">We, the fourth-year BS IT students are developing our capstone project <strong style="color:#1f2937;">"SALIKSIK: UPHSL Research Repository"</strong>, in fulfillment of our subject requirement in <strong style="color:#1f2937;">Capstone Project 2</strong>.</p>
                            <p style="margin:12px 0 0;font-size:13px;color:#64748b;line-height:1.6;">If you are a College/Graduate School student, faculty, non-teaching personnel, or department head in UPHSL, you are qualified to answer this survey. Your participation will help to fulfill the objective of this study.</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:0 32px 24px;" align="center">
                            <a href="https://forms.gle/6Qd8Eqfo5kVZkc9X9" style="display:inline-block;background:#012265;color:#ffffff;font-size:13px;font-weight:700;text-decoration:none;padding:12px 32px;border-radius:8px;">Take the Survey</a>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:0 32px 24px;">
                            <p style="margin:0;font-size:12px;color:#64748b;line-height:1.5;">Thank you in advance and God bless!</p>
                            <p style="margin:12px 0 0;font-size:11px;color:#64748b;font-style:italic;">Note: Please use the UPHSL email account to access this survey.</p>
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

    $mail->send();

    $arr = array('response' => "login_success");
    header('Content-Type: application/json');
    echo json_encode($arr);
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}