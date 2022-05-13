</main>
</div>
</div>

<script>
    $(document).ready(function(){
        $('#checkAllDelete').click(function(){
            if($(this).is(':checked')){
                $('.checkDelete').prop('checked', true);
            } else{
                $('.checkDelete').prop('checked', false);
            }
        });

        $('#buylimitdisabled').click(function(){
            if($(this).is(':checked')){
                $('#buy_limit').prop('disabled', false);
            } else {
                $('#buy_limit').prop('disabled', true);
            }
        });
    });


</script>

<script src="<?=base_url('public/js/select2.min.js');?>"></script>
<script src="<?=base_url('public/js/chart.min.js');?>"></script>

<script src="<?=base_url('public/js/feather.min.js');?>"></script>
<script src="<?=base_url('public/js/bootstrap.min.js');?>"></script>

<script src="<?=base_url('public/admin/js/dashboard.js');?>"></script>


</body>
</html>
