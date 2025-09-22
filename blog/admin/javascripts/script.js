ClassicEditor
.create( document.querySelector( '#content' ),{
  ckfinder:
  {
    uploadUrl: 'fileupload.php'
  }
})
.then(editor => {
  console.log(editor);
})
.catch( error => {
    console.error( error );
});