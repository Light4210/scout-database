var loadPhoto = function(event, dw) {
    var reader = new FileReader();
    reader.onload = function(){
        var output = document.getElementById('user-photo');
        output.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
};

function createPreviewById(elementId){
    let editPhotoField = document.getElementById(elementId)
    if(editPhotoField){
        editPhotoField.onchange = loadPhoto;
    }
}

createPreviewById("user_edit_photo")
createPreviewById("user_create_photo")
