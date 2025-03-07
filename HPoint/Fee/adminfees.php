<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDH Hostel Admin Panel</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #d4fc79, #96e6a1);
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }
        .container {
            max-width: 700px;
            margin: 50px auto;
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            animation: fadeIn 1s ease-in-out;
            position: relative; /* For positioning the back button */
        }
        h1 {
            text-align: center;
            color: #28a745;
            font-size: 2.5em;
            letter-spacing: 1px;
            animation: slideInFromTop 0.8s ease-out;
        }
        #current-due-date {
            text-align: center;
            font-weight: bold;
            color: #333;
            font-size: 1.2em;
            margin: 20px 0;
            animation: fadeIn 1.5s ease-in-out;
        }
        #display-due-date {
            color: #28a745;
        }
        .form-group {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin: 30px 0;
            transition: transform 0.3s ease;
        }
        .form-group:hover {
            transform: translateY(-5px);
        }
        label {
            font-weight: 600;
            color: #444;
            font-size: 1.1em;
        }
        input[type="date"] {
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 1em;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }
        input[type="date"]:focus {
            border-color: #28a745;
            box-shadow: 0 0 8px rgba(40, 167, 69, 0.3);
            outline: none;
        }
        button {
            background: linear-gradient(90deg, #28a745, #34d058);
            color: #fff;
            padding: 15px;
            border: none;
            border-radius: 10px;
            font-size: 1.2em;
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        button:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
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

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes slideInFromTop {
            from { transform: translateY(-50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.02); }
            100% { transform: scale(1); }
        }
        #current-due-date {
            animation: pulse 2s infinite;
        }

        @media (max-width: 600px) {
            .container {
                margin: 20px;
                padding: 20px;
            }
            h1 {
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
        <h1>PDH Hostel Admin Panel</h1>
        <p id="current-due-date">Current Due Date: <span id="display-due-date">March 31, 2025</span></p>
        
        <div class="form-group">
            <label for="due-date">Update Due Date</label>
            <input type="date" id="due-date" name="due-date">
        </div>
        
        <button onclick="updateDueDate()">Save Changes</button>
    </div>

    <script>
        const displayDueDate = document.getElementById('display-due-date');
        const storedDueDate = localStorage.getItem('dueDate');
        if (storedDueDate) {
            displayDueDate.textContent = storedDueDate;
        }

        function updateDueDate() {
            const newDueDate = document.getElementById('due-date').value;
            if (newDueDate) {
                const formattedDate = new Date(newDueDate).toLocaleDateString('en-US', {
                    month: 'long',
                    day: 'numeric',
                    year: 'numeric'
                });
                localStorage.setItem('dueDate', formattedDate);
                displayDueDate.textContent = formattedDate;
                alert('Due date updated successfully!');
            } else {
                alert('Please select a valid date.');
            }
        }
    </script>
</body>
</html>