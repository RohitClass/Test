<?php

$Email = "rohitsharma243403@gmail.com";
$Password = "wzqx hpre yhdq lqbr";

$imap_server = imap_open("{imap.gmail.com:993/ssl}INBOX", $Email, $Password);

if (!$imap_server) {
    die("Failed to connect to mailbox: " . imap_last_error());
}

if (isset($_POST['search'])) {
    // Get search parameters from the form
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];
    $subjectKeyword = $_POST['subject_keyword'];
    $senderEmail = $_POST['sender_email'];

    $imapStartDate = date("j-M-Y H:i:s", strtotime($startDate));
    $imapEndDate = date("j-M-Y H:i:s", strtotime($endDate));

    $searchCriteria = "SINCE \"$imapStartDate\" BEFORE \"$imapEndDate\"";
    
    // Add SUBJECT criteria only if a subject keyword is provided
    if (!empty($subjectKeyword)) {
        $searchCriteria .= " SUBJECT \"$subjectKeyword\"";
    }

    // Add FROM criteria only if a sender email is provided
    if (!empty($senderEmail)) {
        $searchCriteria .= " FROM \"$senderEmail\"";
    }
    
    // Search emails based on the criteria
    $mail = imap_search($imap_server, $searchCriteria);

    if (!$mail) {
        die("No emails found matching the search criteria");
    }
} else {
    // Default search criteria if the form is not submitted
    $startDate = "2023-08-01 15:09:15";
    $endDate = "2023-10-30 15:25:40";
    $subjectKeyword = "";
    $senderEmail = "";

    $imapStartDate = date("j-M-Y H:i:s", strtotime($startDate));
    $imapEndDate = date("j-M-Y H:i:s", strtotime($endDate));

    $searchCriteria = "SINCE \"$imapStartDate\" BEFORE \"$imapEndDate\"";
    
    // Search emails based on the criteria
    $mail = imap_search($imap_server, $searchCriteria);

    if (!$mail) {
        die("No emails found in the specified date and time range");
    }
}

$downloadFolderPath = "Attachment-files/";

if (!file_exists($downloadFolderPath)) {
    mkdir($downloadFolderPath, 0777, true);
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Email Data</title>
</head>
<body>
    <h2>Email Search</h2>
    <form method="post">
        <label for="start_date">Start Date and Time:</label>
        <input type="datetime-local" id="start_date" name="start_date" value="<?= $startDate ?>" required>
        <br>
        <label for="end_date">End Date and Time:</label>
        <input type="datetime-local" id="end_date" name="end_date" value="<?= $endDate ?>" required>
        <br>
        <label for="subject_keyword">Subject Keyword:</label>
        <input type="text" id="subject_keyword" name="subject_keyword" value="<?= $subjectKeyword ?>">
        <br>
        <label for="sender_email">Sender Email:</label>
        <input type="email" id="sender_email" name="sender_email" value="<?= $senderEmail ?>">
        <br>
        <input type="submit" name="search" value="Search">
    </form>

    <table border="1">
        <tr>
            <th>Subject</th>
            <th>From</th>
            <th>Date</th>
            <th>Message Body</th>
            <th>Attachments</th>
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
    
        // Retrieve email attachments
        $structure = imap_fetchstructure($imap_server, $message_number);
        $attachments = [];
    
        if (isset($structure->parts) && is_array($structure->parts) && count($structure->parts) > 0) {
            foreach ($structure->parts as $part_number => $part) {
                if (isset($part->disposition) && $part->disposition == "ATTACHMENT") {
                    $attachment_data = imap_fetchbody($imap_server, $message_number, $part_number + 1);
                    $attachmentName = $part->dparameters[0]->value;
                    $attachmentPath = $downloadFolderPath . $attachmentName;
                    file_put_contents($attachmentPath, base64_decode($attachment_data));
                    $attachments[] = $attachmentPath;
                }
            }
        }
    
        // Retrieve and decode email message body
        $message_body = imap_fetchbody($imap_server, $message_number, 1);
        $decoded_message_body = base64_decode($message_body);

    
        echo "<tr>";
        echo "<td>$subject</td>";
        echo "<td>$from</td>";
        echo "<td>$date</td>";
        echo "<td>$decoded_message_body</td>";
        echo "<td>";
        foreach ($attachments as $attachmentPath) {
            echo "<a href=\"$attachmentPath\" download>" . basename($attachmentPath) . "</a><br>";
        }
        echo "</td>";
        echo "</tr>";
    }
        ?>
    </table>
</body>
</html>
