<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Media to HTML Converter</title>
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 20px;
    }
    #drop-area {
        border: 2px dashed #ccc;
        border-radius: 20px;
        padding: 20px;
        text-align: center;
        cursor: pointer;
    }
    #result {
        margin-top: 20px;
    }
    #output {
        width: 100%;
        height: 100px;
        margin-top: 20px;
        resize: none;
    }
</style>
</head>
<body>
<div id="drop-area">
    <h3>Drop media files here</h3>
    <p>Or click to select files</p>
    <input type="file" id="fileElem" multiple accept="image/*, video/*" onchange="handleFiles(this.files)">
</div>
<div id="result"></div>
<input type="text" id="output" readonly>
<p>
Copy/paste the above result into chatter to send!
</p>

<script>
function handleFiles(files) {
    const dropArea = document.getElementById('drop-area');
    const resultDiv = document.getElementById('result');
    const output = document.getElementById('output');
    let htmlCode = '';

    // Clear previous results
    resultDiv.innerHTML = '';

    for (const file of files) {
        const reader = new FileReader();

        reader.onload = function(event) {
            const dataURI = event.target.result;
            const type = file.type.split('/')[0];

            if (type === 'image') {
                htmlCode += '<br><img src="' + dataURI + '"><br>';
            } else if (type === 'video') {
                htmlCode += '<br><video src="' + dataURI + '" controls></video><br>';
            }
            output.value = htmlCode;
        };

        reader.readAsDataURL(file);
    }

    dropArea.style.borderStyle = 'dashed';
    dropArea.style.borderColor = '#ccc';
}

// Drag and drop event handlers
['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
    dropArea.addEventListener(eventName, preventDefaults, false);
});

function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
}

dropArea.addEventListener('drop', handleDrop, false);

function handleDrop(e) {
    const dt = e.dataTransfer;
    const files = dt.files;

    handleFiles(files);
}
</script>
</body>
</html>
