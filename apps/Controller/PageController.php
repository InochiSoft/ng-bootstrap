<?php
class PageController extends NG\Controller {
    protected $config;
    protected $cache;
    protected $session;
    protected $cookie;
    protected $database;
    protected $kontak;
    
    public function init() {
        $this->config = $this->view->config = \NG\Registry::get('config');
        $this->session = $this->view->session = new \NG\Session();
        $this->cookie = $this->view->cookie = new \NG\Cookie();
        $this->cache = $this->view->cache = new \NG\Cache();
        $this->database = $this->view->database = \NG\Registry::get('database');
        $this->kontak = $this->view->kontak = new Kontak();
    }
    
    public function IndexAction() {
        $config = $this->config;
        $database = $this->database;
        $kontak = $this->kontak;
        
        /*
        $requests mengambil URL yang dikirimkan peramban, misal:
        - http://localhost/ng-bootstrap/halaman/tentang
        maka $requests akan menghasilan array dengan nilai:
        param1 => tentang.
        */
        $requests = \NG\Route::getRequests();
        
        $viewRoute = $config['ROUTE_PATH_1'];
        $siteName = $config['SITE_NAME'];
        
        $viewType = '';
        $viewTitle = '';
        $viewContent = '';
        $viewKeywords = '';
        $viewDescription = '';
        
        $param1 = '';
        
        if ($requests){
            if (isset($requests['param1'])){
                $param1 = $requests['param1'];
                if ($param1){
                    switch($param1){
                        case 'tentang':
                            $viewTitle = 'Tentang';
                            $viewContent = "<p><strong>$siteName</strong> adalah situs percobaan NG Framework dengan Bootstrap. Terima kasih sudah mengunjungi <strong>$siteName</strong>.</p>";
                            
                            $viewKeywords = 'Tentang, NG Framework, Bootstrap';
                            $viewDescription = $viewContent;
                        break;
                        case 'kontak':
                            $viewTitle = 'Kontak';
                            $viewKeywords = 'Kontak';
                            $viewDescription = "Halaman kontak $siteName";
                            
                            /* Bila ada data dikirimkan via form */
                            if (isset($_POST)){
                                /* Bila data dikirimkan dari tombol send [Kirim] */
                                if (isset($_POST["send"])){
                                    $fullname = isset($_POST["fullname"]) ? $_POST["fullname"] : '';
                                    $email = isset($_POST["email"]) ? $_POST["email"] : '';
                                    $message = isset($_POST["message"]) ? $_POST["message"] : '';
                                    
                                    if ($fullname && $email && $message){
                                        $dataInsert = array(
                                            'nama' => $fullname,
                                            'email' => $email,
                                            'pesan' => $message
                                        );
                                        
                                        $insert = $kontak->insertKontak($dataInsert);
                                    }
                                } else {
                                    foreach ($_POST as $key => $value){
                                        /* Bila pada data terdapat kunci 'delete' */
                                        if (substr($key, 0, 6) == 'delete'){
                                            $arrKey = explode('-', $key);
                                            $messageId = 0;
                                            
                                            if (count($arrKey) == 2){
                                                $messageId = $arrKey[1];
                                                $delete = $kontak->deleteKontak($messageId);
                                            }
                                            
                                            break;
                                        }
                                    }
                                }
                            }
                            
                            /* Mengirimkan data kontak */
                            $viewData = $kontak->fetchKontak();
                            
                            /* Mengirimkan data kontak ke View via variabel viewData */
                            $this->view->viewData = $viewData;
                        break;
                    }
                }
            }
        }
        
        $viewType = $param1;
        $viewDescription = htmlentities(strip_tags($viewDescription));
        
        $this->view->viewRoute = $viewRoute;
        $this->view->viewType = $viewType;
        $this->view->viewTitle = $viewTitle;
        $this->view->viewContent = $viewContent;
        $this->view->viewKeywords = $viewKeywords;
        $this->view->viewDescription = $viewDescription;
        $this->view->viewImage = '';
    }
}
?>
