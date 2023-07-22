document.addEventListener('DOMContentLoaded', () => {

    let custom = document.getElementById('custom');

    if(custom !== null) {
        customElements.define('spotlight-bar', Spotlight)
    }

    let carousel = document.getElementById('carousel');

    let ck1 = document.getElementById('wysiwyg_accordion')
    let ck2 = document.getElementById('wysiwyg_accordion_2')

    if (carousel !== null) {
        new Carousel(carousel, {
            slidesToScroll: 1,
            slidesVisible: 3,
            loop: true
        })
    }

    if (ck1 !== null && ck2 !== null) {
        CKEDITOR.replace(ck1, {
            filebrowserUploadUrl: '/ck_upload.php?CKEditorFuncNum = 1',
            filebrowserUoloadMethod: 'form'
        })
        CKEDITOR.replace(ck2, {
            filebrowserUploadUrl: '/ck_upload.php?CKEditorFuncNum = 1',
            filebrowserUoloadMethod: 'form'
        })
    }

})
