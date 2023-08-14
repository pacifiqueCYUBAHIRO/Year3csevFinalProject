<?php
if (isset($_GET["username"])) {
  $selectedUsername = $_GET["username"];
  
  $select_query = "SELECT * FROM employees WHERE username=?";
  $stmt = $conn->prepare($select_query);
  $stmt->bind_param("s", $selectedUsername);
  $stmt->execute();
  
  $result = $stmt->get_result();
  $employeeData = $result->fetch_assoc();
  
  echo json_encode($employeeData);
} else {
  echo json_encode(array());
}
?>
