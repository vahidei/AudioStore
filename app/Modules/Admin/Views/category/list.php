<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Manage categories</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a class="btn btn-success" href="add">
            <span data-feather="plus"></span>
            Add new category
        </a>
    </div>
</div>
<div class="alert <?= $data['alert_class']; ?>">
    <?= $data['alert_message']; ?>
</div>
<form action="list" method="post" id="form">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
            <tr>
                <th scope="col">
                    <input id="checkAllDelete" type="checkbox">
                </th>
                <th scope="col">ID</th>
                <th scope="col">Title</th>
                <th scope="col">Color</th>
                <th scope="col">Last update</th>
                <th scope="col">Created at</th>
                <th scope="col">Edit</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if (empty($data['items'])) {
                echo '<tr class="bg-light"><td colspan="7"><h6 class="text-center">The list is empty.</h6></td></tr>';
            } else {
                foreach ($data['items'] as $key => $item) {
                    echo '<tr>
                            <td>
                                <input class="checkDelete" name="deleteItems[' . $item['id'] . ']" type="checkbox">
                            </td>
                            <td>' . $item['id'] . '</td>
                            <td>' . $item['title'] . '</td>
                            <td>' . $item['color'] . '</td>
                            <td>' . $item['last_update'] . '</td>
                            <td>' . $item['created_at'] . '</td>
                            <td>
                                <a class="text-primary" href="edit/' . $item['id'] . '">
                                    <span data-feather="edit"></span>
                                </a>
                            </td>
                        </tr>';
                }
            }
            ?>
            </tbody>
        </table>
    </div>
    <?php if(!empty($data['items'])){ ?>
    <div class="mt-4 bg-light p-3">
        <input type="submit" onclick="if(!confirm('Are you sure?')) return false" class="btn btn-danger btn-sm"
               name="delete" value="Delete selected rows">
    </div>
    <?php } ?>
</form>