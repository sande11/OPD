<?php
session_start();

if (isset($_SESSION['name'])) {
    $userName = $_SESSION['name'];
    $departmentName = $_SESSION['department'];
} else {
    header("Location: Auth.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>265 OutPatient</title>
    <link rel="stylesheet" type="text/css" href="\265 OPD\style\doctorsoffice.css">
    <link rel="stylesheet" href="\265 OPD\css\all.css">
    <script src="../java.js"></script>
    <link rel="stylesheet" href="fullcalendar/fullcalendar.min.css" />
    <script src="fullcalendar/lib/jquery.min.js"></script>
    <script src="fullcalendar/lib/moment.min.js"></script>
    <script src="fullcalendar/fullcalendar.min.js"></script>
    <script>
        
        $(document).ready(function() {
            var calendar = $('#calendar').fullCalendar({
                editable: true,
                events: "fetch-event.php",
                displayEventTime: false,
                eventRender: function(event, element, view) {
                    if (event.allDay === 'true') {
                        event.allDay = true;
                    } else {
                        event.allDay = false;
                    }
                },
                selectable: true,
                selectHelper: true,
                select: function(start, end, allDay) {
                    var title = prompt('Event Title:');
                    var details = prompt('Event Details:');
                    var patientName = prompt('Patient Name:');
                    var subject = "<?php echo $userName; ?>";

                    if (title) {
                        var start = $.fullCalendar.formatDate(start, "Y-MM-DD HH:mm:ss");
                        var end = $.fullCalendar.formatDate(end, "Y-MM-DD HH:mm:ss");

                        $.ajax({
                            url: 'add-event.php',
                            data: 'title=' + title + '&start=' + start + '&end=' + end + '&subject=' + subject + '&patientName=' + patientName + '&details=' + details,
                            type: "POST",
                            success: function(data) {
                                displayMessage("Added Successfully");
                                }
                        });
                        calendar.fullCalendar('renderEvent', {
                                title: title,
                                start: start,
                                end: end,
                                allDay: allDay
                            },
                            true
                        );
                    }
                    calendar.fullCalendar('unselect');
                },
                
                editable: true,
                eventDrop: function(event, delta) {
                    var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
                    var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");
                    $.ajax({
                        url: 'edit-event.php',
                        data: 'title=' + event.title + '&start=' + start + '&end=' + end + '&id=' + event.id,
                        type: "POST",
                        success: function(response) {
                            displayMessage("Updated Successfully");
                        }
                    });
                },
                eventClick: function(event) {
                    var deleteMsg = confirm("Do you really want to delete?");
                    if (deleteMsg) {
                        $.ajax({
                            type: "POST",
                            url: "delete-event.php",
                            data: "&id=" + event.id,
                            success: function(response) {
                                if (parseInt(response) > 0) {
                                    $('#calendar').fullCalendar('removeEvents', event.id);
                                    displayMessage("Deleted Successfully");
                                }
                            }
                        });
                    }
                }

            });
        });
    

        function displayMessage(message) {
            $(".response").html("<div class='success'>" + message + "</div>");
            setInterval(function() {
                $(".success").fadeOut();
            }, 1000);
        }

        
    </script>

    <style>

        #calendar {
            width: 700px;
            height: 500px;
            margin-left: 30px; 
        }

        .response {
            height: 60px;
        }

        .success {
            background: #cdf3cd;
            padding: 10px 60px;
            border: #c3e6c3 1px solid;
            display: inline-block;
        }
        .events {
            position: absolute;
            right: 0;
            top: 80px;
            width: 20%;
            padding: 20px;
            background-color: white;
            box-shadow: 0px 0px 5px 0px rgba(0,0,0,0.2);
            }
      
    </style> 
  </head>
  <body>
    <div class="overlay">
      <!-- left bar  -->
      <section>
        <div class="left">
          <div class="logo">
            <img src="../calendar/cardiogram 2.png" alt="logo" />
            <p class="logo-name"><span>265</span>OutPatient </p>
          </div>
          <br />
          <div class="side-line"></div>
          <!-- nav -->
          <ul class="nav">
          <li>
              <a href="\265 OPD\User\doctorsoffice.php"><i class="fa-solid fa-caret-left"></i>Doctor's Office</a>
            </li>
            <!-- <li><a href="calendar/index.php"><i class="fa-solid fa-calendar-days"></i> Schedule</a></li>
            <li><a href="\265 OPD\User\testresults.php"><i class="fa-solid fa-microscope"></i> Test Results</a></li>
         -->
          </ul>

          <br /><br />
          <div class="fill" style="margin-top: 210px;">
            <img src="../calendar/undraw_medicine_b1ol.png" />
          </div>
        </div>
        <br />
        <div class="right-div">
          <div id="main">
            <br />
            <div class="head">
              <div class="col-1">
                <p class="nav-title">Schedule</p>

                <div class="user-profile">
                  <div onclick="myFunction()" class="user">
                    <i class="fa fa-user"></i><?php echo $userName ?>
                  </div>
                  <div id="myDropdown" class="dropdown-content">
                    <a href="#"
                      ><i class="fa-regular fa-address-card"></i> Profile</a
                    >
                    <a href="../logout.php"
                      ><i class="fa-solid fa-right-from-bracket"></i> Logout</a
                    >
                  </div>
                </div>
              </div>
              <br />
              <br />
              <div class="line"></div>
        <br>
        <br>
        <br>


    <!-- <h1 style=" margin-top: -20px;">Appointments</h1> -->

    <div class="response"></div>

    <!-- <div style="margin-left: 893px; margin-top:-100px; margin-bottom: 40px;">    
        <form method="post" action="" id="lab_status_form">
            <label>Filter</label>
            <select name="test_date" id="test_date" style="padding:6px; background-color:#f0a732;" onchange="document.getElementById('lab_status_form').submit()">
                <option value="Today">Today</option>
                <option value="This Week">This Week</option>
                <option value="This Month">This Month</option>
                <option value="All Tests">All Tests</option>
            </select>
            </form>
        </div> -->

    <div id='calendar' style=" margin-top: -80px;"></div>
<?php

require_once "db.php";

if (!isset($_SESSION['last_refresh'])) {
    // If not, set it to the current timestamp
    $_SESSION['last_refresh'] = time();
}

// Get current date
$currentDate = date("Y-m-d");

// Fetch events from appointments table for the current date
$sql = "SELECT * FROM appointments WHERE DATE(start) = '$currentDate'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Store fetched results in a variable
    $eventsHtml = '<div class="events"><h2>Events</h2><div class="events-list"><ul>';
    while($row = $result->fetch_assoc()) {
        $eventsHtml .= '<li>Title: ' . $row["title"] . ' <br>Patient Name: ' . $row["patientName"] . ' <br>Details: ' . $row["details"] . '</li>';
    }
    $eventsHtml .= '</ul></div></div>';
} else {
    $eventsHtml = "<div style='margin-top: 40px; margin-right: -100px; display: block;'>No events found for the current date.</div>";
}

// Update last refresh timestamp
$_SESSION['last_refresh'] = time();
    $conn->close();
    
?>  
<script>
        function refreshEvents() {
            // Fetch the events using AJAX
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    // Update the events div with the fetched events
                    document.getElementById("events").innerHTML = this.responseText;
                }
            };
            xhttp.open("GET", "fetch_event.php", true);
            xhttp.send();
        }

        // Refresh events every 24 hours
        setInterval(refreshEvents, 24 * 60 * 60 * 1000);
    </script>

    <div id="events" style="float: right; margin-top: -470px; margin-right: 125px;">
        <?php echo $eventsHtml; ?>
    </div>
</body>
</html>
