<div class="bg-light p-2">
    <div style="height:300px">
        <div class="text-center pt-5">
            <i class="bi bi-collection-play-fill text-primary p-0 m-0 lh-sm" style="font-size:100px"></i>
            <h4 class="text-primary "
                style="font-size:40px"><?= number_format($data['packages_count']); ?> <?= lang('main.packages_available'); ?></h4>
            <h5 class="py-2 fw-light"><?= lang('main.package_list_sentence'); ?></h5>
        </div>
    </div>
</div>
<div class="light-box-shadow bg-white">

    <div class="container-xxl px-lg-5 px-md-3  my-5 pb-5 pt-3">
        <div class="row pb-5">
            <?php
            if (empty($data['list'])) {
                echo '<div class="col-12">
                            <div class="text-secondary text-center p-5 display-3">
                                <i class="bi bi-collection"></i><br/>
                                <span class="display-6">' . lang('main.list_is_empty') . '</span>
                            </div>
                        </div>';
            }
            foreach ($data['list'] as $item) {
                $buy_limit_progress = '';

                if ($item['buy_limit'] !== '-1' && !isFree($item['price'], $item['discount'], $item['discount_type'])) {
                    $count = intval($item['buy_limit']) - $item['sold'];
                    $percent = (intval($item['sold']) * 100) / intval($item['buy_limit']);
                    $buy_limit_progress = '<div class="progress mt-2 ltr" style="height:5px">
                                  <div class="progress-bar progre bg-success" role="progressbar" style="width: ' . $percent . '%" aria-valuenow="' . $percent . '" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>';
                    if ($count == 0) {
                        $buy_limit_progress .= '<div class="mt-1 text-center text-danger small">' . lang('main.package_sale_is_over') . '</div>';
                    } else {
                        $buy_limit_progress .= '<div class="mt-1 text-start text-success small">' . $count . ' ' . lang('main.purchase_left_to_run_off_stock') . '</div>';
                    }
                }

                if ($item['discount'] !== null) {
                    $discount_label = '<span class="badge bg-warning float-left mr-1 px-2 py-1 fs-6 m-0 rounded-pill">';
                    if ($item['discount_type'] == 'money') {
                        $discount_label .= number_format($item['discount']) . ' ' . $this->currency;
                    } else {
                        $discount_label .= $item['discount'] . '%';
                    }
                    $discount_label .= '</span>';
                    $pprice = '<div class="clearfix">' . $discount_label . '<del class="rounded-pill text-reset badge bg-white-glass fs-6 m-0 px-1 py-1 float-left">' . number_format($item['price']) . '</del></div><div class="mt-2 text-success">' . number_format(calcDiscount($item['price'], $item['discount'], $item['discount_type'])) . ' ' . $this->currency . '</div>';
                } else {
                    $discount_label = '';
                    $pprice = '<div class="clearfix"><span class="badge float-left mr-1 px-2 py-1 fs-6 m-0 rounded-pill">&nbsp;</span></div><div class="mt-2 text-success">' . number_format($item['price']) . ' ' . $this->currency . '</div>';
                }

                echo '<div class="col-lg-3 mt-4 col-md-4 col-sm-6 col-12">
                        <div class="card packItem border-0 light-box-shadow rounded">
                            <a target="_blank" href="' . base_url('package/single/' . $item['id']) . '" class="text-reset">
                            <img class="card-img" style="background:' . colors_to_gradient($item['most_used_colors']) . '" src="' . base_url('public/demos/packages/covers/' . $item['coded_name']) . '"/>
                            <div class="price card-body text-left ' . ((!empty($buy_limit_progress)) ? 'haveProgress' : '') . '">
                                <a class="text-decoration-none text-reset" href="' . base_url('package/single/' . $item['id']) . '">
                                    <span><h6 class="py-0 my-0"> ' . $pprice . '</h6></span>
                                </a>
                            </div>
                            <div class="details">
                                <a target="_blank" href="' . base_url('package/single/' . $item['id']) . '" class="text-decoration-none text-reset">
                                <h5>' . switch_key($item, 'title', $this->ls) . '</h5>
                                <h6 class="fw-light pt-2">' . switch_key($item, 'short_desc', $this->ls) . '</h6>
                                <div class="position-absolute" style="bottom:10px; width:calc(100% - 40px)">' . $buy_limit_progress . '</div>
                                 </a>
                            </div>
                            </a>
                        </div>
                    </div>';


            }
            ?>
        </div>
        <?php if (!empty($data['list'])) {
            $links = 5;

            $last = ceil($data['packages_count'] / $data['limit']);

            $start = (($data['page'] - $links) > 0) ? $data['page'] - $links : 1;
            $end = (($data['page'] + $links) < $last) ? $data['page'] + $links : $last;

            $html = '<ul class="pagination justify-content-center">';

            $class = ($data['page'] == 1) ? "disabled" : "";
            $html .= '<li class="page-item ' . $class . '"><a class="page-link" href="' . ($data['page'] - 1) . '">&laquo;</a></li>';

            if ($start > 1) {
                $html .= '<li class="page-item"><a class="page-link" href="1">1</a></li>';
                $html .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
            }

            for ($i = $start; $i <= $end; $i++) {
                $class = ($data['page'] == $i) ? "active" : "";
                $html .= '<li class="page-item ' . $class . '"><a class="page-link" href="' . $i . '">' . $i . '</a></li>';
            }

            if ($end < $last) {
                $html .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
                $html .= '<li class="page-item"><a class="page-link" href="' . $last . '">' . $last . '</a></li>';
            }

            $class = ($data['page'] == $last) ? "disabled" : "";
            $html .= '<li class="page-item ' . $class . '"><a class="page-link" href="' . ($data['page'] + 1) . '">&raquo;</a></li>';

            $html .= '</ul>';


            ?>
            <div>
                <nav aria-label="Page navigation example">
                    <ul class="pagination justify-content-center">
                        <?= $html; ?>
                    </ul>
                </nav>
            </div>
        <?php } ?>
    </div>
</div>