<?php
// print-application.php
$host = "localhost";
$user = "root";
$pass = "";
$db = "sidu_portal";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$id = intval($_GET['id']);
$result = $conn->query("SELECT * FROM loan_applications WHERE id = $id");
$data = $result->fetch_assoc();
if (!$data) die("Application not found.");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Print Loan Application</title>
  <style>
    body { font-family: "Times New Roman", Times, serif; margin: 40px; font-size: 13px; }
    h2 { color: navy; font-size: 16px; text-decoration: underline; margin-top: 30px; }
    .section { margin-top: 25px; }
    .row { display: flex; flex-wrap: wrap; margin-bottom: 5px; }
    .col { flex: 1; margin-right: 20px; }
    .label { font-weight: bold; min-width: 140px; display: inline-block; }
    .value { border-bottom: 1px solid black; display: inline-block; min-width: 200px; padding: 2px 5px; }
    .img-box { margin-top: 10px; }
    .img-box img { border: 1px solid #000; max-height: 120px; margin-right: 10px; }
    .pdf-link { color: blue; text-decoration: underline; display: block; margin-top: 5px; }
    .print-btn { position: fixed; top: 20px; right: 40px; background: goldenrod; color: white; border: none; padding: 10px; cursor: pointer; }
    .page-break { page-break-before: always; }
  </style>
</head>
<body>

<button class="print-btn" onclick="window.print()">🖨️ Print</button>

<h2>SIDU INTERNATIONAL - LOAN APPLICATION</h2>

<div class="section">
  <h2>APPLICANT’S PARTICULARS</h2>
  <div class="row"><span class="label">Name:</span><span class="value"><?= $data['full_name'] ?></span></div>
  <div class="row">
    <span class="label">ID / Passport No:</span><span class="value"><?= $data['id_number'] ?></span>
    <span class="label">Nationality:</span><span class="value"><?= $data['nationality'] ?></span>
    <span class="label">Town:</span><span class="value"><?= $data['town'] ?></span>
  </div>
  <div class="row">
    <span class="label">Phone:</span><span class="value"><?= $data['phone'] ?></span>
    <span class="label">Alt Phone:</span><span class="value"><?= $data['alt_phone'] ?></span>
    <span class="label">Email:</span><span class="value"><?= $data['email'] ?></span>
  </div>
  <div class="row">
    <span class="label">Marital Status:</span><span class="value"><?= $data['marital_status'] ?></span>
    <span class="label">Spouse:</span><span class="value"><?= $data['spouse_name'] ?></span>
    <span class="label">Spouse ID:</span><span class="value"><?= $data['spouse_id'] ?></span>
  </div>
</div>

<div class="section">
  <h2>RESIDENTIAL DETAILS</h2>
  <div class="row">
    <span class="label">Location:</span><span class="value"><?= $data['location'] ?></span>
    <span class="label">Plot/Name:</span><span class="value"><?= $data['plot'] ?></span>
    <span class="label">House No:</span><span class="value"><?= $data['house_no'] ?></span>
  </div>
  <div class="row">
    <span class="label">Landmark:</span><span class="value"><?= $data['landmark'] ?></span>
    <span class="label">Gate Colour:</span><span class="value"><?= $data['gate_colour'] ?></span>
  </div>
</div>

<div class="section">
  <h2>BUSINESS INFORMATION</h2>
  <div class="row">
    <span class="label">Ownership:</span><span class="value"><?= $data['ownership'] ?></span>
    <span class="label">Name:</span><span class="value"><?= $data['business_name'] ?></span>
    <span class="label">Years:</span><span class="value"><?= $data['years_operation'] ?></span>
  </div>
  <div class="row">
    <span class="label">Reg No:</span><span class="value"><?= $data['registration_no'] ?></span>
    <span class="label">Licence:</span><span class="value"><?= $data['licence_no'] ?></span>
    <span class="label">Mode:</span><span class="value"><?= $data['business_mode'] ?></span>
  </div>
  <div class="row">
    <span class="label">Location:</span><span class="value"><?= $data['business_location'] ?></span>
    <span class="label">Landmark:</span><span class="value"><?= $data['business_landmark'] ?></span>
  </div>
  <div class="row">
    <span class="label">Value (Ksh):</span><span class="value"><?= number_format($data['business_value']) ?></span>
    <span class="label">Daily Income:</span><span class="value"><?= number_format($data['daily_income']) ?></span>
  </div>
</div>

<div class="section">
  <h2>LOAN DETAILS</h2>
  <div class="row">
    <span class="label">Amount:</span><span class="value"><?= number_format($data['loan_amount']) ?></span>
    <span class="label">In Words:</span><span class="value"><?= $data['loan_words'] ?></span>
  </div>
  <div class="row">
    <span class="label">Purpose:</span><span class="value"><?= $data['loan_purpose'] ?></span>
    <span class="label">Interest Rate:</span><span class="value"><?= $data['interest_rate'] ?>%</span>
    <span class="label">Max Period:</span><span class="value"><?= $data['repayment_period'] ?> months</span>
  </div>
  <div class="row">
    <span class="label">Plan:</span><span class="value"><?= $data['repayment_plan'] ?></span>
    <span class="label">Collateral Desc:</span><span class="value"><?= $data['collateral_description'] ?></span>
  </div>
</div>

<div class="page-break"></div>

<div class="section">
  <h2>DECLARATION</h2>
  <p>I, <?= $data['declaration_name'] ?>, hereby declare that the information provided is true and correct.</p>
  <div class="row">
    <span class="label">Signature:</span><span class="value"><?= $data['applicant_signature'] ?></span>
    <span class="label">Date:</span><span class="value"><?= $data['declaration_date'] ?></span>
  </div>
</div>

<div class="section">
  <h2>GUARANTORS</h2>
  <?php for ($i = 1; $i <= 3; $i++): ?>
    <div class="row">
      <span class="label">Name:</span><span class="value"><?= $data["guarantor{$i}_name"] ?></span>
      <span class="label">ID:</span><span class="value"><?= $data["guarantor{$i}_id"] ?></span>
      <span class="label">Phone:</span><span class="value"><?= $data["guarantor{$i}_phone"] ?></span>
      <span class="label">Relation:</span><span class="value"><?= $data["guarantor{$i}_relation"] ?></span>
      <span class="label">Signature:</span><span class="value"><?= $data["guarantor{$i}_signature"] ?></span>
    </div>
  <?php endfor; ?>
</div>

<div class="section">
  <h2>AFFIDAVIT</h2>
  <p>I, <?= $data['affidavit_name'] ?>, ID <?= $data['affidavit_id'] ?>, confirm loan of Ksh <?= number_format($data['affidavit_amount']) ?> on <?= $data['loan_given_date'] ?>.</p>
  <p>Business location: <?= $data['affidavit_location'] ?>. Daily payment of Ksh <?= $data['repayment_daily'] ?> starting <?= $data['repayment_start'] ?>.</p>
  <p>Collateral: <?= $data['collateral_item_desc'] ?> (Serial: <?= $data['collateral_serial'] ?>)</p>
  <div class="row">
    <span class="label">Signature:</span><span class="value"><?= $data['affidavit_signature'] ?></span>
    <span class="label">Date Signed:</span><span class="value"><?= $data['affidavit_signed_date'] ?></span>
  </div>
</div>

<div class="section">
  <h2>UPLOADED DOCUMENTS</h2>
  <div class="img-box">
    <strong>Passport Photo:</strong><br>
    <img src="<?= $data['passport_photo'] ?>" alt="Passport Photo">
  </div>
  <div class="img-box">
    <strong>ID Front:</strong><br>
    <img src="<?= $data['id_front'] ?>" alt="ID Front">
  </div>
  <div class="img-box">
    <strong>ID Back:</strong><br>
    <img src="<?= $data['id_back'] ?>" alt="ID Back">
  </div>
  <div class="img-box">
    <strong>Collateral Document:</strong><br>
    <?php if (strpos($data['collateral_doc'], '.pdf') !== false): ?>
      <a href="<?= $data['collateral_doc'] ?>" target="_blank" class="pdf-link">View PDF</a>
    <?php else: ?>
      <img src="<?= $data['collateral_doc'] ?>" alt="Collateral Document">
    <?php endif; ?>
  </div>
</div>

</body>
</html>