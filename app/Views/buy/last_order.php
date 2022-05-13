<div class="pt-5 mt-5">
    <div style="height:230px">
        <div class="text-center">
            <i class="bi bi-cart-check text-success p-0 m-0 lh-sm" style="font-size:100px"></i>
            <h5 class="text-success" style="font-size:40px"><?=lang('main.payment_success');?></h5>
            <h4 class="py-2"><?=lang('main.your_transaction_code:');?> <span class="text-primary"><?=$_SESSION['last_order']['trans_id'];?></span></h4>
        </div>
    </div>
</div>
<div class="pt-3 py-5 mt-5">
    <div class="table-responsive container">
        <table class="bg-white table table-bordered">
            <thead>
                <th></th>
                <th><?=lang('main.title');?></th>
                <th><?=lang('main.amount');?></th>
                <th><?=lang('main.download');?></th>
            </thead>
            <tbody>
            <?php
                $i = 1;
                foreach($data as $item) {
                    echo '<tr>
                            <td>'.$i++.'</td>
                            <td>'.switch_key($item, 'title', $this->ls).'</td>
                            <td>'.number_format($item['final_price']).' '.$this->currency.'</td>
                            <td><a class="btn btn-success" href="'.base_url('file/di/'.$item['code']).'">'.lang('main.download').'</a></td>
                        </tr>';
                }
            ?>
            </tbody>
            <tfoot>

            </tfoot>
        </table>
    </div>
</div>
