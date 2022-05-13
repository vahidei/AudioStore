<?php namespace App\Controllers;

use App\Models\Main_model;
use ZipArchive;

class Home extends BaseController
{
    public function index()
    {
        $data = [];
        $main_model = new Main_model();
        $data['packages'] = $main_model->get_packages();
        $data['categories'] = $main_model->get_categories();


        echo view('includes/header');
        echo view('homepage', ['data' => $data, 'scripts' => [base_url('public/js/homepage.js')]]);
        echo view('includes/footer');
    }

    public function di($code)
    {

        $main_model = new Main_model();
        if (empty($code)) {
            show_404();
        }

        $item = $main_model->get_dl_file_code($code);

        if ($item['type'] == 'track') {
            $original = $main_model->get_original_track($item['item_id']);
            $file_address = 'originals/tracks/' . $original['coded_name'];
            $ip = getIp();
            $expire = date('Y-m-d H:i:s', time() + (60 * 60 * 24 * 2));
            $code = generate_code();
            if ($main_model->create_temp_download($item['item_id'], 'track', $code, $original['file_name'], $file_address, $ip, $expire)) {
                return redirect()->to(base_url('file/download/' . $code));
            }
        }

        if ($item['type'] == 'package') {
            $items = $main_model->get_packages_tracks($item['item_id']);
            $fname = str_ireplace(' ', '_', $items[0]['title']) . '.zip';
            $zipname = FCPATH . '/originals/packages/' . $fname;
            $file_address = 'originals/packages/' . $fname;
            $zip = new ZipArchive;
            $res = $zip->open($zipname, ZipArchive::CREATE | ZIPARCHIVE::OVERWRITE);
            if ($res === TRUE) {
                foreach ($items as $titem) {
                    $file = 'originals/tracks/' . $titem['coded_name'];
                    $zip->addFile($file, pathinfo($file, PATHINFO_BASENAME));
                    $zip->renameName($titem['coded_name'], $titem['file_name']);
                }
                $zip->close();
                if (file_exists($zipname)) {
                    $ip = getIp();
                    $expire = date('Y-m-d H:i:s', time() + (60 * 60 * 24 * 2));
                    $code = generate_code();
                    if ($main_model->create_temp_download($item['item_id'], 'package', $code, $fname, $file_address, $ip, $expire)) {
                        return redirect()->to(base_url('file/download/' . $code));
                    }
                }
            } else {
                var_dump($res);
            }


        }
    }

    public function download($code)
    {

        $main_model = new Main_model();
        if (empty($code)) {
            show_404();
        }

        $item = $main_model->get_temp_download($code);
        if (empty($item)) {
            show_404();
        }

        $filename = $item['file_address'];

        $user = isset($_SESSION['user']) ? $_SESSION['user']['id'] : 0;
        $main_model->add_to_downloaded($item['item_id'], $item['type'], 0, $user, getIp());

        if (file_exists($filename)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($item['file_name']) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filename));
            readfile($filename);
            exit;
        }

    }

    public function free_download($type, $id)
    {
        $main_model = new Main_model();
        $type = strtolower($type);
        if (empty($id) || !is_numeric($id) || $id < 1 || !in_array($type, ['track', 'package'])) {
            show_404();
        }

        $item = $main_model->get_free_download($type, $id);

        if (empty($item)) {
            show_404();
        }

        $price = $item['price'];
        if (!empty($item['discount'])) {
            $price = calcDiscount($item['price'], $item['discount'], $item['discount_type']);
        }

        if ($price > 0) {
            show_404();
        }

        $user = isset($_SESSION['user']) ? $_SESSION['user']['id'] : 0;
        $main_model->add_to_downloaded($item['id'], $type, 1, $user, getIp());

        if ($type == 'track') {
            $filename = 'originals/tracks/' . $item['coded_name'];
        } else {
            $items = $main_model->get_packages_tracks($id);

            $fname = str_ireplace(' ', '_', $items[0]['title']) . '.zip';
            $zipname = FCPATH . '/originals/packages/' . $fname;
            $zip = new ZipArchive;
            $res = $zip->open($zipname, ZipArchive::CREATE | ZIPARCHIVE::OVERWRITE);
            if ($res === TRUE) {
                foreach ($items as $titem) {
                    $file = 'originals/tracks/' . $titem['coded_name'];
                    $zip->addFile($file, pathinfo($file, PATHINFO_BASENAME));
                    $zip->renameName($titem['coded_name'], $titem['file_name']);
                }
                $zip->close();
                $filename = $zipname;
                $item['file_name'] = $zipname;
                $item['file_size'] = filesize($filename);
            }
        }

            if (file_exists($filename)) {
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="' . basename($item['file_name']) . '"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . $item['file_size']);
                readfile($filename);
                exit;
            }else{
                show_404();
            }
        }
        //--------------------------------------------------------------------

    }
