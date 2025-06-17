<!-- loan-form-page2.php -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Loan Form - Page 2</title>
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
    .line-input {
      display: inline-block;
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
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 15px;
    }
    th, td {
      border: 1px solid #000;
      padding: 6px;
      text-align: center;
    }
  </style>
</head>
<body>

<form class="page" action="loan-form-page3.php" method="post">
  <h2>Page 2: Declaration & Guarantors</h2>

  <div class="section-title">APPLICANT DECLARATION</div>
  <p>
    I <input type="text" name="declaration_name" class="line-input"> the undersigned applicant, hereby DECLARE that all the Information provided in this loan application is true, accurate and complete to the best of my knowledge.
  </p>
  <p>
    I understand and accept the terms and conditions of this loan, including the repayment obligations, interest rates and penalties for default.
  </p>
  <p>
    I also understand that any FALSE or misleading information may result in disqualification or legal action.
  </p>

  <label>Signature of the applicant:</label>
  <input type="text" name="applicant_signature" class="line-input">

  <label>ID Number:</label>
  <input type="text" name="declaration_id" class="line-input">

  <label>Date:</label>
  <input type="date" name="declaration_date" class="line-input">

  <div class="section-title">GUARANTOR DECLARATION</div>
  <p>
    I/We , the UNDERSIGNED agree to act as guarantor(s) of the above loan. I/We confirm that I have reviewed the terms of the loan agreement and understand that if the borrower fails to repay the loan, I will be fully liable to repay the outstanding balance including interests and penalties.
  </p>

  <table>
    <thead>
      <tr>
        <th>NO</th>
        <th>GUARANTOR NAME</th>
        <th>ID NO</th>
        <th>PHONE NO</th>
        <th>RELATIONSHIP</th>
        <th>SIGNATURE</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>1</td>
        <td><input type="text" name="guarantor1_name" class="line-input"></td>
        <td><input type="text" name="guarantor1_id" class="line-input"></td>
        <td><input type="text" name="guarantor1_phone" class="line-input"></td>
        <td><input type="text" name="guarantor1_relation" class="line-input"></td>
        <td><input type="text" name="guarantor1_signature" class="line-input"></td>
      </tr>
      <tr>
        <td>2</td>
        <td><input type="text" name="guarantor2_name" class="line-input"></td>
        <td><input type="text" name="guarantor2_id" class="line-input"></td>
        <td><input type="text" name="guarantor2_phone" class="line-input"></td>
        <td><input type="text" name="guarantor2_relation" class="line-input"></td>
        <td><input type="text" name="guarantor2_signature" class="line-input"></td>
      </tr>
      <tr>
        <td>3</td>
        <td><input type="text" name="guarantor3_name" class="line-input"></td>
        <td><input type="text" name="guarantor3_id" class="line-input"></td>
        <td><input type="text" name="guarantor3_phone" class="line-input"></td>
        <td><input type="text" name="guarantor3_relation" class="line-input"></td>
        <td><input type="text" name="guarantor3_signature" class="line-input"></td>
      </tr>
    </tbody>
  </table>

  <br>
  <button type="submit">Save and Continue to Page 3</button>
</form>

</body>
</html>
