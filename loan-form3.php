<!-- loan-form-page3.php -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Loan Form - Page 3</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      padding: 40px;
      background: #f0f9ff;
    }
    .page {
      width: 800px;
      margin: auto;
      background: #fff;
      padding: 40px;
      border: 1px solid #ccc;
      box-shadow: 0 0 15px rgba(0,0,0,0.1);
    }
    h2 {
      text-align: center;
      text-decoration: underline;
    }
    label {
      display: block;
      margin-top: 15px;
      font-weight: bold;
    }
    .line-input {
      border-bottom: 1px solid #000;
      width: 100%;
      padding: 4px 6px;
      font-size: 16px;
    }
    .section-title {
      font-weight: bold;
      margin-top: 30px;
      text-decoration: underline;
    }
    .file-preview img {
      max-height: 120px;
      margin-top: 10px;
    }
    .file-preview a {
      display: block;
      margin-top: 5px;
      color: blue;
    }
  </style>
  <script>
    function previewFile(input, previewId) {
      const file = input.files[0];
      const preview = document.getElementById(previewId);
      preview.innerHTML = '';
      if (!file) return;
      const reader = new FileReader();
      reader.onload = function(e) {
        if (file.type.includes('image')) {
          preview.innerHTML = '<img src="' + e.target.result + '">';
        } else if (file.type === 'application/pdf') {
          preview.innerHTML = '<a href="' + e.target.result + '" target="_blank">View PDF</a>';
        }
      };
      reader.readAsDataURL(file);
    }
  </script>
</head>
<body>

<form class="page" action="submit-loan.php" method="post" enctype="multipart/form-data">
  <h2>Page 3: Affidavit & Uploads</h2>

  <div class="section-title">AFFIDAVIT</div>

  <label>Applicant Name:</label>
  <input type="text" name="affidavit_name" class="line-input" required>

  <label>ID Number:</label>
  <input type="text" name="affidavit_id" class="line-input" required>

  <label>Phone Number:</label>
  <input type="text" name="affidavit_phone" class="line-input" required>

  <label>Loan Amount (Ksh):</label>
  <input type="number" name="affidavit_amount" class="line-input" required>

  <label>Date Loan Given:</label>
  <input type="date" name="loan_given_date" class="line-input" required>

  <label>Business Location (Collateral):</label>
  <input type="text" name="affidavit_location" class="line-input" required>

  <label>Daily Repayment Amount (Ksh):</label>
  <input type="number" name="repayment_daily" class="line-input" required>

  <label>Start Date:</label>
  <input type="date" name="repayment_start" class="line-input" required>

  <label>Collateral Item Description:</label>
  <textarea name="collateral_item_desc" class="line-input" rows="3"></textarea>

  <label>Serial No (if any):</label>
  <input type="text" name="collateral_serial" class="line-input">

  <label>Signature:</label>
  <input type="text" name="affidavit_signature" class="line-input">

  <label>Date Signed:</label>
  <input type="date" name="affidavit_signed_date" class="line-input">

  <div class="section-title">UPLOAD DOCUMENTS</div>

  <label>Passport Photo:</label>
  <input type="file" name="passport_photo" accept="image/*" onchange="previewFile(this, 'passportPreview')" required>
  <div class="file-preview" id="passportPreview"></div>

  <label>ID Front:</label>
  <input type="file" name="id_front" accept="image/*" onchange="previewFile(this, 'idFrontPreview')" required>
  <div class="file-preview" id="idFrontPreview"></div>

  <label>ID Back:</label>
  <input type="file" name="id_back" accept="image/*" onchange="previewFile(this, 'idBackPreview')" required>
  <div class="file-preview" id="idBackPreview"></div>

  <label>Collateral Document (Image or PDF):</label>
  <input type="file" name="collateral_doc" accept="image/*,application/pdf" onchange="previewFile(this, 'collateralPreview')" required>
  <div class="file-preview" id="collateralPreview"></div>

  <br>
  <button type="submit">Submit Full Loan Application</button>
</form>

</body>
</html>
