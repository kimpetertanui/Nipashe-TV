<?php include('includes/config.php'); ?>

</section>
</div>
<!-- END WRAPPER -->

</div>
<!-- END MAIN -->
<!-- START FOOTER -->
<footer class="footer">
    <div class="container">
        <span class="left span-padding grey-text text-lighten-4"> Copyright © 2020 <a class="grey-text text-lighten-4" href="https://kimpetertanui.github.io/" target="_blank">Peter Kimeli</a> All rights reserved.</span>

        <span class="right span-padding grey-text text-lighten-4"> Design and Developed by <a class="grey-text text-lighten-4" href="https://kimpetertanui.github.io/" target="_blank">Peter Kimeli</a></span>
    </div>
</footer>
<!-- END FOOTER -->


<!-- ================================================
Scripts
================================================ -->

<!-- jQuery Library -->
<script type="text/javascript" src="assets/js/jquery-1.11.2.min.js"></script>
<!--materialize js-->
<script type="text/javascript" src="assets/js/materialize.js"></script>
<!--prism-->
<script type="text/javascript" src="assets/js/prism.js"></script>
<!--scrollbar-->
<script type="text/javascript" src="assets/js/plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>

<!-- data-tables -->
<script type="text/javascript" src="assets/js/plugins/data-tables/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="assets/js/plugins/data-tables/data-tables-script.js"></script>

<!-- chartist -->
<script type="text/javascript" src="assets/js/plugins/chartist-js/chartist.min.js"></script>

<!--plugins.js - Some Specific JS codes for Plugin Settings-->
<script type="text/javascript" src="assets/js/plugins.js"></script>
<script type="text/javascript" src="assets/js/dropify.js"></script>

<script type="text/javascript">
    /*Show entries on click hide*/
    $(document).ready(function() {
        $(".dropdown-content.select-dropdown li").on( "click", function() {
            var that = this;
            setTimeout(function() {
                if ($(that).parent().hasClass('active')) {
                    $(that).parent().removeClass('active');
                    $(that).parent().hide();
                }
            },0);
        });
    });
</script>

<script>
    $(document).ready(function(){
        // Basic
        $('.dropify').dropify();

        // Translated
        $('.dropify-fr').dropify({
            messages: {
                default: 'Glissez-déposez un fichier ici ou cliquez',
                replace: 'Glissez-déposez un fichier ou cliquez pour remplacer',
                remove:  'Supprimer',
                error:   'Désolé, le fichier trop volumineux'
            }
        });

        $('.dropify-image').dropify({
            messages: {
                default : '<center>Drag and drop a image here or click</center>',
                error   : 'Ooops, something wrong appended.'
            },
            error: {
                'fileSize': '<center>The file size is too big broo ({{ value }} max).</center>',
                'minWidth': '<center>The image width is too small ({{ value }}}px min).</center>',
                'maxWidth': '<center>The image width is too big ({{ value }}}px max).</center>',
                'minHeight': '<center>The image height is too small ({{ value }}}px min).</center>',
                'maxHeight': '<center>The image height is too big ({{ value }}px max).</center>',
                'imageFormat': '<center>The image format is not allowed ({{ value }} only).</center>',
                'fileExtension': '<center>The file is not allowed ({{ value }} only).</center>'
            },
        });

        $('.dropify-video').dropify({
            messages: {
                default: '<center>Drag and drop a video here or click</center>'
            }
        });

        $('.dropify-notification').dropify({
            messages: {
                default : '<center>Drag and drop a image here or click<br>(Optional)</center>',
            }
        });

        // Used events
        var drEvent = $('#input-file-events').dropify();

        drEvent.on('dropify.beforeClear', function(event, element){
            return confirm("Do you really want to delete \"" + element.file.name + "\" ?");
        });

        drEvent.on('dropify.afterClear', function(event, element){
            alert('File deleted');
        });

        drEvent.on('dropify.errors', function(event, element){
            console.log('Has Errors');
        });

        var drDestroy = $('#input-file-to-destroy').dropify();
        drDestroy = drDestroy.data('dropify')
        $('#toggleDropify').on('click', function(e){
            e.preventDefault();
            if (drDestroy.isDropified()) {
                drDestroy.destroy();
            } else {
                drDestroy.init();
            }
        })
    });
</script>

</body>

</html>