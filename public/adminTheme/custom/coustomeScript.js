 $(document).ready(function(){
    $('.image-input').change(function(){
        const input = $(this);
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                input.siblings('img.image-input-preview').attr('src', event.target.result).show();
            }
            reader.readAsDataURL(file);
        }
    });
});