<?php

require_once 'autoloader.php';

$logReader = new LogReader_Nginx("/var/log/nginx/error.log", new LogReader_Storage_Array());
$logReader->read();

$logs = $logReader->getStorage()->load();
$logs = array_reverse($logs);

?>

<p>File '<?=$logReader->getFilename()?>':</p>

<?php if (count($logs)) { ?>
    <table border="1">
        <tr>
            <th>Date</th>
            <th>Message</th>
            <th>Host + Request</th>
            <th>Referrer</th>
        </tr>
        <?php foreach ($logs as $item) { ?>
        <tr>
            <td><?=$item->getTimestamp()?></td>
            <td><?=nl2br(htmlspecialchars($item->getMessage()))?></td>
            <td><a href="<?=htmlspecialchars($item->getRequestUrl())?>"><?=htmlspecialchars($item->getRequestUrl())?></a></td>
            <td><a href="<?=htmlspecialchars($item->getReferrer())?>"><?=htmlspecialchars($item->getReferrer())?></a></td>
        </tr>
        <?php } ?>
    </table>
<?php } else { ?>
    <p>
        <em>Empty data.</em>
    </p>
<?php } ?>
