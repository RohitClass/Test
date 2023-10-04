<?php

$Email = "rohitsharma243403@gmail.com";
$Password = "wzqx hpre yhdq lqbr";

$startDate = "2023-09-01 15:09:15";
$endDate = "2023-09-30 15:25:40";

$imapStartDate = date("j-M-Y H:i:s", strtotime($startDate));
$imapEndDate = date("j-M-Y H:i:s", strtotime($endDate));

$imap_server = imap_open("{imap.gmail.com:993/ssl}INBOX", $Email, $Password);

if (!$imap_server) {
    die("Failed to connect to mailbox: " . imap_last_error());
}

$searchCriteria = "SINCE \"$imapStartDate\" BEFORE \"$imapEndDate\"";

$mail = imap_search($imap_server, $searchCriteria);

if (!$mail) {
    die("No emails found in the specified date and time range");
}

$csvFolderPath = "Csv-file/";

if (!file_exists($csvFolderPath)) {
    mkdir($csvFolderPath, 0777, true);
}

$csvFilePath = $csvFolderPath . "email_data.csv";

$csvFile = fopen($csvFilePath, "w");
if (!$csvFile) {
    die("Failed to create CSV file");
}
fputcsv($csvFile, ["Subject", "From", "Date", "Message Body"]);

foreach ($mail as $message_number) {
    $mail_headers = imap_headerinfo($imap_server, $message_number);
    if (!$mail_headers) {
        die("Failed to retrieve email headers");
    }

    $subject = $mail_headers->subject;
    $from = $mail_headers->fromaddress;
    $date = date("Y-m-d H:i:s", strtotime($mail_headers->date));
    $message_body = base64_decode(imap_fetchbody($imap_server, $message_number, 1));

    fputcsv($csvFile, [$subject, $from, $date, $message_body]);
}

fclose($csvFile);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Email Data</title>
</head>
<body>
    <a href="<?php echo $csvFilePath; ?>" download>Download CSV</a>
    <table border="1">
        <tr>
            <th>Subject</th>
            <th>From</th>
            <th>Date</th>
            <th>Message Body</th>
        </tr>
        <?php
        foreach ($mail as $message_number) {
            $mail_headers = imap_headerinfo($imap_server, $message_number);
            if (!$mail_headers) {
                die("Failed to retrieve email headers");
            }

            $subject = $mail_headers->subject;
            $from = $mail_headers->fromaddress;
            $date = date("Y-m-d H:i:s", strtotime($mail_headers->date));
            $message_body = base64_decode(imap_fetchbody($imap_server, $message_number, 1));

            echo "<tr>";
            echo "<td>$subject</td>";
            echo "<td>$from</td>";
            echo "<td>$date</td>";
            echo "<td>$message_body</td>";
            echo "</tr>";
        }
        ?>
    </table>
</body>
</html>
