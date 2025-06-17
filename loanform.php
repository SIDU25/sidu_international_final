<?php
session_start();

// Simulate logged-in user data from session (replace with actual session data)
$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'John Doe Mwangi';
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '12345678';
$user_phone = isset($_SESSION['user_phone']) ? $_SESSION['user_phone'] : '+254712345678';
?>

<!-- LOAN APPLICATION FORM START -->
<div class="container">
    <div class="header">
        <h1>SIDU INTERNATIONAL LTD</h1>
        <p>Professional Loan Application Form</p>
    </div>

    <div class="form-content">
        <form id="loanApplicationForm" method="POST" action="submit_loan.php" enctype="multipart/form-data">
            <div class="section-title">📋 APPLICATION DETAILS</div>
            <div class="form-row">
                <div class="form-group">
                    <label>Application No.</label>
                    <input type="text" id="app_number" name="app_number" value="<?php echo 'SIDU' . substr(time(), -8); ?>" readonly>
                </div>
                <div class="form-group">
                    <label>Date</label>
                    <input type="date" id="app_date" name="app_date" value="<?php echo date('Y-m-d'); ?>" readonly>
                </div>
            </div>

            <div class="section-title">👤 APPLICANT'S PARTICULARS</div>
            <div class="form-group">
                <label for="applicant_name">Full Name *</label>
                <input type="text" id="applicant_name" name="applicant_name" value="<?php echo htmlspecialchars($user_name); ?>" readonly required>
            </div>

            <div class="form-row-three">
                <div class="form-group">
                    <label for="id_number">ID/Passport Number *</label>
                    <input type="text" id="id_number" name="id_number" value="<?php echo htmlspecialchars($user_id); ?>" readonly required>
                </div>
                <div class="form-group">
                    <label for="nationality">Nationality *</label>
                    <select id="nationality" name="nationality" required>
                        <option value="">Select Nationality</option>
                        <option value="Kenyan">Kenyan</option>
                        <option value="Ugandan">Ugandan</option>
                        <option value="Tanzanian">Tanzanian</option>
                        <option value="Rwandan">Rwandan</option>
                        <option value="Burundian">Burundian</option>
                        <option value="Ethiopian">Ethiopian</option>
                        <option value="Somali">Somali</option>
                        <option value="Sudanese">Sudanese</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="town">Town/City *</label>
                    <input type="text" id="town" name="town" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="phone_number">Phone Number *</label>
                    <input type="tel" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($user_phone); ?>" pattern="^(\+254|0)[7][0-9]{8}$" readonly required>
                </div>
                <div class="form-group">
                    <label for="alt_phone">Alternative Phone Number *</label>
                    <input type="tel" id="alt_phone" name="alt_phone" pattern="^(\+254|0)[7][0-9]{8}$" required>
                </div>
            </div>

            <!-- ... continue with rest of your form as needed ... -->

            <button type="submit" class="submit-btn" id="submitBtn">
                🚀 Submit Loan Application
            </button>
        </form>
    </div>
</div>
<!-- LOAN APPLICATION FORM END -->
