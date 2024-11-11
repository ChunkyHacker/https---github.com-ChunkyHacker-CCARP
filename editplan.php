<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit project</title>
</head>
<?php
include('config.php');

// Retrieve the project to be edited using projectID
if (isset($_GET['projectID'])) {
    $projectID = $_GET['projectID'];
    $query = "SELECT * FROM projects WHERE projectID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $projectID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $project = $result->fetch_assoc();
    } else {
        // Handle error if project is not found
    }

    $stmt->close();
}

// Handle update logic when the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $projectID = $_POST['projectID']; 
    $editedName = $_POST["Name"];
    $editedStartDate = $_POST["StartDate"];
    $editedEndDate = $_POST["EndDate"];
    $editedDescription = $_POST["Description"];
    $editedStatus = $_POST["Status"];
    $editedBudget = $_POST["Budget"];
    $editedLocation = $_POST["Location"];
    $editedWorkers = $_POST["WorkersDetails"];
    $editedClient = $_POST["ClientDetails"];
    $editedSupplier = $_POST["SupplierDetails"];
    $editedProjectRequest = $_POST["ProjectRequest"];
    $editedSummaryText = $_POST["SummaryText"];

    // Update the database with the edited information
    $updateQuery = "UPDATE projects SET 
                    Name = ?, StartDate = ?, EndDate = ?, Description = ?, 
                    Status = ?, Budget = ?, Location = ?, WorkersDetails = ?, 
                    ClientDetails = ?, SupplierDetails = ?, ProjectRequest = ?, SummaryText = ?
                    WHERE projectID = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param(
        "ssssssssssssi",
        $editedName, $editedStartDate, $editedEndDate, $editedDescription,
        $editedStatus, $editedBudget, $editedLocation, $editedWorkers,
        $editedClient, $editedSupplier, $editedProjectRequest, $editedSummaryText,
        $projectID
    );

    if ($stmt->execute()) {
        // Redirect back to plan.php after the update
        header("Location: plan.php");
        exit;
    } else {
        echo "Error updating project: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
<style>
    body {
        font-family: Arial, sans-serif;
    }
    
    #plan-form {
        max-width: 600px;
        margin: auto;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 5px;
        background-color: #f8f8f8;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    #plan-form label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }

    #plan-form input[type="text"],
    #plan-form input[type="date"],
    #plan-form textarea,
    #plan-form select {
        width: 100%;
        padding: 8px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 3px;
        font-size: 14px;
        font-family: Arial, sans-serif;
    }

    #plan-form select {
        appearance: none;
        padding-right: 30px;
        background: url('arrow.png') no-repeat right center;
        background-size: 20px;
    }

    #plan-form input[type="submit"],
    #plan-form input[type="button"] {
        margin-right: 10px;
        padding: 10px 20px;
        border-radius: 3px;
        font-size: 16px;
        cursor: pointer;
    }

    #plan-form input[type="submit"] {
        background-color: #007bff;
        color: #fff;
        border: none;
    }

    #plan-form input[type="submit"]:hover {
        background-color: #0056b3;
    }

    #plan-form input[type="button"] {
        background-color: #ccc;
        color: #333;
        border: none;
    }

    #plan-form input[type="button"]:hover {
        background-color: #999;
    }
</style>
<body>
<form id="plan-form" action="editplan.php?projectID=<?php echo $projectID; ?>" method="post">
    <label for="Name">Name:</label>
    <input type="text" name="Name" value="<?php echo $project['Name']; ?>" required>
    
    <label for="StartDate">Start Date:</label>
    <input type="date" id="StartDate" name="StartDate" value="<?php echo $project['StartDate']; ?>" required>
    
    <label for="EndDate">End Date:</label>
    <input type="date" id="EndDate" name="EndDate" value="<?php echo $project['EndDate']; ?>" required>
    
    <label for="Description">Description:</label>
    <textarea id="Description" name="Description" required><?php echo $project['Description']; ?></textarea>
    
    <label for="Status">Status:</label>
    <select id="Status" name="Status" required>
    <option value="" disabled selected>Select status</option>
        <option value="Planning">Not Started</option>
        <option value="In Progress">In Progress</option>
        <option value="On hold">On hold</option>
        <option value="Completed">Completed</option>
        <option value="Delayed">Delayed</option>
        <option value="Cancelled">Cancelled</option>
        <option value="Pending Approval">Pending Approval</option>
        <option value="Under review">Under review</option>
        <option value="Behind Schedule">Behind Schedule</option>
        <option value="Over Budget">Over Budget</option>
        <option value="Finalizing Details">Finalizing Details</option>
        <option value="Ready for Inspection">Ready for Inspection</option>
    </select>
    
    <label for="Budget">Budget:</label>
    <input type="text" id="Budget" name="Budget" value="<?php echo $project['Budget']; ?>" required>
    
    <label for="Location">Location:</label>
    <input type="text" id="Location" name="Location" value="<?php echo $project['Location']; ?>" required>
    
    <label for="WorkersDetails">Workers:</label>
    <input type="text" id="WorkersDetails" name="WorkersDetails" value="<?php echo $project['WorkersDetails']; ?>" required>
    
    <label for="ClientDetails">Client:</label>
    <input type="text" id="ClientDetails" name="ClientDetails" value="<?php echo $project['ClientDetails']; ?>" required>
    
    <label for="SupplierDetails">Supplier:</label>
    <input type="text" id="SupplierDetails" name="SupplierDetails" value="<?php echo $project['SupplierDetails']; ?>" required>
    
    <label for="ProjectRequest">Project Request:</label>
    <input type="text" id="ProjectRequest" name="ProjectRequest" value="<?php echo $project['ProjectRequest']; ?>" required>
    
    <label for="SummaryText">Summary Text:</label>
    <textarea id="SummaryText" name="SummaryText" required><?php echo $project['SummaryText']; ?></textarea>

    <input type="hidden" name="projectID" value="<?php echo $projectID; ?>">
    
    <input type="submit" name="save_edit" value="Save Changes">
    <input type="button" value="Cancel" onclick="window.location.href = 'plan.php';">
</form>
</body>
</html>