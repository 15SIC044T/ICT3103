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

                    <br><br>

                    <a href="" id="myDocumentBtn" style="margin: 10px 50px 10px 10px; padding: 5px; font-size: 18px;">My Document</a>
                    <a href="" id="sharedWithMeBtn" style="margin: 10px 50px 10px 10px; padding: 5px; font-size: 18px;">Shared with me</a> <br><br>

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
                                <th>Status</th>
                                <th>Downloads</th>
                                <th></th>
                            </tr>
                        </thead> 
                        <tbody>

                            <?php
                            $conn = new Mysql_Driver();
                            $conn->connect();
                            /* @var $_SESSION type */
                            $accountID = $_SESSION['SESS_ACC_ID'];
                            $qry = "SELECT * FROM file WHERE accountID = $accountID"
                                    . " UNION "
                                    . "SELECT * FROM file WHERE fileID IN (SELECT fileID FROM fileSharing WHERE accountID = $accountID)";
                            $result = $conn->query($qry);

                            if ($conn->num_rows($result) > 0) { //(result)
                                //Loop tdrough tde result and print tde data to tde table
                                while ($row = $conn->fetch_array($result)) {
                                    echo '<tr>';
                                    echo '<td>' . $row["accountID"] . '</td>
                                            <td><a href="#" data-target="#edit" data-toggle="modal"><span class="glyphicon glyphicon-pencil"></span></a></td>
                                            <td><a href="file.php?fID=' . $row["fileID"] . '">' . $row["fileName"] . '</a></td>
                                            <td>' . $row["fileType"] . '</td>
                                            <td>' . round($row["fileSize"] / 1000.0 / 1000.0, 2) . ' MB</td> 
                                            <td>' . $row["uploadDate"] . '</td> 
                                            <td>' . $row["expiryDate"] . '</td>
                                            <td>' . $row["filePermission"] . '</td>
                                            <td>' . $row["fileStatus"] . '</td>
                                            <td>' . $row["downloadTimes"] . '</td>
                                            <td><a href="#" data-target="#del" data-toggle="modal"><span class="glyphicon glyphicon-trash"></span></a></td>';
                                    echo '</a>';
                                }
                            }
                            $conn->close();
                            ?>

                        </tbody>
                    </table>


                    <!-- Modal EDIT -->
                    <div id="edit" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content resetmodal">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="glyphicon glyphicon-remove"></i></button>
                                </div>
                                <div class="modal-body">
                                    <div class="editBlock" style="text-align: center;">
                                        <h1>Edit File Details</h1>
                                        <p>Set permission and stuff</p>
                                    </div>

                                    <!--edit modal content -->
                                    <div class="editBlock">
                                        <form data-toggle="validator" method="post" action="sendResetPasswordEmail.php" class="form-horizontal" role="form" >
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <input name="fName" type="text" class="form-control" placeholder="File Name" required>
                                                    <input name="fPermission" type="text" class="form-control" placeholder="File Permission" required>
                                                    <input name="fStatus" type="text" class="form-control" placeholder="File Status" required>

                                                    <button class="btn btn-lg btn-block" name="login" type="submit">Save</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <!--edit modal content end-->
                                </div>
                            </div>
                        </div>
                    </div>



                    <!-- Modal DELETE -->
                    <div id="del" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content resetmodal">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="glyphicon glyphicon-remove"></i></button>
                                </div>
                                <div class="modal-body">
                                    <div class="deleteBlock" style="text-align: center;">
                                        <h1>Delete FILENAME</h1>
                                        <p>Are you sure you want to delete FILENAME?.</p>
                                    </div>

                                    <!--delete modal content -->
                                    <div class="deleteBlock">
                                        <form data-toggle="validator" method="post" action="sendResetPasswordEmail.php" class="form-horizontal" role="form" >
                                            <div class="form-group">
                                                <div class="col-sm-12" style="text-align: center;"> 
                                                    <button class="btn btn-lg" style="width: 45%" name="deleteYes" type="submit">Yes</button>
                                                    <button class="btn btn-lg" style="width: 45%" name="deleteNo" type="submit">No</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <!--delete modal content end-->
                                </div>
                            </div>
                        </div>
                    </div>




                    <!-- End of Coding -->
                    <br><br><br> 
                </div>
            </div>
        </div>
    </div>  
</body>
</html>