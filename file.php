<!DOCTYPE html>
<html lang="en">
    <?php include "header.php" ?>   
    <!-- DatePicker -->
    <head>
        <link rel="stylesheet" type="text/css" href="css/datetimepicker.css" /> 
        <script type="text/javascript" src="js/moment-with-locales.js"></script> 
        <script type="text/javascript" src="js/datetimepicker.js"></script> 

        <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
        <script type="text/javascript" src="js/addTextboxDynamically.js"></script>
        
        <?php include "fileCheckPermission.php"; ?>
    </head>
    <body>
        <?php include "navbar.php" ?>
        
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    
                    <?php include "fileCheckExpiry.php"; ?> 
                    
                    <?php echo "<h1>" . $_SESSION['SESS_USERNAME'] . "'s File</h1>" ?>
                    
                    <?php
                            $conn = new Mysql_Driver();
                            $conn->connect();

                            $accountID = $_SESSION['SESS_ACC_ID'];
                            $fileID = $_GET["fID"];
                            $qry = "SELECT a.name, f.* FROM file f INNER JOIN account a ON a.accountID = f.accountID WHERE f.fileID = $fileID";
                            $result = $conn->query($qry);

                            if ($conn->num_rows($result) > 0) { //(result)
                                //Loop tdrough tde result and print tde data to tde table
                                while ($row = $conn->fetch_array($result)) {
                                    
                                    $uploadPerson = $row["name"]; 
                                    $uploaderID = $row["accountID"];
                                    $fileName = $row["fileName"];
                                    $fileURL = $row["fileURL"];
                                    $fileType = $row["fileType"];
                                    $fileSize = round($row["fileSize"] / 1000.0 / 1000.0, 2) .  "MB"; 
                                    $FormatedUploadDate = $row["uploadDate"] == NULL ? "" : date("j M Y H:i:s A", strtotime($row["uploadDate"]));
                                    $FormatedExpiryDate = $row["expiryDate"] == NULL ? "" : date("j M Y H:i:s A", strtotime($row["expiryDate"]));
                                    $filePermission = $row["filePermission"];
                                    $fileStatus = $row["fileStatus"];
                                    $downloadTimes = $row["downloadTimes"]; 
                                } 
                            }
                            $conn->close();
                    ?>
                    <br>
                    <div class="col-sm-7">
                        <h2><?php echo $fileName; ?></h2> 
                        <input type="text" id="link" class="form-control" onfocus="this.select();" onmouseover="this.select();" onmouseup="return false;" value="<?php echo $fileURL; ?>" readonly="readyonly">
                              

                        <?php
                        //Check file type for imaage
                        $imgExtensions = ".png,.jpg,.gif,.bmp,.jpeg,.ico";
                        if (strpos($imgExtensions, "." . $fileType) == false) {
                            echo '<img src="' . $fileURL . '" style="width: 100%;"/>';
                        } else {
                            echo '<img src="file/no-preview.png" style="width: 100%;"/>';
                        }
                        
                        
                        ?>  
                    </div>
                    
                    <div class="col-sm-5">
                        <h2>File Information</h2>

                        <?php include "displayAlertMessage.php" ?> 
                        
                        <table class="table table-striped">
                            <tbody>
                                <tr>
                                    <th scope="row">Uploaded by</th>
                                    <td><?php echo $uploadPerson; ?></td>
                                </tr>
                                <tr>
                                    <th scope="row">File Name</th>
                                    <td><?php echo $fileName; ?></td>
                                </tr>
                                <tr>
                                    <th scope="row">File Type</th>
                                    <td><?php echo $fileType; ?></td>
                                </tr>
                                <tr>
                                    <th scope="row">File Size</th>
                                    <td><?php echo $fileSize; ?></td>
                                </tr>
                                <tr>
                                    <th scope="row">Upload Date</th>
                                    <td><?php echo $FormatedUploadDate; ?></td>
                                </tr>
                                <tr>
                                    <th scope="row">Expiry Date</th>
                                    <td><?php echo $FormatedExpiryDate; ?></td>
                                </tr>
                                <tr>
                                    <th scope="row">File Permission</th>
                                    <td><?php echo $filePermission; ?></td>
                                </tr>
                                <tr>
                                    <th scope="row">File Status</th>
                                    <td><?php echo $fileStatus; ?></td>
                                </tr>
                                <tr>
                                    <th scope="row">Downloads</th>
                                    <td><?php echo $downloadTimes; ?></td>
                                </tr>
                            </tbody>
                        </table>

                        <?php
                        //display different views
                        if ($_SESSION["SESS_ACC_ID"] == $uploaderID) {

                            //display edit button
                            echo '<span data-target="#edit' . $fileID . '" data-toggle="modal"><button class="btn btn-lg btn-block" name="edit">Edit</button></span><br>';
                            //upload person able to upload the details 
                            include "fileActionModal.php";

                        }
                        ?> 
                        <br><br><br><br><br>
                        
                    </div>

                    <!-- end of code -->
                </div>
            </div>
        </div>
    </div>  
</body>
</html>