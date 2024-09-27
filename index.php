

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Single File Upload</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.7.0/css/fontawesome.min.css" rel="stylesheet">
  <script src='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.7.0/js/fontawesome.min.js' crossorigin='anonymous'></script>
</head>
<body>
    
    <?php
$target_dir = "uploads/"; // Directory where files will be uploaded

// Check if the upload directory exists, if not, create it
if (!is_dir($target_dir)) {
    mkdir($target_dir, 0777, true);
}
 $msg = "";
if ($_SERVER['REQUEST_METHOD'] == "POST") {
  
    //get data
    $name = $_POST['name'];
    //File manage
    $tmp_name = $_FILES['fileToUpload']['tmp_name'];
    $file_name = $_FILES['fileToUpload']['name'];
    $file_error = $_FILES['fileToUpload']['error'];
    $file_size = $_FILES['fileToUpload']['size'];
    $file_type = $_FILES['fileToUpload']['type'];
    
    $file_kb = ($file_size / 1024);
   
    if(empty($name) && !empty($file_name)){
        $msg = "* Name is Required";
    }elseif(!empty($name) && empty($file_name)){
        $msg = "* File is Required";
    }elseif(empty($name) && empty($file_name)){
        $msg = "* All Fields are Required";
    }elseif ($file_error !== UPLOAD_ERR_OK){
        $msg = "* Error Uploading File";
    }elseif ($file_size > 2000 * 1024){
        $msg = "* File Size is more than 100 KB";
    }elseif (!in_array($file_type, ['image/jpeg', 'image/png', 'image/jpg']) ){
        $msg = "* Only Jpg and Png file allowed";
    }
    else{
        move_uploaded_file($tmp_name, 'uploads/'. $file_name);
        $msg = "File uploaded Successfully";
    }
}


?>


<div class="container  mt-3">
    <div class="row mt-3">
        <div class="card shadow-sm col-md-4">
            <div class="card-body">
  <h2>Single File Upload</h2>
  <hr>
  <p id="uploadmsg" style="color:red;"><?php echo $msg;?></p>
  <h5 id="error" style="color:red;" style="font-size:14px;"></h5>
 
  
  <form action="" method="POST" enctype="multipart/form-data">
     <div class=" mt-3">
      <label for="Name">Name:</label>
      <input type="text" name="name" id="uname" class="form-control">
  
    </div>
    <div class=" mt-3 " s>
      <label for="File">Select File:</label>
      <div class="input-file">
      <input type="file" name="fileToUpload" onchange="displayFileSize()" id="fileToUpload" class="form-control" >
   <img src="upload.png" alt="">
   </div>
    </div>
    <div class=" mt-3">
      <label for="pwd">
      <button name = "submit" type="submit" class="btn btn-primary" >Submit</button>
      </label>
    </div>
    
   
    <hr>
    
  </form>
   <div class="img-div">
  <img class="cls-btnm" id="prv-img" src="nopreview.png" alt="">
  <button class="cls-btn" id="viewcls" onclick="closeBtn()">X</button>
  <hr>
  <p id="fileInfo" style="display:none;"></p>
   <p id="fileInfos" style="display:none;"></p>
  <!--<p id="fileInfokb" ></p>-->
    <p id="fileTypeEr" style="color:red; font-wight:bold;"></p>
  </div>
  
  </div>
 
 
  </div>
  </div>
</div>
<script>
    const imgFile = document.getElementById("fileToUpload");
const imgPrev = document.getElementById("prv-img");
const viewCls = document.getElementById("viewcls")

imgFile.onchange = (event) => {
    const imgUrl = URL.createObjectURL(event.target.files[0]); // Corrected here

    imgPrev.setAttribute('src', imgUrl);
    
     // Moved inside the function
    viewCls.style.display = "block";
     
};

  function closeBtn(){
      imgFile.value = "";
      viewCls.style.display = "none";
      imgPrev.setAttribute('src', "nopreview.png");
      fileInfo.textContent = '';
        fileInfos.textContent = '';
    error.textContent ='';
    fileTypeEr.textContent ='';
  }
  

  
// Select the file input and the div to display file info
const fileInput = document.getElementById('fileToUpload');
const fileInfo = document.getElementById('fileInfo');
const fileInfos = document.getElementById('fileInfos');
const fileInfokb = document.getElementById('fileInfokb');
const error = document.getElementById("error");
const fileTypeEr = document.getElementById("fileTypeEr");

const uploadmsg = document.getElementById("uploadmsg");

// Add an event listener for changes on the file input
fileInput.addEventListener('change', function(event) {
    // Clear previous file info
    fileInfo.innerHTML = '';
    // Clear previous File upload successfully message
    uploadmsg.innerHTML = '';
    // Get the selected file
    const file = event.target.files[0];

    // Check if a file was selected
    if (file) {
        // Create a new paragraph element to display file info
       // const info = document.createElement('p');
        
        // Get file properties
        
        const fileName = `File Name: ${file.name} `;
        
        // Size in KB
        const fileSize = `File Size: ${(file.size / 1024).toFixed(2)} KB`; 
        
        //File Type validation
        const fileType = file.name.split('.').pop().toLowerCase();
       

        if(fileType !== 'jpg' && fileType !== 'png'){
            
            fileTypeEr.textContent = "* File not supported";
            
        }else{
            fileTypeEr.textContent ='';
        }
        //if file size in more than 100 kb
        
        if(file.size > 1024*1024 / 2){
            
            error.textContent = "File size is more than 100 KB";
           
            
        }else{
            error.textContent = '';
        }
        
        
       
        // Set the text content of the paragraph
        fileInfo.style.display="block";
        fileInfos.style.display="block";
        fileInfo.textContent = `${fileName}`;
        fileInfos.textContent = `${fileSize}`;

        // Append the info to the fileInfo div
        fileInfo.appendChild(info);
    } else {
        // If no file was selected
        fileInfo.textContent = 'No file selected.';
    }
    
    
});

 fileInput.addEventListener('change', function() {
            messageDiv.textContent = ''; // Clear the message
        });
  
</script>


<style>
    .card{margin:auto;}
    .img-div img{
        width: 100%;
       
    }
    .img-div{
        position: relative ;
    }
    
    .cls-btn{
        position: absolute;
        top:10px;
        right: 10px;
        display:none;
        border-radius: 50%;
    height: 30px;
    width: 30px;
    background-color: red;
    color: #fff;
    font-weight: bold;
    border: 2px solid #fff;
    transition : .4s;
    }
    .cls-btn:hover{
        background-color: maroon;
    }
    .input-file{
        display: flex;
        
    }
    .input-file img{
        width: 50px;
    }
    
</style>
<!-- 
File type Validation
// This accesses the name of the selected file (e.g., example.jpg).
        //const fileName = file.name;

        // The split() method is called on the file name string, using '.' as the delimiter. 
        // This will create an array of substrings. For example:
        // example.jpg becomes ["example", "jpg"]
        // document.pdf becomes ["document", "pdf"]
        //const fileParts = fileName.split('.');
        
        // The pop() method is called on the resulting array. 
        // This method removes and returns the last element of the array, 
        // which, in this case, is the file extension. For example:
        // From ["example", "jpg"], it returns "jpg".
        //const fileType = fileParts.pop();
        
        // Finally, toLowerCase() is called to ensure that the file extension is in lowercase. 
        // This is useful for consistent comparison, as file extensions may be in different cases (e.g., .JPG, .jpg, etc.).
        //const fileTypeLower = fileType.toLowerCase();
-->

</body>
</html>


