<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Hsin-Hui Chu">
    <title>Therapist Journal</title>
    <link rel="stylesheet" href="../style/global.css">
    <link rel="stylesheet" href="../style/therapistJournal.css">

</head>

<body>
    <!--php code-->
    
    <!-- global navigation bar -->
    <header class="navbar">
        <a href="therapistDashboard.html"><img src="../image/logo.png" alt="Logo Icon" id="logo-icon"></a>
    </header>
    <div class="therapistContainer">
        <div class="leftbox">
            <!-- should be selected patient journal list page -->
            <a href="historyJournalList.html">
                <button class="back-btn">Back</button>
            </a>
        </div>
        <div class="container">
            <div class="entry-header">John Smith</div>
            <div class="entry-date">Date: 13/08/2024</div>
            <div class="entry-content">
                Today I didn't feel well, because it's a raining day.
                It's really easy for me to be affected by the weather.
                I'm always thinking about the meaning of life, and I didn't want to work. Sometimes I cry silently...
                Anyway, I hope there will be a sunny day tomorrow.
            </div>
            <div class="details">
                <label>Sleeping Time:</label>
                <div>23:30 - 07:30</div>
                <label>Food:</label>
                <div>Pizza, Sandwich</div>
                <label>Exercise:</label>
                <div>none</div>
            </div>
            <div class="download">
                <label>Attached File:</label>
                <div>hamburger.png</div>
                <!--download button-->
                <a href="starIcon.png" download="hamburger.png"><button>Download</button></a>
            </div>

            <span class="star" id="starIcon">☆</span>
        </div>
        <div class="rightbox"></div>
    </div>
    <script src="../scripts/journal.js"></script>
    <footer class="site-footer">
        <p>&copy; 2024 CaRe | All Rights Reserved</p>
    </footer>
</body>

</html>