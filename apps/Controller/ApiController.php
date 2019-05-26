<?php
class ApiController extends NG\Controller {
    protected $config;
    protected $cache;
    protected $session;
    protected $cookie;
    
    public function init() {
        $this->config = $this->view->config = \NG\Registry::get('config');
        $this->session = $this->view->session = new \NG\Session();
        $this->cookie = $this->view->cookie = new \NG\Cookie();
        $this->cache = $this->view->cache = new \NG\Cache();
        
        $this->view->setLayout(false);
        $this->view->setNoRender(true);
    }
    
    public function IndexAction() {
        $result = null;
        $requests = \NG\Route::getRequests();
        
        $session = $this->session;
        $cookie = $this->cookie;
        $cache = $this->cache;
        
        $param1 = '';
        $param2 = '';
        $param3 = '';
        
        if ($requests){
            if (isset($requests['param1'])){
                $param1 = $requests['param1'];
            }
            if (isset($requests['param2'])){
                $param2 = $requests['param2'];
            }
            if (isset($requests['param3'])){
                $param3 = $requests['param3'];
            }
            
            $kontak = new Kontak();
            
            if ($param1){
                switch($param1){
                    /* Bila param1 adalah "kontak" 
                    contoh: http://localhost/ng-bootstrap/api/kontak
                    */
                    case 'kontak':
                        /* Bila ada param2 
                        contoh: http://localhost/ng-bootstrap/api/kontak/1
                        */
                        if ($param2){
                            $result = $kontak->getKontak($param2);
                        } else {
                            $result = $kontak->fetchKontak();
                        }
                    break;
                }
            }
        }
        
        if ($result){
            $print_text = json_encode($result);
            header('Content-type: application/json');
            exit($print_text);
        }
    }
}
?>
