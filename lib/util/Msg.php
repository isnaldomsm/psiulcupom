<?php

class Msg {

    /**
     * Mostra uma mensagem na próxima renderização de uma view
     * 
     * @param String $msg
     * @param int $tipo {1,2,3,4}  1=Success, 2=Warning, 3=Error, 4=info
     */
    function __construct($msg, $tipo = 1) {
        switch ($tipo) {
            case 1:
                $_SESSION['frameworkMsg' . APPKEY][] = $this->getTemplate('alert-success', 'fa-check-circle-o', $msg);
                break;
            case 2:
                $_SESSION['frameworkMsg' . APPKEY][] = $this->getTemplate('alert-warning', 'fa-exclamation-circle', $msg);
                break;
            case 3:
                $_SESSION['frameworkMsg' . APPKEY][] = $this->getTemplate('alert-danger', 'fa-times-circle', $msg);
                break;
            case 4:
                $_SESSION['frameworkMsg' . APPKEY][] = $this->getTemplate('alert-info', 'fa-info-circle', $msg);
                break;
            default:
                $_SESSION['frameworkMsg' . APPKEY][] = $this->getTemplate('alert-success', '<i class="fa fa-check-circle-o"></i>', $msg);
        }
    }

    private function getTemplate($class, $icon, $msg) {
        $template = '<div class="alert ' . $class . '" role="alert" style="font-size:1.2em">';
        $template .= '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
        $template .= '<div class="col-xs-1"><i class="fa ' . $icon . '"></i></div> ';
        $template .= '<div class="col-xs-10">'.$msg.'</div>';
        $template .= '<div class="clearfix"></div>';
        $template .= '</div>';
        return $template;
    }

    /**
     * Busca as mensagens do sistema.<br>
     * <b>deve ser utilizado apenas no template.</b><br>
     * 
     * Exemplo: <?php echo $this->getMsg();?>
     * 
     * 
     * @return String mensagem
     */
    public static function getMsg() {
        if (isset($_SESSION['frameworkMsg' . APPKEY])) {
            $msgarr = $_SESSION['frameworkMsg' . APPKEY];
            unset($_SESSION['frameworkMsg' . APPKEY]);
            $msg = '';
            foreach ($msgarr as $value) {
                $msg .= $value;
            }
            return $msg;
        }
        return null;
    }

}
