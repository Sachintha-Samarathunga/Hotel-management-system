<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="style01.css">

	<title>Hotel Management System</title>
</head>

<body>

	<?php

	$guest_name = $address = $email = $phone_num = $room_type = $arrival_date = $departure_time = " ";
	$guest_nameErr = $addressErr = $emailErr = $phone_numErr = $room_typeErr = $arrival_dateErr = $departure_timeErr = " ";
	$searchOutput = $dataInput = $clearTable = $deleteOutput = " ";

	function input_data($data)
	{

		return htmlspecialchars(stripcslashes(trim($data)));
	}

	$is_invalid = true;

	if ($_SERVER['REQUEST_METHOD'] == "POST") {

		if (empty($_POST['guest_name'])) {

			$guest_nameErr = "* Name is Required !!!";
			$is_invalid = false;
		} else {

			$guest_name = input_data($_POST['guest_name']);

			if (!preg_match("/^[a-zA-Z-' ]*$/", $guest_name)) {

				$guest_nameErr = "Invalid Name Format!!!!";
			}
		}

		if (empty($_POST['address'])) {

			$addressErr = "* Address is Required !!!";
			$is_invalid = false;
		} else {

			$address = input_data($_POST['address']);
		}

		if (empty($_POST['email'])) {

			$emailErr = "* Email is Required !!!";
			$is_invalid = false;
		} else {

			$email = input_data($_POST['email']);

			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

				$emailErr = "Invalid Email Format !!!";
			}
		}

		if (empty($_POST['phone_num'])) {

			$phone_numErr = "* Phone Number is Required !!!";
			$is_invalid = false;
		} else {

			$phone_num = input_data($_POST['phone_num']);

			if (!is_numeric($phone_num)) {

				$phone_numErr = "Invalid Phone Number !!!";
				$is_invalid = false;
			} elseif (strlen($phone_num) != 10) {

				$phone_numErr = "Invalid Phone Number !!!";
				$is_invalid = false;
			}
		}

		if (empty($_POST['room_type'])) {

			$room_typeErr = "* Room type is Required !!!";
		} else {

			$room_type = input_data($_POST['room_type']);
		}

		if (empty($_POST['arrival_date'])) {

			$arrival_dateErr = "* Arrival date is Required !!!";
			$is_invalid = false;
		} else {

			$arrival_date = input_data($_POST['arrival_date']);
		}

		if (empty($_POST['departure_time'])) {

			$departure_timeErr = "* Deaparture time is Required !!!";
			$is_invalid = false;
		} else {

			$departure_time = input_data($_POST['departure_time']);
		}
	}

	$conn = new mysqli("localhost", "root", "", "testDB");

	if ($conn->connect_error) {

		die("Connection Failed " . $conn->connect_error);
	}

	if (isset($_POST['add']) && $is_invalid) {

		$sql = "INSERT INTO guest VALUES(\"$guest_name\",\"$address\",\"$email\",\"$phone_num\",\"$room_type\",\"$arrival_date\",\"$departure_time\")";

		if ($conn->query($sql) === TRUE) {

			$dataInput = "Data Insert Successfully!";
		} else {

			$dataInput = "Can't Insert !!!";
		}

		$conn->close();
	} elseif (isset($_POST['clear'])) {

		$sql = "TRUNCATE guest";

		if ($conn->query($sql) === TRUE) {

			$clearTable = "Clear Table Successfully!";
		} else {

			$clearTable = "Can't Clear The Table!";
		}

		$conn->close();
	} elseif (isset($_POST['update'])) {

		$sql = "UPDATE guest SET quest_name=\"$guest_name\" WHERE email=\"$email\" AND room_type=\"$room_type\"";

		if ($conn->query($sql) === TRUE) {

			echo '<script>alert("Update Successfully !!!")</script>';
		} else {

			echo "error " . $conn->error;
		}

		$conn->close();
	} elseif (isset($_POST['delete'])) {

		$sql = "DELETE FROM guest WHERE email=\"$email\" AND room_type=\"$room_type\"";

		if ($conn->query($sql) === TRUE) {

			$deleteOutput = "Delete Successfully";
		} else {

			$deleteOutput = "Can't Delete That";
		}

		$conn->close();
	} elseif (isset($_POST['search'])) {

		$sql = "SELECT quest_name,address FROM guest WHERE email=\"$email\" AND room_type=\"$room_type\"";

		$result = $conn->query($sql);

		if ($result->num_rows > 0) {

			while ($row = $result->fetch_assoc()) {

				$searchOutput = "Guest Name: " . $row["quest_name"] . "<br> Address: " . $row["address"];
			}
		} else {

			$searchOutput =  "No Result " . $conn->error;
		}

		$conn->close();
	}

	?>


	<div class="container">
		<div class="hotel-name">
			<img src="hotel.svg" alt="hotel">
			<span class="brand-name">HASTINGS</span>
			<span class="secondary-name"><i>-Europa Hotel-</i></span>
			<p>Hotel & Rooms</p>
		</div>

		<div class="form-container">
			<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
				<table>
					<tr>
						<td colspan="2">
							<span class="Header">
								<h2>Hotel Registration Form<h2>
							</span>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<span class="error">* Required Fields</span>
						</td>
					</tr>
					<tr>
						<td style="width: 30%;">
							Guest Name:
						</td>
						<td style="width: 70%;">
							<div class="right">
								<input type="text" name="guest_name" class="text-input" placeholder="Name here..."><br>
								<span class="error"><?php echo $guest_nameErr; ?></span>
							</div>
						</td>
					</tr>
					<tr>
						<td>
							Address:
						</td>
						<td>
							<div class="right">
								<input type="text" name="address" class="text-input" placeholder="Adress here ..."><br>
								<span class="error"><?php echo $addressErr; ?></span>
							</div>
						</td>
					</tr>
					<tr>
						<td>
							E-mail:
						</td>
						<td>
							<div class="right">
								<input type="text" name="email" class="text-input" placeholder="E-mail here ..."><br>
								<span class="error"><?php echo $emailErr; ?></span>
							</div>
						</td>
					</tr>
					<tr>
						<td class="left">
							Phone No:
						</td>
						<td>
							<div class="right">
								<input type="text" name="phone_num" class="text-input" placeholder="Phone number here..."><br>
								<span class="error"><?php echo $phone_numErr; ?></span>
							</div>
						</td>
					</tr>
					<tr>
						<td>
							Room Type:
						</td>
						<td>
							<div class="right">
								<select name="room_type" class="text-input">
									<option>--Please Select a room type--</option>
									<option value="single_room">Single Room</option>
									<option value="double_room">Double Room</option>
									<option value="triple">Triple</option>
									<option value="quard">Quard</option>
									<option value="queen">Queen</option>
									<option value="king">Single King</option>
									<option value="twin">Twin</option>
								</select><br>
								<span class="error"><?php echo $room_typeErr; ?></span>
							</div>
						</td>
					</tr>
					<tr>
						<td>
							Arrival Date:
						</td>
						<td>
							<div class="right">
								<input type="date" class="text-input" name="arrival_date"><br>
								<span class="error"><?php echo $arrival_dateErr; ?></span>
							</div>
						</td>
					</tr>
					<tr>
						<td>
							Departure Time:
						</td>
						<td>
							<div class="right">
								<input type="date" class="text-input" name="departure_time"><br>
								<span class="error"><?php echo $departure_timeErr; ?></span>
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<input type="submit" name="add" class="primary-btn add" Value="Add">
						</td>
						<td>
							<div class="btn">
								<input type="submit" name="clear" class="primary-btn clear" Value="Clear">
								<input type="submit" name="search" class="primary-btn search" Value="Search">
								<input type="submit" name="update" class="primary-btn update" Value="Update">
								<input type="submit" name="delete" class="primary-btn delete" Value="Delete">
							</div>
						</td>
					</tr>
				</table>
			</form>
		</div>

		<div class="search-container">
			<?php echo $searchOutput; ?>
			<?php echo $dataInput; ?>
			<?php echo $clearTable; ?>
			<?php echo $deleteOutput; ?>
		</div>
	</div>
</body>

</html>