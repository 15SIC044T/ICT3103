<!DOCTYPE html>
<html lang="en">
    <?php include "header.php" ?> 
    <head>
        <!-- Upload File -->
        <link rel="stylesheet" type="text/css" href="css/dropzone.css" />
        <script type="text/javascript" src="js/dropzone.js"></script>
        <script type="text/javascript" src="js/custom.js"></script>

        <!-- DataTable -->
        <link rel="stylesheet" type="text/css" href="css/jquery.dataTables.ui.css" /> 
        <link rel="stylesheet" type="text/css" href="css/jquery.dataTables.min.css" /> 
        <script type="text/javascript" src="js/jquery.dataTables.min.js"></script> 
        <script type="text/javascript" src="js/jqueryui.dataTables.min.js"></script>

        <!-- DatePicker -->
        <link rel="stylesheet" type="text/css" href="css/datetimepicker.css" /> 
        <script type="text/javascript" src="js/moment-with-locales.js"></script> 
        <script type="text/javascript" src="js/datetimepicker.js"></script> 

        <script type="text/javascript" src="js/custom.js"></script>
    </head>
    <body>
        <?php include "navbar.php"; 
        ?>

        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    
                    <?php include "fileCheckExpiry.php" ?>
                    
                    <?php echo "<h1>" . $_SESSION['SESS_USERNAME'] . "'s File Manager</h1>" ?>

                    <!-- Tutorial: http://www.phpzag.com/drag-and-drop-file-upload-using-jquery-and-php/ 
                        Validate FileUpload: https://codepen.io/probil/pen/yyzdOM -->
                    <div class="file_upload">
                        <form action="fileUpload.php" class="dropzone" id="my-awesome-dropzone">
                            <div class="dz-message needsclick">
                                <strong>Drop files here or click to upload.</strong><br />
                                <span class="note needsclick">After the file is dropped, it will automatically uploaded to the server!</span>
                            </div>
                        </form>
                    </div>

                    <br><br>
                    <?php include "displayAlertMessage.php" ?> 

                    <a id="myDocumentBtn" style="margin: 10px 50px 10px 10px; padding: 5px; font-size: 18px;">My Document</a>
                    <a id="sharedWithMeBtn" style="margin: 10px 50px 10px 10px; padding: 5px; font-size: 18px;">Shared with me</a> <br><br>

                    <!-- loading of datatable -->
                    <table id="fileManagerDataTable" class="display" cellspacing="0" width="100%">
                        <thead>
                            <tr> 
                                <th></th> 
                                <th></th>
                                <th>Name</th>
                                <th>File Type</th>
                                <th>File Size</th> 
                                <th>Upload Date</th> 
                                <th>Expiry Date</th>
                                <th>Permission</th> 
                                <th>File Status</th>
                                <th>Downloads</th>
                                <th></th>
                            </tr>
                        </thead> 
                        <tbody>

                            <?php
                            require_once('dbConnection.php');

                            $accountID = $_SESSION['SESS_ACC_ID'];
                            
                            $stmt = $conn->prepare("SELECT (CASE WHEN (f.accountID = ?) THEN 1 ELSE 0 END) AS state, f.uploadDate, f.expiryDate, f.fileID, f.fileName, f.fileType, f.fileSize, f.filePermission, f.fileStatus, f.downloadTimes FROM file f WHERE f.accountID = ?"
                                    . " UNION "
                                    . "SELECT (CASE WHEN (f.accountID = ?) THEN 1 ELSE 0 END) AS state, f.uploadDate, f.expiryDate, f.fileID, f.fileName, f.fileType, f.fileSize, f.filePermission, f.fileStatus, f.downloadTimes FROM file f WHERE f.fileID IN (SELECT fileID FROM filesharing WHERE accountID = ?)");
                            $stmt->bind_param("iiii", $accountID, $accountID, $accountID, $accountID);
                            $stmt->execute();
                            $result = $stmt->get_result();   

                            if ($result->num_rows > 0) { //(result)
                                //Loop tdrough tde result and print tde data to tde table
                                while ($row = $result->fetch_assoc()) {

                                    $FormatedUploadDate = $row["uploadDate"] == NULL ? "" : date("j M Y H:i:s A", strtotime($row["uploadDate"]));
                                    $FormatedExpiryDate = $row["expiryDate"] == NULL ? "" : date("j M Y H:i:s A", strtotime($row["expiryDate"]));
                                    echo '<tr>';
                                    echo '<td>' . $row["state"] . '</td>';
                                    
                                    if ($row["state"] == 1) {
                                        echo '<td><a href="#" data-target="#edit' . $row["fileID"] . '" data-toggle="modal"><span class="glyphicon glyphicon-pencil"></span></a></td>';
                                    } 
                                    else {
                                        echo '<td> </td>';
                                    } 
                                    
                                    echo '<td><a href="file.php?fID=' . $row["fileID"] . '">' . $row["fileName"] . '</a></td>
                                            <td>' . $row["fileType"] . '</td>
                                            <td>' . round($row["fileSize"] / 1000.0 / 1000.0, 2) . ' MB</td> 
                                            <td>' . $FormatedUploadDate . '</td> 
                                            <td>' . $FormatedExpiryDate . '</td>
                                            <td>' . $row["filePermission"] . '</td> 
                                            <td>' . $row["fileStatus"] . '</td> 
                                            <td>' . $row["downloadTimes"] . '</td>';
                                    if ($row["state"] == 1) {
                                        echo '<td><a href="#" data-target="#del' . $row["fileID"] . '" data-toggle="modal"><span class="glyphicon glyphicon-trash"></span></a></td>';
                                    }
                                    else {
                                        echo '<td><a href="#" data-target="#deI' . $row["fileID"] . '" data-toggle="modal"><span class="glyphicon glyphicon-trash"><span class="glyphicon glyphicon-link"></span></a></td>';
                                    }
                                    echo '</tr>';
                                }
                            }
                            $stmt->close();
                            ?>

                        </tbody>
                    </table>

                    <?php include "fileActionModal.php"; ?> 
                       
                    <!-- End of Coding -->
                    <br><br><br> 
                </div>
            </div>
        </div>
    </div>  
</body>
</html>
