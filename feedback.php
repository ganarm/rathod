<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" type="image/png" href="assets/tablogo.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.4.0/fonts/remixicon.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Logica (GM): Feedback</title>
</head>

<body id="body">
    <nav>
        <div class="nav-logo">
            <a href="home.php"><img src="assets/logo.png" alt="Logica"></a>
        </div>
        <ul class="nav-list">
            <li><a href="home.php">Home</a></li>
            <li><a href="cmpyin.php">Company</a></li>
            <li><a href="cali_vali_in.php">Work</a></li>
            <li><a href="cali_vali_in.php" class="active">Feedback</a></li>
        </ul>
        <div class="theme">
            <label>
                <input type="radio" name="theme" id="black">
                <span class="custom-radio" style="background-color: #1b1b1b;"></span>
            </label>
            <label>
                <input type="radio" name="theme" id="green">
                <span class="custom-radio" style="background-color: #522a61;"></span>
            </label>
            <label>
                <input type="radio" name="theme" id="blue">
                <span class="custom-radio" style="background-color: #8E2157;"></span>
            </label>
        </div>
    </nav>
    <section class="feedback-container">
        <form id="feedbackForm" action="https://api.web3forms.com/submit" method="POST">
            <input type="hidden" name="access_key" value="1898e4cc-6c2e-43bc-b0d9-5847213644cd">
            <input type="text" name="name" placeholder="Your Name">
            <input type="email" name="email" placeholder="Your Email">
            <textarea name="message" placeholder="Your Message"></textarea>
            <button type="submit">Submit</button>
        </form>
    </section>
    <?php include "footer.html"; ?>
    <script>
        document.getElementById('feedbackForm').addEventListener('submit', function(event) {
            var name = document.getElementsByName('name')[0].value;
            var email = document.getElementsByName('email')[0].value;
            var message = document.getElementsByName('message')[0].value;
    
            if (!name || !email || !message) {
                alert('Please fill out all fields.');
                event.preventDefault(); // Prevent form submission
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Check if there's a saved theme in localStorage
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme) {
                applyTheme(savedTheme);
                document.getElementById(savedTheme).checked = true;
            }

            // Listen for changes in theme selection
            const radios = document.querySelectorAll('input[name="theme"]');
            radios.forEach(radio => {
                radio.addEventListener('change', () => {
                    if (radio.checked) {
                        applyTheme(radio.id);
                        localStorage.setItem('theme', radio.id);
                    }
                });
            });

            function applyTheme(theme) {
                if (theme === 'black') {
                    document.documentElement.style.setProperty('--primary-color', '#1b1b1b'); // Example Red Color
                    document.documentElement.style.setProperty('--secondary-color', '#fff'); // White
                } else if (theme === 'green') {
                    document.documentElement.style.setProperty('--primary-color', '#522a61'); // Example Green Color
                    document.documentElement.style.setProperty('--secondary-color', '#fff'); // White
                } else if (theme === 'blue') {
                    document.documentElement.style.setProperty('--primary-color', '#8E2157'); // Example Blue Color
                    document.documentElement.style.setProperty('--secondary-color', '#fff'); // White
                }
            }
        });
    </script>
</body>

</html>