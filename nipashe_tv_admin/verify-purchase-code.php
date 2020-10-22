<?php include('session.php'); ?>
    <script src="assets/js/ckeditor/ckeditor.js"></script>
    <script src="assets/js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="assets/js/jquery.validate.min.js"></script>

    <script type="text/javascript">

        (function($,W,D) {
            var JQUERY4U = {};
            JQUERY4U.UTIL = {
                setupFormValidation: function() {
                    //form validation rules
                    $("#form-validation").validate({
                        rules: {
                            item_purchase_code	: "required"
                        },

                        messages: {
                            item_purchase_code : "Please fill out with your item purchase code!"

                        },
                        errorElement : 'div',
                        submitHandler: function(form) {
                            form.submit();
                        }

                    });
                }
            }

            //when the dom has loaded setup form validation rules
            $(D).ready(function($) {
                JQUERY4U.UTIL.setupFormValidation();
            });

        })(jQuery, window, document);

    </script>
<?php include('public/verify-purchase-code-form.php'); ?>