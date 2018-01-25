<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Description of XML
 *
 * @author Thiago Moura
 */
class Xml extends MY_Controller{
    public function __construct() {
        parent::__construct('sistema/ferramenta/xml','extracao_ncm');
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

        if(!file_exists($config['upload_path'])){
            mkdir($config['upload_path'],777,TRUE);
        }
        
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

    public function mescla_csv(){
        $data = [
            'titulo' => 'Mesclar CSV com base de dados'
        ];
        $this->twig->display('sistema/ferramenta/xml/mescla_csv', $data);
    }
    
    public function upload_csv(){
        $config['upload_path']          = './file/upload/xml/csv';
        $config['allowed_types']        = 'csv';
        $config['max_size']             = 1000;
        $config['overwrite']            = TRUE;
        
        if(!file_exists($config['upload_path'])){
            mkdir($config['upload_path'],777,TRUE);
        }
        $this->load->library('upload', $config);
        
        if ( ! $this->upload->do_upload('uploadcsv'))
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
                $this->twig->display('sistema/ferramenta/xml/mescla_csv', $data);
            }
        }else{
            $file_data = $this->upload->data();
            $lines = file($file_data["full_path"]);
            $this->load->model('prod_ncm_model');
            $this->prod_ncm_model->selecionar();
            $registros = $this->prod_ncm_model->registros();
            $novo_csv = "COD;NCM";
            $num = 0;
            $path_csv = "file/download/csv";
            $nome_csv = "tabela_ncm.csv";
            
            foreach($lines as $line){
                $campos = explode(";", rtrim($line,"\r\n"));
                foreach($registros as $reg){
                    if($reg['cod']==$campos[0] or $reg['cod']==$campos[1]){
                        $novo_csv .= "\r\n" . $campos[0] .";" . $reg['ncm'];
                        break;
                    }
                }
            }
            
            if(!file_exists("./{$path_csv}")){
                mkdir("./{$path_csv}",777,TRUE);
            }
            
            while(file_exists("./{$path_csv}/{$nome_csv}")){
                $num++;
                $nome_csv = "tabela_ncm{$num}.csv";
            }
            
            if(file_put_contents("./{$path_csv}/{$nome_csv}",$novo_csv)!=FALSE){
                $retorno = array(
                    'success' => "Upload realizado com sucesso!",
                    'url' => base_url("{$path_csv}/{$nome_csv}")
                );
                if($this->input->is_ajax_request()){
                $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($retorno));
                }else{
                    $data = [
                        'titulo' => 'Mesclar CSV com base de dados',
                        'upload' => $retorno
                    ];
                    $this->twig->display('sistema/ferramenta/xml/mescla_csv', $data);
                }
            }
        }
    }
    
    public function gera_csv(){
        $this->load->model('prod_ncm_model');
        $this->prod_ncm_model->selecionar();
        $registros = $this->prod_ncm_model->registros();
        $path_csv = "file/download/csv";
        $nome_csv = "tabela_ncm.csv";
        $novo_csv = "PRODUTO;NCM";
        $num = 0;

        foreach($registros as $reg){
            $novo_csv .= "\r\n" . $reg['cod'] .";" . $reg['ncm'];
        }
        if(!file_exists("./{$path_csv}")){
            mkdir("./{$path_csv}",777,TRUE);
        }

        while(file_exists("./{$path_csv}/{$nome_csv}")){
            $num++;
            $nome_csv = "tabela_ncm{$num}.csv";
        }

        if(file_put_contents("./{$path_csv}/{$nome_csv}",$novo_csv)!=FALSE){
            $retorno = array(
                'success' => "Arquivo gerado com sucesso!",
                'url' => base_url("{$path_csv}/{$nome_csv}")
            );
            if($this->input->is_ajax_request()){
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($retorno));
            }else{
                $data = [
                    'titulo' => 'Gera CSV da base de dados',
                    'upload' => $retorno
                ];
                $this->twig->display('sistema/ferramenta/xml/mescla_csv', $data);
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
