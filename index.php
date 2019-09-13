<?php
session_start();

if (isset($_SESSION['success'])) {
  $success = $_SESSION['success'];
}
if (isset($_SESSION['error'])) {
  $error = $_SESSION['error'];
}
if (!isset($_SESSION['instruments']) && !isset($_SESSION['students'])) {
  header('Location: sql.php');
} else {
  $students = $_SESSION['students'];
  $instruments = $_SESSION['instruments'];
  $borrowedInstruments = $_SESSION['borrowedInstruments'];
  unset($_SESSION['students']);
  unset($_SESSION['instruments']);
  unset($_SESSION['borrowedInstruments']);

  if (isset($success)) {
    unset($_SESSION['success']);
  }
  if (isset($error)) {
    unset($_SESSION['error']);
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Store Procedure</title>

  <link rel="stylesheet" href="./assets/bootstrap/css/bootstrap.min.css">
</head>
<body>
  <div class="container">
    <div class="card mt-5">
      <div class="card-body">
        <h2 class="card-title text-center">Store Procedure</h2>
        <?php if (isset($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show">
          <?php echo $error; ?>
          <button type="button" class="close" data-dismiss="alert">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <?php endif; ?>
        <?php if (isset($success)): ?>
        <div class="alert alert-success alert-dismissible fade show">
          <?php echo $success; ?>
          <button type="button" class="close" data-dismiss="alert">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <?php endif; ?>
        <div class="row">
          <div class="col-3">
            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
              <a class="nav-link active" id="v-pills-add-student-tab" data-toggle="pill" href="#v-pills-add-student">Add Student</a>
              <a class="nav-link" id="v-pills-add-instrument-tab" data-toggle="pill" href="#v-pills-add-instrument">Add Instrument</a>
              <a class="nav-link" id="v-pills-borrow-instrument-tab" data-toggle="pill" href="#v-pills-borrow-instrument">Borrow Instrument</a>
              <a class="nav-link" id="v-pills-borrowed-instruments-tab" data-toggle="pill" href="#v-pills-borrowed-instruments">Borrowed Instruments</a>
            </div>
          </div>
          <div class="col-9">
            <div class="tab-content" id="v-pills-tabContent">
              <div class="tab-pane fade show active" id="v-pills-add-student">
                <form method="post" action="./sql.php">
                  <input type="hidden" name="form" value="insertStudent" />
                  <div class="form-group">
                    <label for="studFname">First Name</label>
                    <input type="text" class="form-control" id="studFname" name="studFname" required />
                  </div>
                  <div class="form-group">
                    <label for="studMname">Middle Name</label>
                    <input type="text" class="form-control" id="studMname" name="studMname" required />
                  </div>
                  <div class="form-group">
                    <label for="studLname">Last Name</label>
                    <input type="text" class="form-control" id="studLname" name="studLname" required />
                  </div>
                  <div class="form-group">
                    <label for="studAddress">Address</label>
                    <input type="text" class="form-control" id="studAddress" name="studAddress" required />
                  </div>
                  <button type="submit" class="btn btn-primary">Submit</button>
                </form>
              </div>
              <div class="tab-pane fade" id="v-pills-add-instrument">
                <form method="post" action="./sql.php">
                  <input type="hidden" name="form" value="insertInstrument" />
                  <div class="form-group">
                    <label for="instrModel">Model</label>
                    <input type="text" class="form-control" id="instrModel" name="instrModel" required />
                  </div>
                  <div class="form-group">
                    <label for="instrName">Name</label>
                    <input type="text" class="form-control" id="instrName" name="instrName" required />
                  </div>
                  <div class="form-group">
                    <label for="instrCategory">Category</label>
                    <input type="text" class="form-control" id="instrCategory" name="instrCategory" required />
                  </div>
                  <div class="form-group">
                    <label for="instrDateAcquired">Date Acquired</label>
                    <input type="date" class="form-control" id="instrDateAcquired" name="instrDateAcquired" required />
                  </div>
                  <div class="form-group">
                    <label for="instrEstVal">Estimated Value</label>
                    <input type="number" class="form-control" id="instrEstVal" name="instrEstVal" required />
                  </div>
                  <button type="submit" class="btn btn-primary">Submit</button>
                </form>
              </div>
              <div class="tab-pane fade" id="v-pills-borrow-instrument">
                <form method="post" action="./sql.php">
                  <input type="hidden" name="form" value="borrowInstrument" />
                  <div class="form-group">
                    <label for="student">Student</label>
                    <select class="custom-select" id="student" name="student" required>
                      <option value="">-Select-</option>
                      <?php foreach ($students as $student): ?>
                      <option value="<?php echo $student['key']; ?>"><?php echo $student['label']; ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="instrument">Instrument</label>
                    <select class="custom-select" id="instrument" name="instrument" required>
                      <option value="">-Select-</option>
                      <?php foreach ($instruments as $instrument): ?>
                      <option value="<?php echo $instrument['key']; ?>"><?php echo $instrument['label']; ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="noOfDays">No. of days to borrow</label>
                    <input type="number" class="form-control" id="noOfDays" name="noOfDays" required />
                  </div>
                  <button type="submit" class="btn btn-primary">Submit</button>
                </form>
              </div>
              <div class="tab-pane fade" id="v-pills-borrowed-instruments">
                <table class="table">
                  <thead>
                    <tr>
                      <th>Student Full Name</th>
                      <th>Instrument Borrowed</th>
                      <th>Date Borrowed</th>
                      <th>Return Date</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($borrowedInstruments as $borrowedInstrument): ?>
                    <tr>
                      <td><?php echo $borrowedInstrument['student']; ?></td>
                      <td><?php echo $borrowedInstrument['instrument']; ?></td>
                      <td><?php echo $borrowedInstrument['dateBorrowed']; ?></td>
                      <td><?php echo $borrowedInstrument['returnDate']; ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($borrowedInstruments)): ?>
                      <td colspan="4" class="text-center lead">No instruments borrowed</td>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="./assets/jquery/js/jquery.min.js"></script>
  <script src="./assets/popper/js/popper.min.js"></script>
  <script src="./assets/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>