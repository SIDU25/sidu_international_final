<?php
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

$tracker_result = $conn->query("SELECT * FROM loan_tracker WHERE application_id = $id ORDER BY payment_date DESC");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Admin Loan View</title>
  <style>
    body { font-family: Arial, sans-serif; margin: 30px; background: #f9f9f9; color: #000; }
    h2 { border-bottom: 1px solid #ccc; padding-bottom: 5px; margin-top: 30px; color: navy; }
    .row { display: flex; flex-wrap: wrap; margin-bottom: 10px; }
    label { min-width: 160px; display: inline-block; font-weight: bold; }
    input, textarea, select {
      border: none;
      border-bottom: 1px solid #888;
      background: transparent;
      color: #000;
      padding: 4px 6px;
      width: 300px;
    }
    .form-section { margin-top: 20px; }
    .img-box img { height: 100px; border: 1px solid #ccc; margin: 5px; cursor: pointer; }
    button {
      background: goldenrod; border: none; color: #fff; padding: 10px 15px; cursor: pointer;
      margin-top: 10px;
    }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    table, th, td { border: 1px solid #ccc; }
    th, td { padding: 8px; text-align: center; }
    .add-row { margin-top: 10px; cursor: pointer; color: #007BFF; text-decoration: underline; }
    .total-row td { font-weight: bold; background: #f0f0f0; }
        .export-buttons { margin-top: 20px; }
    .export-buttons button { margin-right: 10px; }
  </style>
</head>
<body>
  <div class="export-buttons">
  <button onclick="window.print()">🖨️ Print Page</button>
  <button onclick="exportTableToExcel('trackerTable', 'loan_tracker')">📊 Export to Excel</button>
  <button onclick="exportToWord('trackerTable')">📄 Export to Word</button>
</div>
<button class="theme-toggle" onclick="toggleTheme()">Toggle Theme</button>

<h2>SIDU INTERNATIONAL - ADMIN LOAN VIEW</h2>

<form method="POST" action="update-application.php" enctype="multipart/form-data">
  <input type="hidden" name="id" value="<?= $id ?>">

  <!-- Example: Applicant Fields -->
  <div class="form-section">
    <h2>Applicant’s Particulars</h2>
    <div class="row">
      <label>Full Name:</label><input name="full_name" value="<?= $data['full_name'] ?>">
    </div>
    <div class="row">
      <label>ID / Passport No:</label><input name="id_number" value="<?= $data['id_number'] ?>">
      <label>Nationality:</label><input name="nationality" value="<?= $data['nationality'] ?>">
      <label>Town:</label><input name="town" value="<?= $data['town'] ?>">
    </div>
   <div class="row">
      <label>Phone:</label><input name="phone" value="<?= $data['phone'] ?>">
      <label>Alt Phone:</label><input name="alt_phone" value="<?= $data['alt_phone'] ?>">
      <label>Email:</label><input name="email" value="<?= $data['email'] ?>">
    </div>
     <div class="row">
      <label>Marital Status:</label><input name="marital_status" value="<?= $data['marital_status'] ?>">
      <label>Spouse:</label><input name="spouse_name" value="<?= $data['spouse_name'] ?>">
      <label>Spouse ID:</label><input name="spouse_id" value="<?= $data['spouse_id'] ?>">
    </div>
    </div>
  <div class="section">
  <h2>RESIDENTIAL DETAILS</h2>
 <div class="row">
  <label class="label">Location:</label>
  <input type="text" name="location" class="value" value="<?= $data['location'] ?>">

  <label class="label">Plot/Name:</label>
  <input type="text" name="plot" class="value" value="<?= $data['plot'] ?>">

  <label class="label">House No:</label>
  <input type="text" name="house_no" class="value" value="<?= $data['house_no'] ?>">
</div>

<div class="row">
  <label class="label">Landmark:</label>
  <input type="text" name="landmark" class="value" value="<?= $data['landmark'] ?>">

  <label class="label">Gate Colour:</label>
  <input type="text" name="gate_colour" class="value" value="<?= $data['gate_colour'] ?>">
</div>

<div class="section">

  <h2>BUSINESS INFORMATION</h2>
  <div class="row">
    <span class="label">Ownership:</span><input type="text" name="ownership" value="<?= $data['ownership'] ?>" class="value">
    <span class="label">Name:</span><input type="text" name="business_name" value="<?= $data['business_name'] ?>" class="value">
    <span class="label">Years:</span><input type="text" name="years_operation" value="<?= $data['years_operation'] ?>" class="value">
  </div>
  <div class="row">
    <span class="label">Reg No:</span><input type="text" name="registration_no" value="<?= $data['registration_no'] ?>" class="value">
    <span class="label">Licence:</span><input type="text" name="licence_no" value="<?= $data['licence_no'] ?>" class="value">
    <span class="label">Mode:</span><input type="text" name="business_mode" value="<?= $data['business_mode'] ?>" class="value">
  </div>
  <div class="row">
    <span class="label">Location:</span><input type="text" name="business_location" value="<?= $data['business_location'] ?>" class="value">
    <span class="label">Landmark:</span><input type="text" name="business_landmark" value="<?= $data['business_landmark'] ?>" class="value">
  </div>
  <div class="row">
    <span class="label">Value (Ksh):</span><input type="number" name="business_value" value="<?= $data['business_value'] ?>" class="value">
    <span class="label">Daily Income:</span><input type="number" name="daily_income" value="<?= $data['daily_income'] ?>" class="value">
  </div>
</div>

<div class="section">
  <h2>LOAN DETAILS</h2>
  <div class="row">
    <span class="label">Amount:</span><input type="number" name="loan_amount" value="<?= $data['loan_amount'] ?>" class="value">
    <span class="label">In Words:</span><input type="text" name="loan_words" value="<?= $data['loan_words'] ?>" class="value">
  </div>
  <div class="row">
    <span class="label">Purpose:</span><input type="text" name="loan_purpose" value="<?= $data['loan_purpose'] ?>" class="value">
    <span class="label">Interest Rate:</span><input type="text" name="interest_rate" value="<?= $data['interest_rate'] ?>" class="value">%
    <span class="label">Max Period:</span><input type="text" name="repayment_period" value="<?= $data['repayment_period'] ?>" class="value"> months
  </div>
  <div class="row">
    <span class="label">Plan:</span><input type="text" name="repayment_plan" value="<?= $data['repayment_plan'] ?>" class="value">
    <span class="label">Collateral Desc:</span><input type="text" name="collateral_description" value="<?= $data['collateral_description'] ?>" class="value">
  </div>
</div>

<div class="page-break"></div>

<div class="section">
  <h2>DECLARATION</h2>
  <p>I, <input type="text" name="declaration_name" value="<?= $data['declaration_name'] ?>" class="value">, hereby declare that the information provided is true and correct.</p>
  <div class="row">
    <span class="label">Signature:</span><input type="text" name="applicant_signature" value="<?= $data['applicant_signature'] ?>" class="value">
    <span class="label">Date:</span><input type="date" name="declaration_date" value="<?= $data['declaration_date'] ?>" class="value">
  </div>
</div>

<div class="section">
  <h2>GUARANTORS</h2>
  <?php for ($i = 1; $i <= 3; $i++): ?>
    <div class="row">
      <span class="label">Name:</span><input type="text" name="guarantor<?= $i ?>_name" value="<?= $data["guarantor{$i}_name"] ?>" class="value">
      <span class="label">ID:</span><input type="text" name="guarantor<?= $i ?>_id" value="<?= $data["guarantor{$i}_id"] ?>" class="value">
      <span class="label">Phone:</span><input type="text" name="guarantor<?= $i ?>_phone" value="<?= $data["guarantor{$i}_phone"] ?>" class="value">
      <span class="label">Relation:</span><input type="text" name="guarantor<?= $i ?>_relation" value="<?= $data["guarantor{$i}_relation"] ?>" class="value">
      <span class="label">Signature:</span><input type="text" name="guarantor<?= $i ?>_signature" value="<?= $data["guarantor{$i}_signature"] ?>" class="value">
    </div>
  <?php endfor; ?>
</div>

<div class="section">
  <h2>AFFIDAVIT</h2>
  <p>
    I, <input type="text" name="affidavit_name" value="<?= $data['affidavit_name'] ?>" class="value">,
    ID <input type="text" name="affidavit_id" value="<?= $data['affidavit_id'] ?>" class="value">,
    confirm loan of Ksh <input type="number" name="affidavit_amount" value="<?= $data['affidavit_amount'] ?>" class="value">
    on <input type="date" name="loan_given_date" value="<?= $data['loan_given_date'] ?>" class="value">.
  </p>
  <p>
    Business location: <input type="text" name="affidavit_location" value="<?= $data['affidavit_location'] ?>" class="value">.
    Daily payment of Ksh <input type="number" name="repayment_daily" value="<?= $data['repayment_daily'] ?>" class="value">
    starting <input type="date" name="repayment_start" value="<?= $data['repayment_start'] ?>" class="value">.
  </p>
  <p>
    Collateral: <input type="text" name="collateral_item_desc" value="<?= $data['collateral_item_desc'] ?>" class="value">
    (Serial: <input type="text" name="collateral_serial" value="<?= $data['collateral_serial'] ?>" class="value">)
  </p>
  <div class="row">
    <span class="label">Signature:</span><input type="text" name="affidavit_signature" value="<?= $data['affidavit_signature'] ?>" class="value">
    <span class="label">Date Signed:</span><input type="date" name="affidavit_signed_date" value="<?= $data['affidavit_signed_date'] ?>" class="value">
  </div>
</div>

  <div class="form-section">
    <h2>Uploaded Documents</h2>
    <div class="img-box">
      <strong>ID Front:</strong><br>
      <img id="id_front_preview" src="<?= $data['id_front'] ?>" alt="ID Front" onclick="window.open(this.src)">
      <input type="file" name="id_front" onchange="previewImage(this, 'id_front_preview')">
    </div>
    <div class="img-box">
      <strong>ID Back:</strong><br>
      <img id="id_back_preview" src="<?= $data['id_back'] ?>" alt="ID Back" onclick="window.open(this.src)">
      <input type="file" name="id_back" onchange="previewImage(this, 'id_back_preview')">
    </div>
    <div class="img-box">
      <strong>Collateral Doc:</strong><br>
      <?php if (strpos($data['collateral_doc'], '.pdf') !== false): ?>
        <a href="<?= $data['collateral_doc'] ?>" target="_blank">View PDF</a>
      <?php else: ?>
        <img id="collateral_preview" src="<?= $data['collateral_doc'] ?>" alt="Collateral" onclick="window.open(this.src)">
      <?php endif; ?>
      <input type="file" name="collateral_doc" onchange="previewImage(this, 'collateral_preview')">
    </div>
    <div class="img-box">
      <strong>Upload Letter:</strong><br>
      <input type="file" name="support_letter">
    </div>
  </div>

  <div class="form-section">
    <button type="submit">💾 Save Changes</button>
  </div>
</form>

<!-- 🔥 START: Loan Repayment Tracker Section -->
<h2>Loan Repayment Tracker</h2>

<!-- New Entry Form -->
<form method="POST" action="save-tracker.php" id="trackerForm">
  <input type="hidden" name="application_id" value="<?= $id ?>">
  <table id="trackerTable">
    <thead>
      <tr>
        <th>Date</th>
        <th>Amount Paid</th>
        <th>Fine</th>
        <th>Ref No</th>
        <th>Balance</th>
        <th>SIDU Sign</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><input type="date" name="payment_date[]" required></td>
        <td><input type="number" step="0.01" name="amount_paid[]" class="amount" required></td>
        <td><input type="number" step="0.01" name="fine[]" class="fine"></td>
        <td><input type="text" name="ref_no[]"></td>
        <td><input type="number" step="0.01" name="balance[]"></td>
        <td><input type="text" name="sidu_sign[]"></td>
      </tr>
    </tbody>
    <tfoot>
      <tr class="total-row">
        <td colspan="1">Total</td>
        <td id="totalAmount">0.00</td>
        <td id="totalFine">0.00</td>
        <td colspan="3"></td>
      </tr>
    </tfoot>
  </table>
  <div class="add-row" onclick="addRow()">➕ Add Another Row</div>
  <button type="submit">💾 Save Tracker Entries</button>
</form>

<!-- Existing Tracker Records -->
<h3 style="margin-top:30px;">📋 Payment History for This Loan</h3>

<?php if ($tracker_result && $tracker_result->num_rows > 0): ?>
  <table>
    <tr>
      <th>Date</th>
      <th>Amount Paid</th>
      <th>Fine</th>
      <th>Ref No</th>
      <th>Balance</th>
      <th>SIDU Sign</th>
    </tr>
    <?php while($row = $tracker_result->fetch_assoc()): ?>
      <tr>
        <td><?= htmlspecialchars($row['payment_date']) ?></td>
        <td>Ksh <?= number_format($row['amount_paid'], 2) ?></td>
        <td><?= number_format($row['fine'], 2) ?></td>
        <td><?= htmlspecialchars($row['ref_no']) ?></td>
        <td><?= number_format($row['balance'], 2) ?></td>
        <td><?= htmlspecialchars($row['sidu_sign']) ?></td>
      </tr>
    <?php endwhile; ?>
  </table>
<?php else: ?>
  <p>No repayments recorded yet.</p>
<?php endif; ?>
<!-- 🔥 END: Loan Repayment Tracker Section -->

<script>
function addRow() {
  const table = document.querySelector("#trackerTable tbody");
  const row = table.rows[0].cloneNode(true);
  row.querySelectorAll("input").forEach(input => input.value = "");
  table.appendChild(row);
  calculateTotals();
}

function calculateTotals() {
  let totalAmount = 0;
  let totalFine = 0;

  document.querySelectorAll(".amount").forEach(input => {
    totalAmount += parseFloat(input.value) || 0;
  });
  document.querySelectorAll(".fine").forEach(input => {
    totalFine += parseFloat(input.value) || 0;
  });

  document.getElementById("totalAmount").innerText = totalAmount.toFixed(2);
  document.getElementById("totalFine").innerText = totalFine.toFixed(2);
}

document.querySelectorAll("input").forEach(input => {
  input.addEventListener("input", calculateTotals);
});
</script>

</body>
</html>
