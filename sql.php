<?php
session_start();

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'store_procedure';

$con = new mysqli($host, $username, $password, $database);

// Check connection
if ($con->connect_error) {
  echo 'Failed to connect to MySQL: ' . $con->connect_error;
}

if (isset($_POST['form'])) {
    switch ($_POST['form']) {
        case 'insertStudent':
            $studFname = $_POST['studFname'];
            $studMname = $_POST['studMname'];
            $studLname = $_POST['studLname'];
            $studAddress = $_POST['studAddress'];

            $sql = 'INSERT INTO students (StudFname, StudMname, StudLname, Address)
            VALUES ("' . $studFname . '", "' . $studMname . '", "' . $studLname . '", "' . $studAddress . '")';

            $success = 'Student successfully added!';
        break;
        case 'insertInstrument':
            $instrModel = $_POST['instrModel'];
            $instrName = $_POST['instrName'];
            $instrCategory = $_POST['instrCategory'];
            $instrDateAcquired = $_POST['instrDateAcquired'];
            $instrEstVal = $_POST['instrEstVal'];

            $sql = 'INSERT INTO instruments (Model, InstrName, Category, DateAcquired, EstimatedValue)
            VALUES ("' . $instrModel . '", "' . $instrName . '", "' . $instrCategory . '", "' . $instrDateAcquired . '", "' . $instrEstVal . '")';

            $success = 'Instrument successfully added!';
        break;
        case 'borrowInstrument':
            $student = $_POST['student'];
            $instrument = $_POST['instrument'];
            $noOfDays = $_POST['noOfDays'];
            $checkInDate = strtotime('+' . $noOfDays . ' days');
            $checkOutDate = date('Y-m-d H:i:s');
            $checkInDate = date('Y-m-d H:i:s', $checkInDate);
            $sql = 'INSERT INTO student_instrument (StudentID, InstrumentID, CheckOutDate, CheckInDate)
            VALUES (' . $student . ', ' . $instrument . ', "' . $checkOutDate . '", "' . $checkInDate . '")';

            $success = 'Instrument succesfully borrowed!';
        break;
    }
} else {
    $students = [];
    $instruments = [];
    $borrowedInstruments = [];

    $sql = 'CALL StudentsWithBorrowCredits()';
    $result = $con->query($sql);

    if ($result) {
        while($row = $result->fetch_assoc()) {
            array_push($students, [
                'key' => $row['StudentID'],
                'label' => ucfirst(strtolower($row['StudFname'])) . ' ' .
                strtoupper(substr($row['StudMname'], 0, 1)) . '. ' .
                ucfirst(strtolower($row['StudLname'])),
            ]);
        }
        $result->close();
        $con->next_result();
    }

    $sql = 'CALL AvailableInstruments()';
    $result = $con->query($sql);

    if ($result) {
        while($row = $result->fetch_assoc()) {
            array_push($instruments, [
                'key' => $row['InstrumentID'],
                'label' => strtoupper($row['Model']) . ' — '  . ucfirst(strtolower($row['InstrName'])),
            ]);
        }
        $result->close();
        $con->next_result();
    }

    $sql = 'CALL BorrowedInstruments()';
    $result = $con->query($sql);

    if ($result) {
        while($row = $result->fetch_assoc()) {
            array_push($borrowedInstruments, [
                'student' => ucfirst(strtolower($row['StudFname'])) . ' ' .
                strtoupper(substr($row['StudMname'], 0, 1)) . '. ' .
                ucfirst(strtolower($row['StudLname'])),
                'instrument' => strtoupper($row['Model']) . ' — '  . ucfirst(strtolower($row['InstrName'])),
                'dateBorrowed' => date('Y-m-d', strtotime($row['CheckOutDate'])),
                'returnDate' => date('Y-m-d', strtotime($row['CheckInDate'])),
            ]);
        }
        $result->close();
        $con->next_result();
    }
}

if ($con->query($sql)) {
    if (isset($success)) {
        $_SESSION['success'] = $success;
    }
} else {
    $_SESSION['error'] = $con->error;
}

if (isset($students)) {
    $_SESSION['students'] = $students;
}

if (isset($instruments)) {
    $_SESSION['instruments'] = $instruments;
}

if (isset($borrowedInstruments)) {
    $_SESSION['borrowedInstruments'] = $borrowedInstruments;
}

mysqli_close($con);
header('Location: index.php');