<?php
session_start();

// Save step 1 fields into session
$_SESSION['loan_form'] = [
    'full_name' => $_POST['full_name'],
    'id_number' => $_POST['id_number'],
    'nationality' => $_POST['nationality'],
    'town' => $_POST['town'],
    'phone' => $_POST['phone'],
    'alt_phone' => $_POST['alt_phone'],
    'email' => $_POST['email'],
    'marital_status' => $_POST['marital_status'],
    'spouse_name' => $_POST['spouse_name'],
    'spouse_id' => $_POST['spouse_id'],
    'spouse_phone' => $_POST['spouse_phone'],
    'location' => $_POST['location'],
    'plot' => $_POST['plot'],
    'house_no' => $_POST['house_no'],
    'landmark' => $_POST['landmark'],
    'gate_colour' => $_POST['gate_colour'],
    'ownership' => $_POST['ownership'],
    'business_name' => $_POST['business_name'],
    'years_operation' => $_POST['years_operation'],
    'registration_no' => $_POST['registration_no'],
    'licence_no' => $_POST['licence_no'],
    'business_mode' => $_POST['business_mode'],
    'business_location' => $_POST['business_location'],
    'business_landmark' => $_POST['business_landmark'],
    'business_value' => $_POST['business_value'],
    'daily_income' => $_POST['daily_income'],
    'loan_amount' => $_POST['loan_amount'],
    'loan_words' => $_POST['loan_words'],
    'loan_purpose' => $_POST['loan_purpose'],
    'interest_rate' => $_POST['interest_rate'],
    'repayment_period' => $_POST['repayment_period'],
    'repayment_plan' => $_POST['repayment_plan'],
    'collateral_description' => $_POST['collateral_description']
];

// Redirect to page 2
header("Location: loan-form2.php");
exit;
?>
