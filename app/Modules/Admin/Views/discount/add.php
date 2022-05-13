<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Add new discount</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a class="btn btn-secondary" href="list">
            <span data-feather="list"></span>
            Manage discounts
        </a>
    </div>
</div>
<div class="alert <?= $data['alert_class']; ?>">
    <?= $data['alert_message']; ?>
</div>
<form action="" method="POST" enctype="multipart/form-data">
    <div class="row">
        <div class="col-md-6">
            <h6>Title:</h6>
            <input type="text" name="title" class="form-control" value="<?= $post['title']; ?>"/>
        </div>
        <div class="col-md-6 mt-3 mt-md-0">
            <h6>Persian title:</h6>
            <input type="text" name="title_fa" class="rtl form-control" placeholder="Leave blank, if it is the same"
                   value="<?= $post['title_fa']; ?>"/>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-3">
            <h6>Photo:</h6>
            <input type="file" name="photo" class="form-control">
        </div>
        <div class="col-md-3 mt-3 mt-md-0">
            <h6>Type:</h6>
            <select class="form-control" name="type" id="type">
                <option value="" disabled <?= (!empty($post['type'])) ? '' : 'selected'; ?>>Select a type of discount
                </option>
                <option <?= ($post['type'] == 'track') ? 'selected' : ''; ?> value="track">Track</option>
                <option <?= ($post['type'] == 'package') ? 'selected' : ''; ?> value="package">Package</option>
            </select>
        </div>
        <div class="col-md-6 mt-3 mt-md-0">
            <h6>Select item(s):</h6>
            <div class="<?= ($post['type'] == 'track') ? '' : 'd-none';?>" id="track_select_div">
                <select <?= ($post['type'] == 'track') ? '' : 'disabled';?> name="items[]" data-live-search="true" id="track_select"
                        class="selectpicker form-control" multiple>
                    <?php
                    if($post['type'] == 'track' && !empty($post['items'])){
                        foreach($data['selected_items'] as $key => $value){
                            echo '<option selected value="'.$value['id'].'">'.$value['title'].'</option>';
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="<?= ($post['type'] == 'package') ? '' : 'd-none';?>" id="package_select_div">
                <select <?= ($post['type'] == 'package') ? '' : 'disabled';?> name="items[]" data-live-search="true" id="package_select" class="selectpicker form-control"
                        multiple>
                    <?php
                    if($post['type'] == 'package' && !empty($post['items'])){
                        foreach($data['selected_items'] as $key => $value){
                            echo '<option selected value="'.$value['id'].'">'.$value['title'].'</option>';
                        }
                    }
                    ?>
                </select>
            </div>

        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-4">
            <h6>Discount type:</h6>
            <select class="form-control" name="discount_type">
                <option <?= ($post['discount_type'] == 'money') ? 'selected' : ''; ?> value="money">Monetary discount
                </option>
                <option <?= ($post['discount_type'] == 'percent') ? 'selected' : ''; ?> value="percent">Percentage
                    discount
                </option>
            </select>
        </div>
        <div class="col-md-4 mt-3 mt-md-0">
            <h6>Discount value:</h6>
            <input type="number" name="discount" value="<?= $post['discount']; ?>" class="form-control"/>
        </div>
        <div class="col-md-4 mt-3 mt-md-0">
            <h6>Expires after (days):</h6>
            <input type="number" name="expire" value="<?= $post['expire']; ?>" class="form-control"/>
        </div>
    </div>
    <div class="mt-3 mb-5">
        <h6>
            <input type="checkbox" <?= ($post['saveindrafts'] == 'on') ? 'checked' : ''; ?> name="saveindrafts"
                   id="saveindrafts">
            <label class="small" for="saveindrafts">Save in drafts</label>
        </h6>
        <input type="submit" name="submit" class="btn btn-success mt-4" value="Submit">
    </div>
</form>

<script>
    $(document).ready(function () {

        $('#track_select').select2({
            theme: 'bootstrap-5',
            liveSearch:true,
            minimumInputLength: 1,
            ajax: {
                url: base_url+'/track/ajaxItems',
                dataType: 'json',
                contentType: 'application/json',
                delay: 250,
                data: function (params) {
                    return {
                        value: params.term,
                        discount: true
                    };
                },
                processResults: function (data) {
                    return {
                        results: $.map(data, function(obj){
                            if(parseInt(obj.discounted) < 1){
                                return {id: obj.id, text: obj.title};
                            }
                        })
                    };
                }
            }
        });

        $('#package_select').select2({
            theme: 'bootstrap-5',
            liveSearch:true,
            minimumInputLength: 1,
            ajax: {
                url: base_url+'/package/ajaxItems',
                dataType: 'json',
                contentType: 'application/json',
                delay: 250,
                data: function (params) {
                    return {
                        value: params.term,
                        discount: true
                    };
                },
                processResults: function (data) {
                    return {
                        results: $.map(data, function(obj){
                            if(parseInt(obj.discounted) < 1){
                                return {id: obj.id, text: obj.title};
                            }
                        })
                    };
                }
            }
        });

        $('#type').on('change', function () {
            if (this.value == 'track') {

                $('#package_select_div').addClass('d-none');
                $('#track_select_div').removeClass('d-none');
                $('#track_select').prop('disabled', false);
            } else if (this.value == 'package') {
                $('#track_select_div').addClass('d-none');
                $('#package_select_div').removeClass('d-none');
                $('#package_select').prop('disabled', false);
            }

        });

    });
</script>