<!-- loan-form1.php -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>SIDU International - Loan Application Page 1</title>
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
      display: inline-block;
      border-bottom: 1px solid #000;
      width: 100%;
      padding: 4px 6px;
      font-size: 16px;
    }
    .half {
      width: 48%;
      display: inline-block;
      margin-right: 3%;
    }
    .section-title {
      font-weight: bold;
      margin-top: 30px;
      text-decoration: underline;
    }
  </style>
</head>
<body>
<form class="page" action="submit-loan.php" method="post" enctype="multipart/form-data">

  <h2>SIDU INTERNATIONAL LOAN APPLICATION - PAGE 1</h2>

  <div class="section-title">APPLICANT’S PARTICULARS</div>
  <label>Applicant’s Name:</label>
  <input type="text" name="full_name" class="line-input" required>

  <div class="half">
    <label>ID / Passport No:</label>
    <input type="text" name="id_number" class="line-input" required>
  </div>
  <div class="half">
    <label>Nationality:</label>
    <input type="text" name="nationality" class="line-input" required>
  </div>

  <label>Town:</label>
  <input type="text" name="town" class="line-input" required>

  <div class="half">
    <label>Phone No:</label>
    <input type="text" name="phone" class="line-input" required>
  </div>
  <div class="half">
    <label>Alternative No:</label>
    <input type="text" name="alt_phone" class="line-input">
  </div>

  <label>Email:</label>
  <input type="email" name="email" class="line-input">

  <div class="half">
    <label>Marital Status:</label>
    <input type="text" name="marital_status" class="line-input">
  </div>
  <div class="half">
    <label>Spouse Name:</label>
    <input type="text" name="spouse_name" class="line-input">
  </div>

  <div class="half">
    <label>Spouse ID:</label>
    <input type="text" name="spouse_id" class="line-input">
  </div>
  <div class="half">
    <label>Spouse Phone No:</label>
    <input type="text" name="spouse_phone" class="line-input">
  </div>

  <div class="section-title">RESIDENTIAL DETAILS</div>
  <label>Location:</label>
  <input type="text" name="location" class="line-input">
  
  <label>Plot No / Name:</label>
  <input type="text" name="plot" class="line-input">

  <label>House No:</label>
  <input type="text" name="house_no" class="line-input">

  <label>Nearest Landmark:</label>
  <input type="text" name="landmark" class="line-input">

  <label>Gate Colour:</label>
  <input type="text" name="gate_colour" class="line-input">

  <div class="section-title">BUSINESS INFORMATION</div>
  <label>Business Ownership:</label>
  <select name="ownership" class="line-input">
    <option value="Family Business">Family Business</option>
    <option value="Sole Proprietorship">Sole Proprietorship</option>
    <option value="Partnership">Partnership</option>
    <option value="Limited Company">Limited Company</option>
  </select>

  <label>Name of Business:</label>
  <input type="text" name="business_name" class="line-input">

  <label>Years in Operation:</label>
  <input type="number" name="years_operation" class="line-input">

  <label>Registration No:</label>
  <input type="text" name="registration_no" class="line-input">

  <label>Licence No:</label>
  <input type="text" name="licence_no" class="line-input">

  <label>Business Mode:</label>
  <select name="business_mode" class="line-input">
    <option value="Retail">Retail</option>
    <option value="Wholesale">Wholesale</option>
  </select>

  <label>Business Location:</label>
  <input type="text" name="business_location" class="line-input">

  <label>Nearest Landmark:</label>
  <input type="text" name="business_landmark" class="line-input">

  <label>Estimated Business Value (Ksh):</label>
  <input type="number" name="business_value" class="line-input">

  <label>Daily Income (Ksh):</label>
  <input type="number" name="daily_income" class="line-input">

  <div class="section-title">LOAN DETAILS</div>

  <label>Loan Amount Requested (Ksh):</label>
  <input type="number" name="loan_amount" class="line-input" required>

  <label>Loan Amount in Words:</label>
  <input type="text" name="loan_words" class="line-input">

  <label>Purpose of the Loan:</label>
  <input type="text" name="loan_purpose" class="line-input">

  <label>Interest Rate (% per month):</label>
  <input type="number" name="interest_rate" class="line-input">

  <label>Max Repayment Period (Months):</label>
  <input type="number" name="repayment_period" class="line-input">

  <label>Proposed Repayment Plan:</label>
  <input type="text" name="repayment_plan" class="line-input">

  <label>Additional Collateral Description:</label>
  <input type="text" name="collateral_description" class="line-input">

  <br><br>
  <button type="submit">Save and Continue to Page 2</button>
</form>
</body>
</html>
