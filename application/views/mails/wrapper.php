<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title></title>
    <!--[if mso]><xml><o:OfficeDocumentSettings><o:AllowPNG/></o:OfficeDocumentSettings></xml><![endif]-->
    <style type="text/css">
        body{ margin:0; padding:0; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; background-color:#f4f4f4; }
        table{ border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; }
        img{ border:0; height:auto; line-height:100%; outline:none; text-decoration:none; -ms-interpolation-mode:bicubic; max-width:100%; }
        @media only screen and (max-width:600px){ .email-wrap{ width:100% !important; } .email-body-td{ padding:16px !important; } }
    </style>
</head>
<body style="margin:0;padding:0;background-color:#f4f4f4;font-family:Arial,sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#f4f4f4" style="background-color:#f4f4f4;">
        <tr>
            <td align="center" style="padding:20px 10px;">
                <table role="presentation" class="email-wrap" width="600" cellpadding="0" cellspacing="0" border="0" style="max-width:600px;width:100%;background-color:#ffffff;border-radius:4px;">

                    <!-- HEADER -->
                    <tr>
                        <td class="email-header-td" align="center" bgcolor="#0066cc"
                            style="padding:24px 20px;text-align:center;background-color:#0066cc;border-radius:4px 4px 0 0;">
                            <?php if (!empty($logo_url)) { ?>
                            <a href="<?= $base_url ?>" target="_blank" style="display:inline-block;">
                                <img src="<?= htmlspecialchars($logo_url, ENT_QUOTES, 'UTF-8') ?>"
                                     alt="<?= htmlspecialchars($site_name, ENT_QUOTES, 'UTF-8') ?>"
                                     style="max-width:144px;height:auto;display:block;border:0;">
                            </a>
                            <?php } ?>
                            <h1 style="margin:12px 0 0;font-size:20px;font-weight:bold;color:#ffffff;font-family:Arial,sans-serif;">
                                <?= htmlspecialchars($site_name, ENT_QUOTES, 'UTF-8') ?>
                            </h1>
                        </td>
                    </tr>

                    <!-- BODY -->
                    <tr>
                        <td class="email-body-td" style="padding:28px 24px;font-size:15px;line-height:1.7;color:#333333;background-color:#ffffff;font-family:Arial,sans-serif;">
                            <?= $html ?>
                        </td>
                    </tr>

                    <!-- FOOTER -->
                    <tr>
                        <td align="center" bgcolor="#0066cc"
                            style="padding:20px;text-align:center;background-color:#0066cc;color:#ffffff;font-size:13px;font-family:Arial,sans-serif;border-radius:0 0 4px 4px;">
                            <?php if (!empty($footer)) { ?>
                            <div style="margin-bottom:12px;color:#ffffff;"><?= $footer ?></div>
                            <?php } ?>
                            <?php if (!empty($unsub_text)) { ?>
                            <div style="color:#ffffff;"><?= $unsub_text ?></div>
                            <?php } ?>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
