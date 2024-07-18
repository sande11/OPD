<?php
include_once '../includes/conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $phonenum = $_POST['phone_number'];
    $reference = strtoupper($_POST['trans_id']);
    $total_amount = $_POST['amount'];
    $currentDateTime = date("Y-m-d H:i:s");
    $patient_id =  $_POST['patient_id'];;

    // REMOVE DOTS
    function remove_dots($str)
    {
        return str_replace('.', '', $str);
    }
    // ADD DOTS 
    function addDotsToString($str)
    {

        if (strpos($str, '.') !== false) {
            if (substr_count($str, '.') == 2) {
                return $str;
                # code...
            } else {
                # code...
                $newS = str_replace('.', '', $str);
                $newStr = substr_replace($newS, '.', 8, 0); // add dot after 8th character
                $finalStr = substr_replace($newStr, '.', 13, 0); // add dot after 12th character
                return $finalStr;
            }
        } else {
            $newStr = substr_replace($str, '.', 8, 0); // add dot after 8th character
            $finalStr = substr_replace($newStr, '.', 13, 0); // add dot after 12th character
            return $finalStr;
        }
    }
    // remove dot
    function removeLastDot($str)
    {
        if (substr($str, -1) === '.') {
            return substr($str, 0, strlen($str) - 1);
        }
        return $str;
    }

    // database

    // MAIL DETAILS
    $hostname = '{outlook.office365.com:993/imap/ssl/novalidate-cert}';
    $username = 'Shammahweya@outlook.com';
    $password = 'Pinduzi2000';
    // try to connect
    if ($inbox = imap_open($hostname, $username, $password)) {
        // grab emails
        $from_email = "Shammahweya@outlook.com";
        $emails = imap_search($inbox, 'FROM "' . $from_email . '"');
        // if emails are returned, cycle through each...

        if ($emails) {
            rsort($emails);
            set_time_limit(300000);
            foreach ($emails as $email_number) {
                // get information specific to this email
                $headers = imap_fetch_overview($inbox, $email_number, 0);
                $message = imap_fetchbody($inbox, $email_number, '1');
                // Extract transaction ID, phone number, and amount from the message
                preg_match("/Trans\.ID\s*:\s*([A-Za-z0-9.]+)/i", $message, $ref_matches);
                preg_match("/from\s*([0-9]{9})/i", $message, $phone_matches);
                preg_match("/you have received\s*MK\s*([0-9.]+)/i", $message, $amount_matches);

                if (!empty($ref_matches) && !empty($phone_matches) && !empty($amount_matches)) {
                    $ref_number = $ref_matches[1];
                    $phone_number = $phone_matches[1];
                    $amount = $amount_matches[1];

                    // Construct the reference number from extracted data
                    $realmessage = "Trans.ID : $ref_number, from $phone_number, you have received MK $amount";

                    $query2 = "SELECT * FROM payments WHERE ref_num = '$realmessage'";
                    $result2 = mysqli_query($conn, $query2);
                    $num = mysqli_num_rows($result2);

                    if ($num == 0) {
                        $sql = "INSERT INTO payments (ref_num, payment_date) VALUES ('$realmessage', '$currentDateTime')";
                        if (mysqli_query($conn, $sql)) {
                            // Inserted successfully, no need to continue.
                            continue;
                        } else {
                            // header("location:checkout.php?error= error while transacting your payment, please try again later");
                            // exit();
                        }
                    }
                }
            }
        } else {
            echo "Cant connect to mailbox: please connect to internet and try again";
            // header("location:checkout.php?error= can not verify payment, please connect to internet and try again");
            // exit();
        } // END OF IMAPS
        //
        // Build the SQL query to search for the message
        $sql = "SELECT * FROM payments WHERE ref_num LIKE '%Trans.ID : $reference%' ";
        $result = mysqli_query($conn, $sql);
        // Loop through the query results
        while ($row = mysqli_fetch_assoc($result)) {
            $payment_id = $row['payment_id'];
            // $patient_id = $row['patient_id']; // Move this line inside the loop

            preg_match("/Trans.ID :\s+([A-Za-z0-9.]+)/i", $row['ref_num'], $ref_matches);
            preg_match("/from\s+([0-9]{9}),/", $row['ref_num'], $phone_matches);
            preg_match("/you have received MK\s+([0-9.]+)\./", $row['ref_num'], $amount_matches);

            $ref_number = removeLastDot($ref_matches[1]);
            $phone_number = $phone_matches[1];
            $amount = $amount_matches[1];

            // Check if payment matches the booking details
            if ($ref_number == $reference && $phone_number == $phonenum && $amount == $total_amount) {
                // UPDATE PAYMENT RECORDS
                $sql = "UPDATE payments SET state = 'used', patient_id='" . $patient_id . "' WHERE ref_num LIKE '%Trans.ID : $reference%'";

                if (mysqli_query($conn, $sql)) {

                    $success_message = "Payment with reference number $ref_number has been successfully verified.";

                    echo "<script>alert('$success_message');</script>";

                    echo "<script>window.location.href = './payment.php';</script>";
                    exit();
                } else {
                    echo "Error updating payment record: " . mysqli_error($conn);
                }
            } else {
                $error = "Input is not valid for reference number $ref_number.";
            }
        }
    }
}
