<?php 
include_once '../includes/conn.php';

if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];
    $sql_orders = "SELECT imagery_details, created_at, image_data FROM xray_test_orders WHERE order_id = '$order_id'";
    $result_orders = mysqli_query($conn, $sql_orders);
}

echo '<h1>Xray</h1>';
if (mysqli_num_rows($result_orders) > 0) {
    echo '<h2>Imagery Details</h2>';
    echo '<div class="image-gallery">';
    while ($row_orders = mysqli_fetch_assoc($result_orders)) {
        echo '<div class="image-item">';
        // Image with onclick event to open modal
        echo '<img class="modal-image" src="../xray/' . $row_orders['image_data'] . '" alt="' . $row_orders['imagery_details'] . '" style="width: 50%; max-width: 300px; border-radius: 5px; cursor: pointer; transition: 0.3s;">';
        echo '<p>Order: ' . $row_orders['imagery_details'] . '</p>';
        echo '<p>Date: ' . $row_orders['created_at'] . '</p>';
        echo '</div>';
    }
    echo '</div>';
} else {
    echo '<p>No xray images found for this patient.</p>';
}
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
body {font-family: Arial, Helvetica, sans-serif;}

/* The Modal (background) */
.modal {
  display: none;
  position: fixed;
  z-index: 1;
  padding-top: 100px;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  overflow: auto;
  background-color: rgba(0, 0, 0, 0.9);
}

/* Modal Content (image) */
.modal-content {
  margin: auto;
  display: block;
  width: 100%;
  max-width: 1200px;
}

/* Caption of Modal Image */
#caption {
  margin: auto;
  display: block;
  width: 80%;
  max-width: 700px;
  text-align: center;
  color: #ccc;
  padding: 10px 0;
  height: 150px;
}

/* Add Animation */
.modal-content, #caption {  
  animation-name: zoom;
  animation-duration: 0.6s;
}

@keyframes zoom {
  from {transform: scale(0)} 
  to {transform: scale(1)}
}

/* The Close Button */
.close {
  position: absolute;
  top: 15px;
  right: 35px;
  color: #fff;
  font-size: 40px;
  font-weight: bold;
  cursor: pointer;
}

.close:hover,
.close:focus {
  color: #ccc;
  text-decoration: none;
}
.zoom-hover {
  transition: transform 0.5s;
}

.zoom-hover:hover {
  transform: scale(1.1);
}


/* 100% Image Width on Smaller Screens */
@media only screen and (max-width: 700px){
  .modal-content {
    width: 100%;
  }
}
</style>
</head>
<body>

<!-- The Modal -->
<div id="myModal" class="modal">
  <span class="close">&times;</span>
  <img class="modal-content" id="modalImg">
  <div id="caption"></div>
</div>

<script>
// Get the modal and image
var modal = document.getElementById("myModal");
var modalImg = document.getElementById("modalImg");

// Get all elements with class="modal-image"
var images = document.getElementsByClassName("modal-image");

// Loop through the images and add an onclick event to each one
for (var i = 0; i < images.length; i++) {
  images[i].onclick = function(){
    modal.style.display = "block"; // Display the modal
    modalImg.src = this.src; // Set the modal image source to the clicked image source
    document.getElementById("caption").innerHTML = this.alt; // Set the caption
  }
}

// Get the close button element
var closeBtn = document.getElementsByClassName("close")[0];

// When the user clicks on the close button, close the modal
closeBtn.onclick = function() { 
  modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}
</script>

</body>
</html>
