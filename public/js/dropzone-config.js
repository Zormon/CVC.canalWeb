var total_photos_counter = 0;
Dropzone.options.myDropzone = {
    uploadMultiple: true,
    parallelUploads: 3,
    maxFilesize: 120,
    addRemoveLinks: false,
    acceptedFiles: 'image/*,video/*',
    dictRemoveFile: 'Remove file',
    dictFileTooBig: 'EL archivo es superior a 120MB',
    previewTemplate: document.querySelector('#preview').innerHTML,
    previewsContainer: '#previewUploader',
    timeout: 90000,
    success: function (file, done) {
        location.reload();
    }
};
