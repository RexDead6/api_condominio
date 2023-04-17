<?php
use Dompdf\Dompdf;

class PDFManager {
    private $template;
    public function template($template_name, $data = []){
        ob_start();
        require_once str_replace("\\", "/", dirname( __DIR__ )) . "/assets/template/$template_name";
        $this->template = ob_get_clean();
        foreach ($data as $key => $value) {
            if (gettype($value) == 'array') {
                $rows = "";
                foreach ($value as $row) {
                    $rows .= "<tr>";
                    foreach ($row as $column) {
                        $rows .= "<td>$column</td>";
                    }
                    $rows .= "</tr>";
                }
                $this->template = str_replace("{{".$key."}}", $rows, $this->template);
            } else {
                $this->template = str_replace("{{".$key."}}", $value, $this->template);
            }
        }
    }

    public function output($name){
        try{
            $dompdf = new Dompdf();
            $dompdf->loadHtml($this->template);
            $dompdf->render();
            $contenido = $dompdf->output();
            file_put_contents(str_replace("\\", "/", dirname( __DIR__ )) . "/assets/pdf/$name", $contenido);
            return true;
        } catch(Exception $err){
            echo $err;
            return false;
        }
    }
}
?>