<?php

  require "database.php";

  $error = null;
  $regex = '/[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})/';

  if($_SERVER["REQUEST_METHOD"] === "POST") {

    if(empty($_POST["name"]) || empty($_POST["email"]) || empty($_POST["password"])) {

      $error = "Please fill all the fields";

    }else if (!preg_match($regex, $_POST["email"])) {

       echo $_POST["email"] . " is an invalid email. Please try again.";

    }else {

      $statement = $conn->prepare("SELECT * FROM users WHERE email = :email");
      $statement->bindParam(":email", $_POST["email"]);
      $statement->execute();

      if($statement->rowCount() > 0) {

        $error = "This email already exists";

      }else {

        $conn
        ->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)")
        ->execute([
          ":name"=>$_POST["name"],
          ":email"=>$_POST["email"],
          ":password"=>password_hash($_POST["password"], PASSWORD_BCRYPT),
        ]);

        $statement = $conn->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $statement->bindParam(":email", $_POST["email"]);
        $statement->execute();
        $user = $statement->fetch(PDO::FETCH_ASSOC);

        session_start();
        $_SESSION["user"] = $user;

        header("Location: home.php");

      }
    }
  }

?>

<?php require "partials/header.php" ?>
  <div class="container pt-5">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">Register</div>
          <div class="card-body">
            <?php if($error): ?>
              <p class="text-danger">
                <?php echo $error ?>
              </p>
            <?php endif ?>
            <form method="POST" action="register.php">
              <div class="mb-3 row">
                <label for="name" class="col-md-4 col-form-label text-md-end">Name</label>

                <div class="col-md-6">
                  <input id="name" type="text" class="form-control" name="name" required autocomplete="name" autofocus>
                </div>
              </div>

              <div class="mb-3 row">
                <label for="email" class="col-md-4 col-form-label text-md-end">Email</label>

                <div class="col-md-6">
                  <input id="email" type="emai" class="form-control" name="email" required autocomplete="email" autofocus>
                </div>
              </div>

              <div class="mb-3 row">
                <label for="password" class="col-md-4 col-form-label text-md-end">Password</label>

                <div class="col-md-6">
                  <input id="password" type="password" class="form-control" name="password" required autocomplete="password" autofocus>
                </div>
              </div>

              <div class="mb-3 row">
                <div class="col-md-6 offset-md-4">
                  <button type="submit" class="btn btn-primary">Submit</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php require "partials/footer.php"?>