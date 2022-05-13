<div class="container py-5 bg-white">
    <h4><?= lang('main.review_selected_items'); ?></h4>
    <?php
    if (isset($data['alert'])) {
        echo '<div class="alert alert-danger">' . $data['alert'] . '</div>';
    }

    if(isset($data['is_empty'])){
        echo '<div class="bg-light mt-4 py-5 text-center">
                    <i class="bi bi-basket3 display-3"></i>
                    <h4 class="pt-3">'.lang('main.your_cart_is_empty').'</h4>
                    <h6 class="pt-3"><a href="'.base_url().'">'.lang('main.back').'</a></h6>
                </div>';
        goto end;
    }

    ?>
    <form action="" method="post">
        <div class="table-responsive">
            <table class="mt-4 table table-light table-hover">
                <thead>
                <th class="bg-white"></th>
                <th class="bg-white"></th>
                <th class="bg-white"><?= lang('main.title'); ?></th>
                <th class="bg-white"><?= lang('main.initial_price'); ?></th>
                <th class="bg-white"><?= lang('main.discount'); ?></th>
                <th class="bg-white"><?= lang('main.final_price'); ?></th>
                <th class="bg-white"></th>
                </thead>
                <tbody>
                <?php
                $totalPrice = 0;
                $i = 1;
                foreach ($data['packages'] as $item) {
                    $fnprice = calcDiscount($item['price'], $item['discount'], $item['discount_type']);
                    $dprice = '0';
                    if (!empty($item['discount'])) {
                        $dprice = (($item['discount_type'] == 'money') ? $item['discount'] . ' ' . $this->currency : $item['discount'] . '%');
                    }
                    echo '<input type="hidden" name="packages[]" value="' . $item['id'] . '"/>';
                    echo '<tr>
                                <td class="py-3 text-center"></td>
                                <td class="py-3">' . $i++ . '</td>
                                <td class="py-3">' . switch_key($item, 'title', $this->ls) . '</td>
                                <td class="py-3">' . number_format($item['price']) . ' ' . $this->currency . '</td>
                                <td class="py-3">' . $dprice . '</td>
                                <td class="py-3">' . number_format($fnprice) . ' ' . $this->currency . '</td>
                                <td class="py-3">
                                    <a href="'.base_url('buy/final?type=package&remove='.$item['id']).'" class="btn btn-danger"><i class="bi bi-trash"></i> '.lang('main.remove_this').'</a>
                                </td>
                            </tr>';
                    $totalPrice += $fnprice;
                }

                foreach ($data['tracks'] as $item) {
                    $fnprice = calcDiscount($item['price'], $item['discount'], $item['discount_type']);
                    $dprice = '0';
                    if (!empty($item['discount'])) {
                        $dprice = (($item['discount_type'] == 'money') ? $item['discount'] . ' ' . $this->currency : $item['discount'] . '%');
                    }
                    echo '<input type="hidden" name="tracks[]" value="' . $item['id'] . '"/>';
                    echo '<tr>
                                <td class="py-3 text-center"></td>
                                <td class="py-3">' . $i++ . '</td>
                                <td class="py-3">' . switch_key($item, 'title', $this->ls) . '</td>
                                <td class="py-3">' . number_format($item['price']) . ' ' . $this->currency . '</td>
                                <td class="py-3">' . $dprice . '</td>
                                <td class="py-3">' . number_format($fnprice) . ' ' . $this->currency . '</td>
                                <td class="py-3">
                                    <a href="'.base_url('buy/final?type=track&remove='.$item['id']).'" class="btn btn-danger"><i class="bi bi-trash"></i> '.lang('main.remove_this').'</a>
                                </td>
                            </tr>';
                    $totalPrice += $fnprice;
                }
                ?>
                </tbody>
                <tfoot>
                <tr>
                    <td class="bg-white py-3" colspan="5">
                        <h4><?= lang('main.total_price_to_paid:'); ?></h4>
                    </td>
                    <td class="bg-white py-3" colspan="2">
                        <h4><?= number_format($totalPrice) . ' ' . $this->currency; ?></h4>
                    </td>
                </tr>
                </tfoot>
            </table>
            <div class="text-center pb-5">
                <input type="submit" name="submit" class="btn btn-lg btn-success"
                       value="<?= lang('main.buy_now_for:') . ' ' . number_format($totalPrice) . ' ' . $this->currency; ?>"/>
            </div>
        </div>
    </form>
</div>
<?php end: ?>
