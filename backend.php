<?php


        $conn = mysqli_connect("sql1.njit.edu", "db526", "**PASSWORD**", "db526");

		//check connection
        if (!$conn) {
            echo "Error: Unable to connect to MySQL." . PHP_EOL;
            echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
            echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
            exit;
        }

		// read in recieved json
        $json = file_get_contents('php://input');
        $json = rtrim($json, "\0" );
        $data = json_decode($json);

		// assign json values
        $username = $data->username;
        $pswrd = $data->password;
		//hash password
        $password = hash('ripemd160', $pswrd);

		// get the password from the database of the username entered 
        $sql = "SELECT password FROM users WHERE username = '$username'";
        $result = $conn->query($sql);

        $dbpass = NULL;

        if ($result->num_rows > 0 )
        {
                while($row = $result->fetch_assoc()) {
                        $dbpass = $row["password"];
                }
        }

        $auth = 0;
		// compare password and set authentication accordingly
        if ($password == $dbpass)
        {
                $auth = 1;

        }
        else
        {
                $auth = 0;
        }

		// make a json and echo it out 
        $arr = array('njit' => 0, 'local' => $auth);
        echo json_encode($arr);

        mysqli_close($conn);



?>
