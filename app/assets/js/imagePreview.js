var loadPhoto = function(event, dw) {
    var reader = new FileReader();
    reader.onload = function(){
        var output = document.getElementById('user-photo');
        output.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
};

export function createPreviewById(elementId){
    let editPhotoField = document.getElementById(elementId)
    if(editPhotoField){
        editPhotoField.onchange = loadPhoto;
    } else {
        console.warn('Field with id: "' + elementId + '" was not found')
    }
}
