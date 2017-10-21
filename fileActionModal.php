 <?php
    $conn = new Mysql_Driver();
    $conn->connect();
    $accountID = $_SESSION['SESS_ACC_ID'];
    $qry = "SELECT (CASE WHEN (f.accountID = $accountID) THEN 1 ELSE 0 END) AS state, f.* FROM file f WHERE f.accountID = $accountID"
            . " UNION "
            . "SELECT (CASE WHEN (f.accountID = $accountID) THEN 1 ELSE 0 END) AS state, f.* FROM file f WHERE f.fileID IN (SELECT fileID FROM fileSharing WHERE accountID = $accountID)";
    $result = $conn->query($qry);
    if ($conn->num_rows($result) > 0) { //(result)
        //Loop tdrough tde result and print tde data to tde table
        while ($row = $conn->fetch_array($result)) {
            $expiryDate = $row["expiryDate"];
            $FormatedExpiryDate = $expiryDate == NULL ? "" : date("m-d-Y H:i:s A", strtotime($expiryDate));
             // Modal EDIT
            echo '<div id="edit' . $row["fileID"] . '" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content resetmodal">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="glyphicon glyphicon-remove"></i></button>
                            </div>
                            <div class="modal-body">
                                <div class="editBlock" style="text-align: center;">
                                    <h1>' . $row["fileName"] . '</h1>
                                    <p> </p>
                                </div>
                                <!--edit modal content -->
                                <div class="editBlock">
                                    <form data-toggle="validator" method="post" action="fileAction.php" class="form-horizontal" role="form" >
                                        <div class="form-group">
                                            <div class="col-sm-12" style="text-align: center;"> 
                                                <input type="hidden" name="actionEdit" value="' . $row["fileID"] . '" />
                                                <input type="hidden" name="prevURL" value="' . $_SERVER["REQUEST_URI"] . '" />
                                                <label for="lblExpiryDate">File Name:</label>
                                                <input name="txtFileName" type="text" class="form-control" placeholder="File Name" value="' . $row["fileName"] . '" required>
                                                <label for="lblExpiryDate">Expiry Date:</label> 
                                                    <div class="input-group date" id="datetimepicker' . $row["fileID"] . '">
                                                            <input name="txtExpiryDate" type="text" class="form-control" placeholder="Default: No Expiry Date" value="'. ($row["expiryDate"] == NULL? "" : $FormatedExpiryDate) .'" />
                                                            <span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span>
                                                    </div> 
                                                <script type="text/javascript">
                                                    $(function () {
                                                            $("#datetimepicker' . $row["fileID"] . '").datetimepicker();
                                                    });
                                                </script>  
                                                <label for="lblFilePermission">File Permission:</label>
                                                <select class="form-control" id="ddlFilePermission" name="DDLFilePermission">
                                                    <option' . (($row["filePermission"] == "Private") ? " selected" : "") . '>Private</option>
                                                    <option' . (($row["filePermission"] == "Public") ? " selected" : "") . '>Public</option> 
                                                </select>   
                                                <button class="btn btn-lg btn-block btn-success" name="save" type="submit">Save</button>
                                            </div>
                                        </div>
                                    </form>
                                    
                                    <hr>

                                        <h2>File Sharing</h2> 

                                        <table id="fileSharingTable" class="display" cellspacing="0" width="100%">
                                            <thead>
                                                <tr>   
                                                    <th>Email</th>
                                                    <th>Sharing Status</th> 
                                                    <th></th> 
                                                </tr>
                                            </thead> 
                                            <tbody>';    
                                                //Run SQL statement: to query for the shared
                                                $shareFileID = 0;
                                                $qryShared = "SELECT a.email, fs.* FROM fileSharing fs INNER JOIN account a ON fs.accountID = a.accountID WHERE fs.fileID = " . $row['fileID'];
                                                $resultShared = $conn->query($qryShared);
  
                                                if ($conn->num_rows($resultShared) > 0) { //(result)
                                                    //Loop tdrough tde result and print tde data to tde table
                                                    while ($rowShared = $conn->fetch_array($resultShared)) { 
                                                        $shareFileID = $rowShared["fileSharingID"];

                                                        echo '<tr>';
                                                        echo '<td>' . $rowShared["email"] . '</td>
                                                                    <td>' . $rowShared["invitationAccepted"] . '</td>
                                                                    <td><a href="#" data-target="#delShared' . $rowShared["fileSharingID"] . ' data-toggle="modal"><span class="glyphicon glyphicon-trash"></span></a></td>';
                                                        echo '</tr>';
                                                    }
                                                }  
                                            echo '
                                            </tbody>
                                        </table


                                    <br><br>
                                    <p>Enter the person email to share with</p>
                                    <form data-toggle="validator" method="post" action="fileAction.php" class="form-horizontal" role="form">
                                        <div class="form-group">
                                            <div class="col-sm-12" style="text-align: center;"> 
                                                <input name="txtEmail" type="text" class="form-control" placeholder="Registered Email" required>
                                                <input type="hidden" name="actionShare" value="'. $row["fileID"] .'" /> 
                                                <input type="hidden" name="prevURL" value="'. $_SERVER["REQUEST_URI"]. '" />
                                                <button class="btn btn-lg btn-block btn-success" name="add" type="submit">Add</button> 
                                            </div>
                                        </div>
                                    </form>
                                    




                                </div>
                                <!--edit modal content end-->
                            </div>
                        </div>
                    </div>
                </div>';
            // Modal DELETE
            echo '<div id="del' . $row["fileID"] . '" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content resetmodal">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="glyphicon glyphicon-remove"></i></button>
                            </div>
                            <div class="modal-body">
                                <div class="deleteBlock" style="text-align: center;">
                                    <h1>' . $row["fileName"] . '</h1>
                                    <p>Are you sure you want to delete <strong>' . $row["fileName"] . '</strong>?.</p>
                                </div>
                                <!--delete modal content -->
                                <div class="deleteBlock">
                                    <form data-toggle="validator" method="post" action="fileAction.php" class="form-horizontal" role="form" >
                                        <div class="form-group">
                                            <div class="col-sm-12" style="text-align: center;"> 
                                                <input type="hidden" name="actionDelete" value="' . $row["fileID"] . '" />
                                                    <input type="hidden" name="prevURL" value="' . $_SERVER["REQUEST_URI"] . '" />
                                                <button class="btn btn-lg" style="width: 45%" name="deleteYes" type="submit">Yes</button>
                                                <button class="btn btn-lg" style="width: 45%" data-dismiss="modal" aria-hidden="true">No</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <!--delete modal content end-->
                            </div>
                        </div>
                    </div>
                </div>'; 
        }
    }
    $conn->close();
?>