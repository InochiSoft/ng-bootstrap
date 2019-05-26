<?php
class DownloadController extends NG\Controller {
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
        
        if ($requests){
            if (isset($requests['param1'])){
                $param1 = $requests['param1'];
            }
            $kontak = new Kontak();
            if ($param1){
                switch($param1){
                    /* Bila param1 adalah "kontak" 
                    contoh: http://localhost/ng-bootstrap/unduh/kontak
                    */
                    case 'kontak':
                        $pdf = new Pdf('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
                        $pdf->SetAuthor('Agung Novian');
                        $pdf->SetTitle('Informasi Kontak');
                        $pdf->SetSubject('Informasi Kontak');
                        
                        $pdf->setPrintHeader(false);
                        $pdf->setPrintFooter(false);
                        $pdf->setFontSubsetting(true);
                        $pdf->AddPage();
                        $pdf->SetFont('helvetica', '', 10, '', true);
                        
                        $content = '<h1 style="text-align: center">Informasi Kontak</h1>';
                        $content .= '<br />';
                        $content .= '<table width="100%" style="border-collapse: collapse;" border="1" cellpadding="4">';
                        $content .= '<tr>';
                        $content .= '<td align="center" width="40" style="font-weight: bold;background-color: rgb(200, 200, 200)">No</td>';
                        $content .= '<td align="center" width="40" style="font-weight: bold;background-color: rgb(200, 200, 200)">ID</td>';
                        $content .= '<td align="center" width="120" style="font-weight: bold;background-color: rgb(200, 200, 200)">Nama Lengkap</td>';
                        $content .= '<td align="center" width="150" style="font-weight: bold;background-color: rgb(200, 200, 200)">Email</td>';
                        $content .= '<td align="center" width="180" style="font-weight: bold;background-color: rgb(200, 200, 200)">Pesan</td>';
                        $content .= '</tr>';
                        
                        $data = $kontak->fetchKontak();
                        $no = 1;
                        
                        foreach ($data as $item){
                            $content .= '<tr>';
                            $content .= '<td align="right">' . $no . '</td>';
                            $content .= '<td align="right">' . $item['id'] . '</td>';
                            $content .= '<td>' . $item['nama'] . '</td>';
                            $content .= '<td>' . $item['email'] . '</td>';
                            $content .= '<td>' . $item['pesan'] . '</td>';
                            $content .= '</tr>';
                            
                            $no++;
                        }
                        
                        $content .= '</table>';
                        
                        $pdf->writeHTML($content, true, false, true, false, '');
                        $pdf->Output('Informasi Kontak.pdf', 'I');
                    break;
                }
            }
        } else {
            $this->view->setLayout(true);
            $this->view->setNoRender(false);
        }
    }
}
?>
