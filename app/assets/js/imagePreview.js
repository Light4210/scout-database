var loadFile = function(event) {
    var reader = new FileReader();
    reader.onload = function(){
        var output = document.getElementById('user-photo');
        output.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
};

document.getElementById("user_edit_photo").onchange = loadFile;