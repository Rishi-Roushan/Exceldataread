<!DOCTYPE html>
<html>

<head>
    <title>Reading Excel Data</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.5/xlsx.min.js"></script>
    <style>
    .modal-backdrop.in {
        filter: alpha(opacity=50);
        opacity: 0.5;
    }

    td,
    th {
        padding: 13px;
    }
    </style>
</head>

<body>
    <div class="container1" id="first_modal">
        <h2></h2>
        <!-- Trigger the modal with a button -->
        <button type="button" class="btn btn-info btn-lg pull-right" data-toggle="modal" data-target="#myModal">Click
            here to
            upload file</button>
        <!-- Modal -->
        <div class="modal" id="myModal" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title" id="modal_title">Upload Your Excel File Here</h4>
                    </div>
                    <div class="modal-body">
                        <div class="input_field">
                            <input type="file" id="file_upload" />
                            <button onclick="upload()">Upload</button>
                        </div>
                        <div class="table_div">
                            <table id="display_excel_data" border="1"
                                style="width: 100%; text-align:revert;margin-left:2px;"></table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <script>
        $(document).ready(function() {});
        // Method to upload a valid excel file
        function upload() {
            var files = document.getElementById('file_upload').files;
            if (files.length == 0) {
                alert("Please choose any file...");
                return;
            }
            var filename = files[0].name;
            var extension = filename.substring(filename.lastIndexOf(".")).toUpperCase();
            if (extension == '.XLS' || extension == '.XLSX') {
                $(".input_field").hide();
                //Here calling another method to read excel file into json
                excelFileToJSON(files[0]);
            } else {
                alert("Please select a valid excel file.");
            }
        }
        //Method to read excel file and convert it into JSON 
        function excelFileToJSON(file) {
            try {
                var reader = new FileReader();
                reader.readAsBinaryString(file);
                reader.onload = function(e) {

                    var data = e.target.result;
                    var workbook = XLSX.read(data, {
                        type: 'binary'
                    });
                    var result = {};
                    var firstSheetName = workbook.SheetNames[0];
                    //reading only first sheet data
                    var jsonData = XLSX.utils.sheet_to_json(workbook.Sheets[firstSheetName]);
                    //displaying the json result into HTML table
                    displayJsonToHtmlTable(jsonData);
                }
            } catch (e) {
                console.error(e);
            }
        }
        //Method to display the data in HTML Table
        function displayJsonToHtmlTable(jsonData) {
            var table = document.getElementById("display_excel_data");
            if (jsonData.length > 0) {
                $("#modal_title").html('');
                $("#modal_title").html('Please Check your Excel File Data here');
                $(".table_div").show();
                var htmlData =
                    '<tr><th>Name</th><th>Address</th><th>Marks</th><th>Percentage</th></tr>';
                for (var i = 0; i < jsonData.length; i++) {
                    var row = jsonData[i];
                    //you can change your excel Data as per requirement
                    htmlData += '<tr><td>' + row["Name"] + '</td><td>' + row["Address"] +
                        '</td><td>' + row["Marks"] + '</td><td>' + row["Percentage"] + '</td></tr>';
                }
                table.innerHTML = htmlData;
            } else {
                table.innerHTML = 'There is no data in Excel';
            }
        }
        </script>
</body>

</html>