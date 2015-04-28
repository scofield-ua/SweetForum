<?php
$url = "http://".$_SERVER['SERVER_NAME'].SWEET_FORUM_BASE_URL."users/password_reset/".$hash;
?>
<td bgcolor="#ffffff" style="padding: 40px 30px 40px 30px;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td style="color: #153643; font-family: Arial, sans-serif; font-size: 24px;">
                <b><?= __d("sweet_forum", "Recovery password") ?></b>
            </td>
        </tr>
        <tr>
            <td style="padding: 20px 0 30px 0; color: #153643; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;">
                <p><?= __d("sweet_forum", "If you need to recovery password use this link")." - "; ?> <a href='<?= $url; ?>' style='color: #FF9D60;'><?= $url; ?></a></p>
            </td>
        </tr>								
    </table>
</td>