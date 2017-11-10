<?php

require_once('dbConnection.php');
$accountID = $_SESSION['SESS_ACC_ID']; 

$stmt = $conn->prepare("SELECT (CASE WHEN (f.accountID = ?) THEN 1 ELSE 0 END) AS state, f.fileID, f.fileName, f.expiryDate, f.filePermission FROM file f WHERE f.accountID = ?"
        . " UNION SELECT (CASE WHEN (f.accountID = ?) THEN 1 ELSE 0 END) AS state, f.fileID, f.fileName, f.expiryDate, f.filePermission FROM file f WHERE f.fileID IN "
        . "(SELECT fileID FROM filesharing WHERE accountID = ?)");
$stmt->bind_param("iiii", $accountID, $accountID, $accountID, $accountID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) { //(result)
        //Loop tdrough tde result and print tde data to tde table
        while ($row = $result->fetch_assoc()) {
            $expiryDate = $row["expiryDate"];
            $FormatedExpiryDate = $expiryDate == NULL ? "" : date("m-d-Y H:i:s A", strtotime($expiryDate));
             // Modal EDIT
              
            foreach ($_SESSION['fileArray'] as $product) {
                if ($product['fileID'] == $row["fileID"]) {
                    $fileHashing = $product['hashID'];
                    $countID = $product['countID'];
                    break;
                }
            }
            
            if ($row["state"] == 1) {
            echo '<div id="edit' . $countID . '" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
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
                                                <input type="hidden" name="actionEdit" value="' . $fileHashing . '" />
                                                <input type="hidden" name="prevURL" value="' . $_SERVER["REQUEST_URI"] . '" />
                                                <label for="lblExpiryDate">File Name:</label>
                                                <input name="txtFileName" type="text" class="form-control" placeholder="File Name" value="' . $row["fileName"] . '" required>
                                                <label for="lblExpiryDate">Expiry Date:</label> 
                                                    <div class="input-group date" id="datetimepicker' . $countID . '">
                                                            <input name="txtExpiryDate" type="text" class="form-control" placeholder="Default: No Expiry Date" value="'. $FormatedExpiryDate .'" />
                                                            <span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span>
                                                    </div> 
                                                <script type="text/javascript">
                                                    $(function () { 
                                                            $("#datetimepicker' . $countID . '").datetimepicker({ minDate: new Date(), useCurrent: false}); 
                                                    });
                                                    
                                                    $(window).load(function() {
                                                        $("#datetimepicker' . $countID . '").val("");
                                                    });

                                                </script>  
                                                <label for="lblFilePermission">File Permission:</label>
                                                <select class="form-control" id="ddlFilePermission" name="DDLFilePermission">
                                                    <option' . (($row["filePermission"] == "Private") ? " selected" : "") . '>Private</option>
                                                    <option' . (($row["filePermission"] == "Public") ? " selected" : "") . '>Public</option> 
                                                </select>   
                                                
                                                <hr>

                                    <div id="fSharing">
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
                                                $stmt = $conn->prepare("SELECT a.email, fs.* FROM fileSharing fs INNER JOIN account a ON fs.accountID = a.accountID WHERE fs.fileID = ? AND fs.owner = 0 ORDER BY fs.fileSharingID ASC");
                                                $stmt->bind_param("i", $row['fileID']);
                                                $stmt->execute();
                                                $resultShared = $stmt->get_result(); 
  
                                                if ($resultShared->num_rows > 0) { //(result) 
                                                    $_SESSION['fileSharedArray'] = array();
                                                    //Loop tdrough tde result and print tde data to tde table
                                                    while ($rowShared = $resultShared->fetch_assoc()) { 
                                                        $now = DateTime::createFromFormat('U.u', microtime(true)); 
                                                        
                                                        $hashSharedFileID = password_hash($rowShared["fileSharingID"] . $now->format("m-d-Y H:i:s.u") . $_SESSION['SESS_ACC_ID'], PASSWORD_BCRYPT);
                                                        $countID = preg_replace("/[^A-Za-z0-9 ]/", '', $hashSharedFileID);
                                                        array_push($_SESSION['fileSharedArray'], ['hashID' => $hashSharedFileID, 'countID' => $countID, 'fileSharedID' => $rowShared["fileSharingID"]]);
                                                        $shareFileID = $rowShared["fileSharingID"];

                                                        echo '<tr>';
                                                        echo '<td>' . $rowShared["email"] . '</td>
                                                                    <td>' . $rowShared["invitationAccepted"] . '</td>
                                                                    <td><a style="cursor: pointer;" onClick="myFunction' . $countID . '()"><span class="glyphicon glyphicon-trash"></span></a></td>';
                                                        echo '</tr>'; 
                                                    }
                                                }  
                                            echo '
                                            </tbody>
                                        </table>
                                            
                                        <br><br>
                                        <p>Enter the person email to share with</p>
                                        <input id="txtEmail" name="txtEmail" type="text" class="form-control" placeholder="Registered Email"> 
                                        </div>


                                        <script> 
                                            $(document).ready(function(){   
                                                $("#fSharing").hide();
                                                $("#fSharing:input").attr("disabled", true);

                                                if ( $("#ddlFilePermission").val() == "private" || $("#ddlFilePermission").val() == "Private") {
                                                    $("#fSharing").show();
                                                    $("#fSharing:input").attr("disabled", false);
                                                  }

                                                $("#ddlFilePermission").on("change", function() {
                                                  if ( this.value == "private" || this.value == "Private") {
                                                    $("#fSharing").show();
                                                    $("#fSharing:input").attr("disabled", false);
                                                  } else {
                                                    $("#fSharing").hide();
                                                    $("#fSharing:input").attr("disabled", true);
                                                  }
                                                });
                                            }); 

                                            $(document).ready(function(){
                                                $("#txtEmail").on("change", function() {
                                                var usr = $("#txtEmail").val();
                                                if(usr.length >= 8){ 
                                                    $.ajax({ 
                                                    type: "POST", 
                                                    url: "checkEmail.php", 
                                                    data: "email="+ usr,
                                                    dataType: "text",
                                                        success: function(msg){
                                                            if(msg == "OK"){
                                                                $("#txtEmail").css("backgrouond-color", "green");
                                                            } else {
                                                                $("#txtEmail").addClass("border-color", "red");
                                                           }
                                                        }

                                                    });
                                                }  
                                                });
                                            });
                                        </script> 
                                        
                                                <button class="btn btn-lg btn-block btn-success" name="save" type="submit">Save</button>
                                            </div>
                                        </div> 
                                    </form> 
                                    

                                </div>
                                <!--edit modal content end-->
                            </div>
                        </div>
                    </div>
                </div>';
            }
            
            if ($row["state"] == 1) {
            // Modal DELETE
            echo '<div id="del' . $countID . '" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
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
                                                <input type="hidden" name="actionDelete" value="' . $fileHashing . '" />
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
            
            if ($row["state"] == 0) {
            // Modal DELETE (UNLINK FILES)
            echo '<div id="deI' . $countID . '" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content resetmodal">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="glyphicon glyphicon-remove"></i></button>
                            </div>
                            <div class="modal-body">
                                <div class="deleteBlock" style="text-align: center;">
                                    <h1>' . $row["fileName"] . '</h1>
                                    <p>Are you sure you want to unlink <strong>' . $row["fileName"] . '</strong>?.</p>
                                </div>
                                <!--delete modal content -->
                                <div class="deleteBlock">
                                    <form data-toggle="validator" method="post" action="fileAction.php" class="form-horizontal" role="form" >
                                        <div class="form-group">
                                            <div class="col-sm-12" style="text-align: center;"> 
                                                <input type="hidden" name="actionDeIete" value="' . $fileHashing . '" />
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
        //Run SQL statement: to query for the shared  
        $stmt = $conn->prepare("SELECT a.email, fs.* FROM fileSharing fs INNER JOIN account a ON fs.accountID = a.accountID WHERE fs.fileID = ? AND fs.owner = 0 ORDER BY fs.fileSharingID ASC");
	$stmt->bind_param("i", $row['fileID']);
        $stmt->execute();
        $resultSharedDel = $stmt->get_result();

        echo '<form id="delShareForm" name="delShareForm" method="post" action="fileAction.php">
                <input type="hidden" id="sharedEmail" name="sharedEmail" />
                <input type="hidden" id="actionDelShare" name="actionDelShare" />
                <input type="hidden" id="prevURL" name="prevURL" /> 
                    </form>';
        
        if ($resultSharedDel->num_rows > 0) { //(result)
            //Loop tdrough tde result and print tde data to tde table  
            while ($rowSharedDel = $resultSharedDel->fetch_assoc()) { 
                $sharedEmail = $rowSharedDel["email"]; 
                
                $hashSharedFileID = password_hash($rowSharedDel["fileSharingID"] . $rowSharedDel["email"] . $_SESSION['SESS_ACC_ID'], PASSWORD_BCRYPT);

                if (isset($_SESSION['fileSharedArray'])) {
                    foreach ($_SESSION['fileSharedArray'] as $product) {
                        if ($product['hashID'] == $hashSharedFileID) {
                            $countID = $product['countID'];
                            break;
                        }
                    }

                    echo ' 
                    <script>
                    function myFunction' . $countID . '() { 
                        if (confirm("Are you sure you want to unlink file with ' . $sharedEmail . '") == true) { 
                                document.getElementById("actionDelShare").value = "' . $countID . '";
                                document.getElementById("sharedEmail").value = "' . $sharedEmail . '";
                                document.getElementById("prevURL").value = "' . $_SERVER["REQUEST_URI"] . '";
                                document.forms["delShareForm"].submit(); 
                        }  
                    }
                    </script>';
                }
            }
        }
    }
}
$stmt->close();
?>