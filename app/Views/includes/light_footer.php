<?php $cookie = get_cookie('lang'); ?>

<div class="modal fade" id="setLangModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    Choose your language
                </h5></div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-6">
                        <a class="btn btn-light langBtn w-100 py-3" href="<?=base_url('lang/fa');?>"><b>فارسی</b></a>
                    </div>
                    <div class="col-sm-6">
                        <a class=" btn btn-light langBtn w-100 py-3 mt-3 mt-sm-0 mt-lg-0" href="<?=base_url('lang/en');?>">
                            <b>English</b>

                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>


<script src="<?=base_url('public/js/owl.carousel.min.js');?>"></script>
<script src="<?=base_url('public/plugin/home-player/home-player.js');?>"></script>
<script src="<?=base_url('public/plugin/home-player/plyr.min.js');?>"></script>
<script src="<?=base_url('public/js/app.js');?>"></script>



<script>
    function onSubmit(token) {
        document.getElementById("demo-form").submit();
    }
</script>

<script>
    function onClick(e) {
        e.preventDefault();
        grecaptcha.ready(function() {
            grecaptcha.execute('6LdZpxYcAAAAABsRrAN2To890s6_YzDdJd1RBQkf', {action: 'submit'}).then(function(token) {
                // Add your logic to submit to your backend server here.
            });
        });
    }
</script>
<?php

if (!isset($cookie)) {
    echo "<script>$(document).ready(function(){
                $('#setLangModal').modal('show')  ;
            })</script>";
}
?>
</body>
</html>
