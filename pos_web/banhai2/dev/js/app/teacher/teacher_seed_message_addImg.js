var img_num = 20;//控制图片数量
var loadImageFile = (function () {
    if (window.FileReader) {
        var oPreviewImg = null, oFReader = new window.FileReader(),
            rFilter = /^(?:image\/bmp|image\/cis\-cod|image\/gif|image\/ief|image\/jpeg|image\/jpeg|image\/jpeg|image\/pipeg|image\/png|image\/svg\+xml|image\/tiff|image\/x\-cmu\-raster|image\/x\-cmx|image\/x\-icon|image\/x\-portable\-anymap|image\/x\-portable\-bitmap|image\/x\-portable\-graymap|image\/x\-portable\-pixmap|image\/x\-rgb|image\/x\-xbitmap|image\/x\-xpixmap|image\/x\-xwindowdump)$/i;
        oFReader.onload = function (oFREvent) {
            if(img_num!=0) {
                var newPreview = document.createElement('li');
                oPreviewImg = new Image();
                oPreviewImg.style.width = "179px";
                oPreviewImg.style.height = "120px";
                newPreview.innerHTML = '<i class="remove_images"></i>';
                newPreview.appendChild(oPreviewImg);
                var img_ul = document.getElementById('images_ul');
                img_ul.insertBefore(newPreview, document.getElementById('imageInput').parentNode);
                img_num--;
                if (img_num == 0) {
                    $("#remove_img").css('display', 'none');
                }
                document.getElementById('img_num').innerHTML = img_num;
                oPreviewImg.src = oFREvent.target.result;
            }
        };
        return function () {
            var aFiles = document.getElementById("imageInput").files;
            if (aFiles.length === 0) {
                return;
            }
            if (!rFilter.test(aFiles[0].type)) {
                alert("You must select a valid image file!");
                return;
            }
            oFReader.readAsDataURL(aFiles[0]);
        }
    }
})();