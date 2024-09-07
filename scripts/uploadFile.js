function uploadFile(event) {
  const fileInput = event.target;
  const file = fileInput.files[0]; // Get the first selected file

  if (file) {
    // Update the upload-info div with the file name
    const uploadInfo = document.getElementById("upload-info");
    uploadInfo.textContent = `Uploaded: ${file.name}`;
  } else {
    // Reset to default message if no file is selected
    const uploadInfo = document.getElementById("upload-info");
    uploadInfo.textContent = "No file uploaded";
  }
}
