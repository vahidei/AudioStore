<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Add new category</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a class="btn btn-secondary" href="list">
            <span data-feather="list"></span>
            Manage categories
        </a>
    </div>
</div>
<div class="alert <?=$data['alert_class'];?>">
    <?=$data['alert_message'];?>
</div>
<form action="" method="POST">
    <div class="row">
        <div class="col-md-6">
            <h6>Title:</h6>
            <input type="text" name="title" class="form-control" value="<?=$post['title'];?>"/>
        </div>
        <div class="col-md-6 mt-3 mt-md-0">
            <h6>Persian title:</h6>
            <input type="text" name="title_fa" class="rtl form-control" placeholder="Leave blank, if it is the same" value="<?=$post['title_fa'];?>"/>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-6">
            <h6>Color:</h6>
            <input type="text" name="color" class="form-control" value="<?=$post['color'];?>"/>
        </div>
    </div>

    <div class="mt-3">

        <input type="submit" name="submit" class="btn btn-success mt-4" value="Submit">
    </div>
</form>