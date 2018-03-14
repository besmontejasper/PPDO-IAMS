<?php 
	$db = mysqli_connect('localhost', 'root', '', 'ppdo_iams');

	// initialize variables
	$item_name = "";
	$item_quantity = "";
	$item_tag = "";
	$item_cost = "";
	$date_modified = "";
	$critical_stock = "";
	$unit = "";
	$file = "";
	$id = 0;
	$update = false;

	if (isset($_POST['save'])) {
		if (!is_null($_POST['item_name']) && ($_POST['item_quantity']!=0) && ($_POST['item_tag']!="None") && ($_POST['item_cost']!=0)){
			$item_name = $_POST['item_name'];
			$item_quantity = $_POST['item_quantity'];
			$unit = $_POST['unit'];
			$item_tag = $_POST['item_tag'];
			$item_cost = sprintf("%.2f", $_POST['item_cost']);
			$critical_stock = $_POST['critical_stock'];
			$dt = new DateTime();
			$d = date_add($dt,date_interval_create_from_date_string("+0 days"));
			$date_modified = $d->format('Y-m-d');
			$results = mysqli_query($db, "SELECT * FROM item_inventory");
			while ($row = mysqli_fetch_array($results)){
				if ((strtolower(trim($item_name," ")) == strtolower(trim($row['item_name'], " "))) && $item_tag == $row['item_tag'] && $item_cost == $row['item_cost']){ 
					$item_quantity = $item_quantity + $row['item_quantity'];
					$id = $row['id'];
					mysqli_query($db, "DELETE FROM item_inventory WHERE id=$id");
				}
			}
			mysqli_query($db, "INSERT INTO item_inventory (id, item_name, item_quantity, unit, item_tag, item_cost, date_modified, critical_stock) VALUES ('$id', '$item_name', '$item_quantity', '$unit', '$item_tag', '$item_cost', '$date_modified', '$critical_stock')"); 	
			
			header('location: inventory.php');
		}
		else {
			header('location: inventory.php?error');
		}
	}
	
	if (isset($_POST['update'])) {
		if (!is_null($_POST['item_name']) && ($_POST['item_quantity']!=0) && ($_POST['item_tag']!="None") && ($_POST['item_cost']!=0)){
			$id = $_POST['id'];
			$item_name = $_POST['item_name'];
			$item_quantity = $_POST['item_quantity'];
			$unit = $_POST['unit'];
			$item_tag = $_POST['item_tag'];
			$item_cost = $_POST['item_cost'];
			$critical_stock = $_POST['critical_stock'];
			$dt = new DateTime();
			$d = date_add($dt,date_interval_create_from_date_string("+0 days"));
			$date_modified = $d->format('Y-m-d');
			mysqli_query($db, "UPDATE item_inventory SET item_name='$item_name', item_quantity='$item_quantity', critical_stock='$critical_stock', unit = '$unit', item_tag='$item_tag', item_cost='$item_cost', date_modified='$date_modified'  WHERE id=$id");

			header('location: inventory.php');
		}
		else {
			header('location: inventory.php?error');
		}
	}

	if (isset($_GET['del'])) {
		$id = $_GET['del'];
		mysqli_query($db, "DELETE FROM item_inventory WHERE id=$id");
		$_SESSION['message'] = "Item deleted!"; 
		header('location: inventory.php');
	}	

	if (isset($_POST['file_submit'])) {
		if ($_FILES['csv_file']['error'] === UPLOAD_ERR_OK) { 
			$file = realpath($_FILES["csv_file"]["tmp_name"]);
			$file = str_replace ("\\", "/", $file);

			$query = " LOAD DATA LOCAL INFILE '".$file."' " .
	        " INTO TABLE item_inventory" .
	        " CHARACTER SET UTF8" .
	        " FIELDS TERMINATED BY ',' " .
	        " OPTIONALLY ENCLOSED BY '\"' " .
	        " LINES TERMINATED BY '\\r\\n' " .
	        " IGNORE 1 LINES " .
	        " (item_name,item_quantity,critical_stock,item_tag,item_cost,unit,@var1)" .
	        " SET id = NULL, date_modified = STR_TO_DATE(@var1,'%m/%d/%Y')";

			echo $query;
			$result = mysqli_query($db, $query);

		} 
		else { 
			throw new UploadException($_FILES['csv_file']['error']); 
		} 
		
		header('location: inventory.php');
	}

	class UploadException extends Exception 
	{ 
    public function __construct($code) { 
        $message = $this->codeToMessage($code); 
        parent::__construct($message, $code); 
    } 

    private function codeToMessage($code) 
    { 
        switch ($code) { 
            case UPLOAD_ERR_INI_SIZE: 
                $message = "The uploaded file exceeds the upload_max_filesize directive in php.ini"; 
                break; 
            case UPLOAD_ERR_FORM_SIZE: 
                $message = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form"; 
                break; 
            case UPLOAD_ERR_PARTIAL: 
                $message = "The uploaded file was only partially uploaded"; 
                break; 
            case UPLOAD_ERR_NO_FILE: 
                $message = "No file was uploaded"; 
                break; 
            case UPLOAD_ERR_NO_TMP_DIR: 
                $message = "Missing a temporary folder"; 
                break; 
            case UPLOAD_ERR_CANT_WRITE: 
                $message = "Failed to write file to disk"; 
                break; 
            case UPLOAD_ERR_EXTENSION: 
                $message = "File upload stopped by extension"; 
                break; 

            default: 
                $message = "Unknown upload error"; 
                break; 
        } 
        return $message; 
    } 
} 
?>