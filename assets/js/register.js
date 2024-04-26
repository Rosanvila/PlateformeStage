document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("registration_form_picture").onchange = function() {
        const reader = new FileReader();
            reader.onload = function() {
                document.getElementById("picturePreview").src = reader.result;
            };
            reader.readAsDataURL(this.files[0]);
        };
});