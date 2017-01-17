<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Description of XML
 *
 * @author Thiago Moura
 */
class Xml extends MY_Controller{
    public function __construct() {
        parent::__construct('sistema/ferramenta/xml',TRUE);
    }
	
    public function extracao_ncm(){
        $data = [
            'titulo' => 'Extração de NCM das NF-e'
        ];
        $this->twig->display('sistema/ferramenta/xml/extracao_ncm', $data);
    }
    
    public function upload(){
        $config['upload_path']          = './file/upload/xml/nfe';
        $config['allowed_types']        = 'xml';
        $config['max_size']             = 1000;
        $config['overwrite']            = TRUE;

        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload('uploadxml'))
        {
            $upload = array('error' => $this->upload->display_errors('',''));

            if($this->input->is_ajax_request()){
                $this->output
                    ->set_status_header(401)
                    ->set_content_type('application/json')
                    ->set_output(json_encode($upload));
            }else{
                $data = [
                    'titulo' => 'Falha no upload',
                    'upload' => $upload
                ];
                $this->twig->display('sistema/ferramenta/xml/extracao_ncm', $data);
            }
        }
        else
        {
            $this->load->model('prod_ncm_model');
            $inseridos = 0;
            $count_prod = 0;
            
            $file_data = $this->upload->data();
            $xmlDOM = $this->loadXML($file_data['full_path']);
            $produtos = $xmlDOM->getElementsByTagName('prod');
            foreach($produtos as $produto){
                $cod = $produto->getElementsByTagName('cProd')->item(0)->nodeValue;
                $ncm = $produto->getElementsByTagName('NCM')->item(0)->nodeValue;
                if($this->prod_ncm_model->has_recorded($cod,$ncm)===FALSE){
                    if($this->prod_ncm_model->inserir(array(
                        'cod' => $cod,
                        'descricao' => $produto->getElementsByTagName('xProd')->item(0)->nodeValue,
                        'ncm' => $ncm
                    ))){
                        $inseridos += 1;
                    }
                }
                $count_prod += 1;
            }
            $upload = array(
                'xml' => $file_data,
                'success' => 'Upload realizado com sucesso!',
                'inserido' => $inseridos,
                'extraido' => $count_prod
            );

            if($this->input->is_ajax_request()){
                $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($upload));
            }else{
                $data = [
                    'titulo' => 'Extração de NCM das NF-e',
                    'upload' => $upload
                ];
                $this->twig->display('sistema/ferramenta/xml/extracao_ncm', $data);
            }
        }
    }

    private function loadXML($nome){
        $document = new DOMDocument( '1.0', 'UTF-8' );
        $document->preserveWhiteSpace = false;
        $document->load( $nome );
        return $document;
    }
}
