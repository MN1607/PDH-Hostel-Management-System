<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDH Hostel Mess Fees Payment</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #a18cd1, #fbc2eb);
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }
        .container {
            max-width: 900px;
            margin: 50px auto;
            background: #fff;
            padding: 40px;
            border-radius: 25px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2);
            animation: zoomIn 0.8s ease-out;
            position: relative; /* For positioning the back button */
        }
        header {
            text-align: center;
            padding-bottom: 25px;
            border-bottom: 4px solid #8e44ad;
            animation: slideInFromTop 0.8s ease-out;
        }
        header h1 {
            margin: 0;
            color: #8e44ad;
            font-size: 3em;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
        header p {
            margin: 10px 0;
            font-size: 1.4em;
            color: #333;
        }
        .intro {
            text-align: center;
            margin: 30px 0;
            font-size: 1.2em;
            color: #555;
            animation: fadeIn 1.2s ease-in-out;
        }
        .payment-form {
            display: flex;
            flex-direction: column;
            gap: 30px;
        }
        .payment-form h2 {
            color: #333;
            font-size: 2em;
            border-bottom: 3px solid #8e44ad;
            padding-bottom: 10px;
            animation: slideInFromLeft 0.8s ease-out;
        }
        .form-group {
            display: flex;
            flex-direction: column;
            gap: 10px;
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
            padding: 14px;
            border: 2px solid #ddd;
            border-radius: 10px;
            font-size: 1.1em;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }
        .form-group input:focus, .form-group select:focus {
            border-color: #8e44ad;
            box-shadow: 0 0 12px rgba(142, 68, 173, 0.3);
            outline: none;
        }
        #mess-fee-amount {
            background: #f9f1ff;
            padding: 14px;
            border-radius: 10px;
            font-weight: bold;
            color: #8e44ad;
            font-size: 1.3em;
            text-align: center;
            animation: bounceIn 1s ease-out;
        }
        .due-date-marquee {
            background: #f9f1ff;
            padding: 12px;
            border-radius: 10px;
            border: 2px solid #8e44ad;
            font-weight: bold;
            color: #8e44ad;
            font-size: 1.2em;
            overflow: hidden;
            white-space: nowrap;
            animation: fadeIn 1.5s ease-in-out;
        }
        marquee {
            width: 100%;
        }
        .payment-options {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        .payment-options label {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 1.1em;
            color: #444;
            transition: color 0.3s ease;
        }
        .payment-options label:hover {
            color: #8e44ad;
        }
        .submit-btn {
            background: linear-gradient(90deg, #8e44ad, #3498db);
            color: #fff;
            padding: 18px;
            border: none;
            border-radius: 12px;
            font-size: 1.3em;
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .submit-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 25px rgba(142, 68, 173, 0.5);
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
            text-align: center;
            margin-top: 40px;
            font-size: 0.9em;
            color: #666;
            animation: fadeIn 2s ease-in-out;
        }
        footer a {
            color: #8e44ad;
            text-decoration: none;
        }
        footer a:hover {
            text-decoration: underline;
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
        @keyframes zoomIn {
            from { transform: scale(0.9); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }
        @keyframes bounceIn {
            0% { transform: scale(0.8); opacity: 0; }
            60% { transform: scale(1.1); opacity: 1; }
            100% { transform: scale(1); }
        }

        @media (max-width: 600px) {
            .container {
                margin: 20px;
                padding: 25px;
            }
            header h1 {
                font-size: 2.2em;
            }
            .payment-form h2 {
                font-size: 1.6em;
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
            <h1>Mess Fees Payment Portal</h1>
            <p><strong>PDH Hostel</strong> - Secure Mess Fee Payments</p>
        </header>

        <section class="intro">
            <p>Pay your mess fees conveniently and securely.</p>
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

            <h2>Payment Details</h2>
            <div class="form-group">
                <label>Mess Fee Amount (₹)</label>
                <div id="mess-fee-amount">₹1800</div>
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
            <p>For assistance, contact: <a href="mailto:support@pdhhostel.com">support@pdhhostel.com</a></p>
        </footer>
    </div>

    <script>
        const messFeeAmount = document.getElementById('mess-fee-amount');
        const dueDateMarquee = document.getElementById('dynamic-due-date');

        // Load stored values or set defaults
        const storedFee = localStorage.getItem('messFee');
        const storedDueDate = localStorage.getItem('dueDate');
        if (storedFee) {
            messFeeAmount.textContent = `₹${storedFee}`;
        } else {
            messFeeAmount.textContent = '₹1800';
        }
        if (storedDueDate) {
            dueDateMarquee.textContent = `Due Date: ${storedDueDate}`;
        } else {
            dueDateMarquee.textContent = 'Due Date: March 31, 2025';
        }
    </script>
</body>
</html>