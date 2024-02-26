<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registration Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <form action="proses_register.php" method="post">
                    <h2 class="text-center mb-4">User Registration</h2>

                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" name="username" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>

                    <div class="mb-3">
                        <label for="namalengkap" class="form-label">Full Name</label>
                        <input type="text" class="form-control" name="namalengkap" id="namalengkap" required>
                        <div id="namaWarning" class="form-text text-danger d-none">Nama Lengkap harus 3 character atau lebih.</div>
                    </div>

                    <div class="mb-3">
                        <label for="alamat" class="form-label">Address</label>
                        <input type="text" class="form-control" name="alamat" required>
                    </div>

                    <div class="text-center">
                       <input type="submit" value="Register" class="btn btn-primary">
                    </div>
                </form>
                <div class="text-center mt-3">
                    Already have an account? <a href="login.php">Login here</a>.
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    <script>
        document.getElementById("namalengkap").addEventListener("input", function() {
            var namaInput = this.value;
            var namaWarning = document.getElementById("namaWarning");
            
            if (namaInput.length < 3) {
                namaWarning.classList.remove("d-none");
            } else {
                namaWarning.classList.add("d-none");
            }
        });
    </script>
</body>

</html>
