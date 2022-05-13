<div class="bg-light p-2" >
    <div style="height:300px">
        <div class="text-center pt-5">
            <i class="bi bi-inbox text-info p-0 m-0 lh-sm" style="font-size:100px"></i>
            <h5 class="text-info lbl" style="font-size:40px"><?=lang('main.purchases');?></h5>
            <h6 class="py-2"><?=lang('main.total').': '.$data['purchases_count'].' '.lang('main.purchase(s)');?></h6>
        </div>
    </div>
</div>
<div class="light-box-shadow bg-white">

    <div class="container-xxl px-lg-5 px-md-3 purchases my-5 pb-5 pt-3">
        <div class="pt-3 py-5 mt-5">

            <?php if(empty($data['items'])){
                echo '<div class="bg-light text-center py-4 h3 mt-4 text-danger"><i class="bi bi-inbox display-2"></i><br>'.lang('main.not_found').'</div>';
            }else { ?>

            <div class="table-responsive container">
                <table class="bg-white table table-bordered">
                    <thead>
                    <th></th>
                    <th><?=lang('main.title');?></th>
                    <th><?=lang('main.amount');?></th>
                    <th><?=lang('main.purchase_date');?></th>
                    <th><?=lang('main.download');?></th>
                    </thead>
                    <tbody>
                    <?php
                    $i = 1;
                    foreach($data['items'] as $item) {
                        if(!empty($item['track_title'])){
                            $type = 'track';
                        }else{
                            $type = 'package';
                        }
                        echo '<tr>
                            <td>'.$i++.'</td>
                            <td>'.switch_key($item, $type.'_title', $this->ls).'</td>
                            <td>'.number_format($item['final_price']).' '.$this->currency.'</td>
                            <td>'.$item['buy_date'].'</td>
                            <td><a class="btn btn-success" href="'.base_url('file/di/'.$item['code']).'">'.lang('main.download').'</a></td>
                        </tr>';
                    }
                    ?>
                    </tbody>
                    <tfoot>

                    </tfoot>
                </table>
            </div>

            <?php if(!empty($data['items'])){
                $links = 5;

                $last       = ceil( $data['purchases_count'] / $data['limit'] );

                $start      = ( ( $data['page'] - $links ) > 0 ) ? $data['page'] - $links : 1;
                $end        = ( ( $data['page'] + $links ) < $last ) ? $data['page'] + $links : $last;

                $html       = '<ul class="pagination pt-5 justify-content-center">';

                $class      = ( $data['page'] == 1 ) ? "disabled" : "";
                $class2      = ( $data['page'] == 1 ) ? "" : "text-info";
                $html       .= '<li class="page-item ' . $class . '"><a class="page-link '.$class2.'" href="' . ( $data['page'] - 1 ) . '">&laquo;</a></li>';

                if ( $start > 1 ) {
                    $html   .= '<li class="page-item"><a class="page-link" href="1">1</a></li>';
                    $html   .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
                }

                for ( $i = $start ; $i <= $end; $i++ ) {
                    $class  = ( $data['page'] == $i ) ? "active" : "";
                    $class2  = ( $data['page'] == $i ) ? "bg-info border-info" : "text-info";
                    $html   .= '<li class="page-item ' . $class . '"><a class="page-link '.$class2.'" href="' . $i . '">' . $i . '</a></li>';
                }

                if ( $end < $last ) {
                    $html   .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
                    $html   .= '<li class="page-item"><a class="page-link" href="' . $last . '">' . $last . '</a></li>';
                }

                $class      = ( $data['page'] == $last ) ? "disabled" : "";
                $class2 = ( $data['page'] == $last ) ? "" : "text-info";
                $html       .= '<li class="page-item ' . $class . '"><a class="page-link '.$class2.'" href="' . ( $data['page'] + 1 ) . '">&raquo;</a></li>';

                $html       .= '</ul>';


                ?>
                <div>
                    <nav aria-label="Page navigation example" >
                        <ul class="pagination justify-content-center">
                            <?=$html;?>
                        </ul>
                    </nav>
                </div>
            <?php } ?>

            <?php } ?>
        </div>
    </div>
</div>

