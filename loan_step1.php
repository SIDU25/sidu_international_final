<!-- loan_step1.php -->
<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
  <title>SIDU Loan Form - Page 1</title>
  <style>
    body { font-family: 'Times New Roman', serif; margin: 30px; }
    .title { color: blue; font-weight: bold; font-size: 18px; }
    label { display: block; margin-top: 10px; font-weight: bold; }
    input[type="text"], input[type="date"], input[type="email"], input[type="number"] {
      width: 100%; padding: 5px; border: none; border-bottom: 1px solid #000;
    }
    .photo-preview { width: 100px; height: 120px; border: 1px solid #000; object-fit: cover; margin-top: 10px; }
    .left { float: left; width: 70%; }
    .right { float: right; width: 25%; text-align: right; }
    .clear { clear: both; }
  </style>
</head>
<body>
  <form action="loan_step2.php" method="post" enctype="multipart/form-data">
    <div class="right">
      <label for="passport">Passport Photo:</label>
      <input type="file" name="passport" accept="image/*" onchange="previewImage(event, 'passportPreview')" required>
      <img id="passportPreview" class="photo-preview" src="#" alt="Preview">
    </div>

    <div class="left">
      <div>
        <label>NO. …………………………………………. Date: <input type="date" name="date" required></label>
      </div>
      <div class="title">APPLICANT’S PARTICULARS</div>
      <label>Applicant's Name: <input type="text" name="applicant_name" required></label>
      <label>ID No. / Passport No: <input type="text" name="id_number" required></label>
      <label>Nationality: <input type="text" name="nationality" required></label>
      <label>Town: <input type="text" name="town" required></label>
      <label>Phone No: <input type="text" name="phone" required></label>
      <label>Alternative No: <input type="text" name="alt_phone"></label>
      <label>Email: <input type="email" name="email"></label>
      <label>Marital Status: <input type="text" name="marital_status"></label>
      <label>Name of Spouse: <input type="text" name="spouse_name"></label>
      <label>Spouse ID No: <input type="text" name="spouse_id"></label>
      <label>Spouse Phone No: <input type="text" name="spouse_phone"></label>
      <label>Alternative No: <input type="text" name="spouse_alt_phone"></label>
      <label>Applicant Signature: ________________________</label>

      <div class="title">RESIDENTIAL DETAILS</div>
      <label>Location: <input type="text" name="location" required></label>
      <label>Plot No / Name: <input type="text" name="plot_name"></label>
      <label>House No: <input type="text" name="house_no"></label>
      <label>Nearest Landmark: <input type="text" name="landmark"></label>
      <label>Gate Colour: <input type="text" name="gate_color"></label>

      <div class="title">BUSINESS INFORMATION</div>
      <label>Business Ownership:
        <select name="ownership">
          <option>Family Business</option>
          <option>Sole Proprietorship</option>
          <option>Partnership</option>
          <option>Limited Company</option>
        </select>
      </label>
      <label>Business Name: <input type="text" name="business_name"></label>
      <label>Years in Operation: <input type="text" name="years"></label>
      <label>Registration No: <input type="text" name="reg_no"></label>
      <label>Licence No: <input type="text" name="license_no"></label>
      <label>Mode: 
        <select name="mode"><option>Retail</option><option>Wholesale</option></select>
      </label>
      <label>Business Location: <input type="text" name="business_location"></label>
      <label>Nearest Landmark: <input type="text" name="business_landmark"></label>
      <label>Estimated Business Value (Ksh): <input type="number" name="value"></label>
      <label>Daily Income (Ksh): <input type="number" name="daily_income"></label>
    </div>

    <div class="clear"></div>
    <br><br>
    <button type="submit">Save and Continue</button>
  </form>

  <script>
    function previewImage(event, targetId) {
      const output = document.getElementById(targetId);
      output.src = URL.createObjectURL(event.target.files[0]);
      output.onload = () => URL.revokeObjectURL(output.src);
    }
  </script>
</body>
</html>
