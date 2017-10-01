<!DOCTYPE html>
<html lang="en">
    <?php include "header.php" ?> 
    <head>
        <link rel="stylesheet" type="text/css" href="css/dropzone.css" />
        <script type="text/javascript" src="js/dropzone.js"></script>
        <script type="text/javascript" src="js/custom.js"></script>

        <link rel="stylesheet" type="text/css" href="css/jquery.dataTables.ui.css" /> 
        <link rel="stylesheet" type="text/css" href="css/jquery.dataTables.min.css" /> 
        <script type="text/javascript" src="js/jquery.dataTables.min.js"></script> 
        <script type="text/javascript" src="js/jqueryui.dataTables.min.js"></script> 
        <script type="text/javascript" src="js/custom.js"></script>
    </head>
    <body>
        <?php include "navbar.php" ?>

        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <?php echo "<h1>" . $_SESSION['SESS_USERNAME'] . "'s File Manager</h1>" ?>

                    <!-- Tutorial: http://www.phpzag.com/drag-and-drop-file-upload-using-jquery-and-php/ 
                        Validate FileUpload: https://codepen.io/probil/pen/yyzdOM -->
                    <div class="file_upload">
                        <form action="file_upload.php" class="dropzone" id="my-awesome-dropzone">
                            <div class="dz-message needsclick">
                                <strong>Drop files here or click to upload.</strong><br />
                                <span class="note needsclick">After the file is dropped, it will automatically uploaded to the server!</span>
                            </div>
                        </form>
                    </div>

                    <br> 

                    <!-- loading of datatable -->
                    <table id="datatable-filemanager" class="display" cellspacing="0" width="100%">
                        <thead>
                            <tr> 
                                <th>Name</th>
                                <th>File Type</th>
                                <th>File Size</th> 
                                <th>Upload Date</th> 
                                <th>Expiry Date</th>
                                <th>Permission</th>
                                <th>Status</th>
                                <th>Downloads</th> 
                                <th>edit</th>
                                <th>delete</th>
                            </tr>
                        </thead> 
                        <tbody>

                            <?php
                            $conn = new Mysql_Driver();
                            $conn->connect();
                            /* @var $_SESSION type */
                            $accountID = $_SESSION['SESS_ACC_ID'];
                            $qry = "SELECT * FROM file WHERE accountID = $accountID";
                            $result = $conn->query($qry);

                            if ($conn->num_rows($result) > 0) { //(result)
                                //Loop tdrough tde result and print tde data to tde table
                                while ($row = $conn->fetch_array($result)) {
                                    echo '<tr>';
                                    echo '<td><a href="file.php?fID=' . $row["fileID"] . '">' . $row["fileName"] . '</a></td>
                                            <td>' . $row["fileType"] . '</td>
                                            <td>' . round($row["fileSize"] / 1000.0 / 1000.0, 2) .' MB</td> 
                                            <td>' . $row["uploadDate"] . '</td> 
                                            <td>' . $row["expiryDate"] . '</td>
                                            <td>' . $row["filePermission"] . '</td>
                                            <td>' . $row["fileStatus"] . '</td>
                                            <td>' . $row["downloadTimes"] . '</td> 
                                            <td>edit</td>
                                            <td>delete</td>'; 
                                    echo '</a>';
                                }
                            }
                            $conn->close();
                            ?>

                        </tbody>
                    </table>




                </div>
            </div>
        </div>
    </div>  
</body>
</html>