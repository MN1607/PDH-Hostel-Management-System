<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDH Hostel Fee Payment Portal</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #e0eafc, #cfdef3);
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }
        .container {
            max-width: 900px;
            margin: 50px auto;
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            animation: fadeIn 1s ease-in-out;
            position: relative; /* For positioning the back button */
        }
        header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 3px solid #007bff;
            animation: slideInFromTop 0.8s ease-out;
        }
        header h1 {
            margin: 0;
            color: #007bff;
            font-size: 2.5em;
            letter-spacing: 1px;
        }
        header p {
            margin: 10px 0;
            font-size: 1.2em;
            color: #333;
        }
        .intro {
            text-align: center;
            margin: 30px 0;
            font-size: 1.1em;
            color: #555;
            animation: fadeIn 1.5s ease-in-out;
        }
        .payment-form {
            display: flex;
            flex-direction: column;
            gap: 25px;
        }
        .payment-form h2 {
            color: #333;
            font-size: 1.6em;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
            animation: slideInFromLeft 0.8s ease-out;
        }
        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
            transition: transform 0.3s ease;
        }
        .form-group:hover {
            transform: translateY(-5px);
        }
        .form-group label {
            font-weight: 600;
            color: #444;
        }
        .form-group input, .form-group select {
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 1em;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }
        .form-group input:focus, .form-group select:focus {
            border-color: #007bff;
            box-shadow: 0 0 8px rgba(0, 123, 255, 0.3);
            outline: none;
        }
        .due-date-marquee {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #007bff;
            font-weight: bold;
            color: #007bff;
            font-size: 1.1em;
            overflow: hidden;
            white-space: nowrap;
        }
        marquee {
            width: 100%;
        }
        .payment-options {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        .payment-options label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 1em;
            color: #444;
            transition: color 0.3s ease;
        }
        .payment-options label:hover {
            color: #007bff;
        }
        .submit-btn {
            background: linear-gradient(90deg, #007bff, #00c4ff);
            color: #fff;
            padding: 15px;
            border: none;
            border-radius: 10px;
            font-size: 1.2em;
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .submit-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(0, 123, 255, 0.4);
        }
        .back-btn {
            position: absolute;
            top: 20px;
            left: 20px;
            background: linear-gradient(90deg, #7f8c8d, #95a5a6);
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-size: 1em;
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .back-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 5px 15px rgba(127, 140, 141, 0.4);
        }
        footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 0.95em;
            color: #666;
            animation: fadeIn 2s ease-in-out;
        }
        footer h3 {
            margin-top: 0;
            color: #333;
        }
        footer ul {
            list-style-type: none;
            padding: 0;
        }
        footer ul li {
            margin: 10px 0;
            position: relative;
            padding-left: 20px;
        }
        footer ul li:before {
            content: "✔";
            position: absolute;
            left: 0;
            color: #28a745;
        }
        .security-notice {
            margin-top: 15px;
            font-weight: bold;
            color: #28a745;
            font-size: 1em;
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes slideInFromTop {
            from { transform: translateY(-50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        @keyframes slideInFromLeft {
            from { transform: translateX(-50px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        @media (max-width: 600px) {
            .container {
                margin: 20px;
                padding: 20px;
            }
            header h1 {
                font-size: 2em;
            }
            .back-btn {
                top: 15px;
                left: 15px;
                padding: 8px 15px;
                font-size: 0.9em;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <button class="back-btn" onclick="history.back()">Go Back</button>
        <header>
            <h1>Hostel Fee Payment Portal</h1>
            <p><strong>PDH Hostel</strong> - Secure Online Payments</p>
        </header>

        <section class="intro">
            <p>Welcome to the PDH Hostel Payment System. Pay your hostel fees quickly and securely.</p>
        </section>

        <form class="payment-form">
            <h2>Student Information</h2>
            <div class="form-group">
                <label for="full-name">Full Name</label>
                <input type="text" id="full-name" name="full-name" required>
            </div>
            <div class="form-group">
                <label for="student-id">Student ID</label>
                <input type="text" id="student-id" name="student-id" required>
            </div>
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="contact">Contact Number (Optional)</label>
                <input type="tel" id="contact" name="contact">
            </div>

            <h2>Payment Details</h2>
            <div class="form-group">
                <label for="fee-type">Fee Type</label>
                <select id="fee-type" name="fee-type" required>
                    <option value="">Select Fee Type</option>
                    <option value="monthly">Monthly Fees</option>
                    <option value="semester">Semester Fees</option>
                    <option value="annual">Annual Fees</option>
                </select>
            </div>
            <div class="form-group">
                <label for="amount">Amount Due (₹)</label>
                <input type="number" id="amount" name="amount" required>
            </div>
            <div class="form-group">
                <label>Due Date</label>
                <div class="due-date-marquee">
                    <marquee behavior="scroll" direction="left" scrollamount="5" id="dynamic-due-date">
                        Due Date: March 31, 2025
                    </marquee>
                </div>
            </div>

            <h2>Payment Method</h2>
            <div class="payment-options">
                <label><input type="radio" name="payment-method" value="card" required> Credit/Debit Card</label>
                <label><input type="radio" name="payment-method" value="bank"> Bank Transfer</label>
                <label><input type="radio" name="payment-method" value="upi"> UPI/Wallets</label>
            </div>

            <button type="submit" class="submit-btn">Proceed to Payment</button>
        </form>

        <footer>
            <h3>Terms & Conditions</h3>
            <ul>
                <li>Payments are non-refundable after the due date.</li>
                <li>Ensure all details are correct before submission.</li>
                <li>For assistance, contact us at: <a href="mailto:support@pdhhostel.com">support@pdhhostel.com</a> or call +91-XXX-XXX-XXXX.</li>
            </ul>
            <p class="security-notice">🔒 Your payment is protected with 256-bit SSL encryption.</p>
        </footer>
    </div>

    <script>
        const dueDateElement = document.getElementById('dynamic-due-date');
        const storedDueDate = localStorage.getItem('dueDate');
        if (storedDueDate) {
            dueDateElement.textContent = `Due Date: ${storedDueDate}`;
        } else {
            dueDateElement.textContent = `Due Date: March 31, 2025`; // Default value
        }
    </script>
</body>
</html>