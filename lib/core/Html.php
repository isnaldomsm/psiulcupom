<?php

/**
 * classe Html
 * 
 * @author Miguel
 * @package \lib\core
 */
class Html {
    /*
     * Mesma coisa que o getLink, com a diferença que circunda a tag <a> com a tag <li>
     * Usada principalmente para o menu.
     * 
     * @author  Healfull 
     * @param String $name
     * @param String $controller
     * @param String $action
     * @param array $urlParams Array associativo para especificar as variáveis opcionais enviadas via get
     * @param array $linkParams Array associativo para especificar os atributos HTML adicionais da tag <a>
     * @return string
     */

    public function getMenuLink($name, $controller, $action, Array $urlParams = NULL, Array $linkParams = NULL) {
        if (!Acl::check($controller, $action, Session::get(Acl::$loggedSession))) {
            return '';
        }
        return '<li>' . $this->getLink($name, $controller, $action, $urlParams, $linkParams) . '</li>';
    }

    /**
     * Constrói um link (ancora) padrão lazyphp.
     * 
     * <b>Exemplo de uso 1:</b><br>
     * <?php echo $this->Html->getLink('Listar usuarios', 'Usuario', 'all');?>
     * 
     * <b>Retorna: </b><br>
     * &lt;a href=&quot;/Usuario/all&quot;&gt;Listar usuarios&lt;/a&gt;<br>
     * 
     * <b>Exemplo de uso 2:</b><br>
     * <?php echo $this->Html->getLink('Ver usuario', 'Usuario', 'all', array('id' => 1));?>
     * 
     * <b>Retorna: </b><br>
     * &lt;a href=&quot;/Usuario/all/?id=1&quot;&gt;Ver Usuario&lt;/a&gt;<br>
     * 
     * @param String $name
     * @param String $controller
     * @param String $action
     * @param array $urlParams Array associativo para especificar as variáveis opcionais enviadas via get
     * @param array $linkParams Array associativo para especificar os atributos HTML adicionais da tag <a>
     * @return string
     */
    public function getLink($name, $controller, $action, Array $urlParams = NULL, Array $linkParams = NULL) {
        if (!Acl::check($controller, $action, Session::get(Acl::$loggedSession))) {
            return '';
        }
        $anchor = '';
        $link = '<a href="';
        $url = SITE_PATH . '/?m=' . $controller . '&p=' . $action;
        if (Config::get('rewriteURL')) {
            $url = SITE_PATH . '/' . $controller . '/' . $action . '/';
        }
        $link .=$url;
        if (is_array($urlParams)) {
            $carr = (Config::get('criptedGetParamns'));
            if (is_array($carr)) {
                foreach ($carr as $param) {
                    foreach ($urlParams as $key => $value) {
                        if ($param === $key) {
                            $urlParams[$key] = Cript::cript($value);
                        }
                    }
                }
            }
            if (Config::get('rewriteURL')) { # com urls amigaveis
                foreach ($urlParams as $key => $value) {
                    if ($key === '#') {
                        $anchor = '#' . $value;
                    } elseif (is_int($key)) {
                        $link .= $value . '/';
                    } else {
                        $link .= $key . ':' . $value . '/';
                    }
                    unset($urlParams[$key]);
                }
            } else { # sem urls amigaveis
                $link .= '?';
                foreach ($urlParams as $key => $value) {
                    if ($key === '#') {
                        $anchor = '#' . $value;
                    }
                    unset($urlParams[$key]);
                    break;
                }
                $params = '&' . http_build_query($urlParams);
                $link .= $params;
            }
        }
        $link .= $anchor . '"';
        if (is_array($linkParams)) {
            foreach ($linkParams as $key => $value) {
                $link .= ' ' . $key . '="' . $value . '"';
            }
        }
        $link = str_replace('//', '/', $link);
        $link .= '>' . $name . '</a>';
        return $link;
    }

    /**
     * Constrói um link (ancora) padrão lazyphp aberto em MODAL.
     * 
     * <b>Exemplo de uso 1:</b><br>
     * <?php echo $this->Html->getModalLink('Listar usuarios', 'Usuario', 'all');?>
     * 
     * <b>Retorna: </b><br>
     * &lt;a href=&quot;#&quot; data-toggle=&quot;modal&quot;  data-href=&quot;/Usuario/all&quot;&gt;Listar usuarios&lt;/a&gt;<br>
     * 
     * <b>Exemplo de uso 2:</b><br>
     * <?php echo $this->Html->getLink('Ver usuario', 'Usuario', 'all', array('id' => 1));?>
     * 
     * <b>Retorna: </b><br>
     * &lt;a href=&quot;#&quot; data-toggle=&quot;modal&quot;  data-href=&quot;/Usuario/all/?id=1&quot;&gt;Ver Usuario&lt;/a&gt;<br>
     * 
     * @param String $name
     * @param String $controller
     * @param String $action
     * @param array $urlParams Array associativo para especificar as variáveis opcionais enviadas via get
     * @param array $linkParams Array associativo para especificar os atributos HTML adicionais da tag <a>
     * @return string
     */
    public function getModalLink($name, $controller, $action, Array $urlParams = array(), Array $linkParams = NULL) {
        if (!Acl::check($controller, $action, Session::get(Acl::$loggedSession))) {
            return '';
        }
        $anchor = '';
        $link = '<a data-href="#' . $action . '-' . implode(':', $urlParams) . '" data-target="#modal" data-toggle="modal" href="';
        if (Config::get('rewriteURL')) {
            $url = SITE_PATH . '/' . $controller . '/' . $action . '/';
        }
        $link .=$url;
        if (is_array($urlParams)) {
            $carr = (Config::get('criptedGetParamns'));
            if (is_array($carr)) {
                foreach ($carr as $param) {
                    foreach ($urlParams as $key => $value) {
                        if ($param === $key) {
                            $urlParams[$key] = Cript::cript($value);
                        }
                    }
                }
            }

            foreach ($urlParams as $key => $value) {
                if ($key === '#') {
                    $anchor = '#' . $value;
                } elseif (is_int($key)) {
                    $link .= $value . '/';
                } else {
                    $link .= $key . ':' . $value . '/';
                }
                unset($urlParams[$key]);
            }
        }
        $link .= $anchor . '/template:off"';
        if (is_array($linkParams)) {
            foreach ($linkParams as $key => $value) {
                $link .= ' ' . $key . '="' . $value . '"';
            }
        }
        $link .= '>' . $name . '</a>';
        return str_replace('//', '/', $link);
    }

    /**
     * Constrói uma URL no padrão lazyphp.
     * 
     * <b>Exemplo de uso 1:</b><br>
     * <?php echo $this->Html->getUrl('Usuario', 'all');?>
     * 
     * <b>Retorna se rewriteURL estiver definido: </b><br>
     * /Usuario/all<br>
     * 
     * <b>Retorna se rewriteURL NÃO estiver definido: </b><br>
     * index.php?m=Usuario&p=all<br>
     * 
     * <b>Exemplo de uso 2:</b><br>
     * <?php echo $this->Html->getUrl('Usuario', 'all', array('id' => 1));?>
     * 
     * <b>Retorna se rewriteURL estiver definido: </b><br>
     * /Usuario/all/?id=1<br>
     * 
     * <b>Retorna se rewriteURL NÃO estiver definido: </b><br>
     * index.php?m=Usuario&p=all&id=1<br>
     * 
     * @param String $controller
     * @param String $action
     * @param array $urlParams
     * @return string
     */
    public function getUrl($controller, $action, Array $urlParams = NULL) {
        if (!Acl::check($controller, $action, Session::get(Acl::$loggedSession))) {
            return '';
        }
        $anchor = '';
        $link = '';
        $url = SITE_PATH . '/?m=' . $controller . '&p=' . $action;
        if (Config::get('rewriteURL')) {
            $url = SITE_PATH . '/' . $controller . '/' . $action . '/';
        }
        $link .=$url;
        if (is_array($urlParams)) {
            $carr = (Config::get('criptedGetParamns'));
            if (is_array($carr)) {
                foreach ($carr as $param) {
                    foreach ($urlParams as $key => $value) {
                        if ($param === $key) {
                            $urlParams[$key] = Cript::cript($value);
                        }
                    }
                }
            }
            if (Config::get('rewriteURL')) { # com urls amigaveis
                foreach ($urlParams as $key => $value) {
                    if ($key === '#') {
                        $anchor = '#' . $value;
                    } elseif (is_int($key)) {
                        $link .= $value . '/';
                    } else {
                        $link .= $key . ':' . $value . '/';
                    }
                    unset($urlParams[$key]);
                }
            } else { # sem urls amigaveis
                $link .= '?';
                foreach ($urlParams as $key => $value) {
                    if ($key === '#') {
                        $anchor = '#' . $value;
                    }
                    unset($urlParams[$key]);
                    break;
                }
                $params = '&' . http_build_query($urlParams);
                $link .= $params;
            }
        }
        $link .= $anchor;
        return str_replace('//', '/', $link);
    }

    /**
     * Retorna um form-group padrão Bootstrao contendo um campo de formulário input
     *  
     * @param String $label
     * @param String $name
     * @param String $value
     * @param String $type [opcional]
     * @param String $placeholder [opcional]
     * @param boolean $required [opcional]
     * @return string
     */
    public function getFormInput($label, $name, $value, $type = 'text', $placeholder = '', $required = true) {
        $field = '<div class="form-group">';
        $field .= '<label class="col-sm-3 control-label" for="' . $name . '">' . $label;
        $r = '';
        if ($required) {
            $field .= ' <small><i class="fa fa-asterisk"></i></small>';
            $r = 'required';
        }
        $field .= '</label>';
        $field .= '<div class="col-sm-9">';
        $step = $type == 'decimal' ? ' step="0.01"' : '';
        $field .= '<input type="' . $type . '" ' . $step . ' name="' . $name . '" id="' . $name . '" class="form-control" value="' . $value . '" placeholder="' . $placeholder . '" ' . $r . '>';
        $field .= '</div>';
        $field .= '<div class="clearfix"></div>';
        $field .= '</div>';
        return $field;
    }

    /**
     * Retorna um form-group padrão Bootstrao contendo um campo de formulário textarea
     *  
     * @param String $label
     * @param String $name
     * @param String $value
     * @param String $placeholder [opcional]
     * @param boolean $required [opcional]
     * @return string
     */
    public function getFormTextarea($label, $name, $value, $placeholder = '', $required = true) {
        $field = '<div class="form-group">';
        $r = '';
        if ($label) {
            $field .= '<label class="col-sm-3 control-label" for="' . $name . '">' . $label;
            if ($required) {
                $field .= ' <small><i class="fa fa-asterisk"></i></small>';
                $r = 'required';
            }
            $field .= '</label>';
            $field .= '<div class="col-sm-9">';
        } else {
            $field .= '<div class="col-sm-12">';
        }
        $field .= '<textarea name="' . $name . '" id="' . $name . '" class="form-control" placeholder="' . $placeholder . '" ' . $r . '>' . $value . '</textarea>';
        $field .= '</div>';
        $field .= '<div class="clearfix"></div>';
        $field .= '</div>';
        return $field;
    }

    /**
     * Retorna um form-group padrão Bootstrao contendo um campo de formulário textarea 
     * com wysiwyg editor
     *  
     * @param String $label
     * @param String $name
     * @param String $value
     * @param String $placeholder [opcional]
     * @param boolean $required [opcional]
     * @return string
     */
    public function getFormTextareaHtml($label, $name, $value, $placeholder = '', $required = true) {
        $field = '<div class="form-group">';
        $r = '';
        if ($required && $label) {
            $field .= ' <small><i class="fa fa-asterisk"></i></small>';
            $r = 'required';
        }
        if ($label) {
            $field .= '<label class="col-sm-3 control-label" for="' . $name . '">' . $label;

            $field .= '</label>';
            $field .= '<div class="col-sm-9">';
        } else {
            $field .= '<div class="col-sm-12">';
        }
        $field .= '<link href="'.SITE_PATH.'/lib/frontend/summernote/summernote.css" rel="stylesheet">';
        $field .= '<script src="'.SITE_PATH.'/lib/frontend/summernote/summernote.js"></script>';
        $field .= '<script src="'.SITE_PATH.'/lib/frontend/summernote/plugin/summernote-ext-codewrapper.min.js"></script>';
        $field .= '<script src="'.SITE_PATH.'/lib/frontend/summernote/lang/summernote-pt-BR.js"></script>';
        $field .= '<textarea name="' . $name . '" id="' . $name . '" class="form-control" placeholder="' . $placeholder . '" ' . $r . '>'.htmlentities($value).'</textarea>';
        $field .= '<script>';
        $field .= '$(document).ready(function() {';
        $field .= '$(\'#' . $name . '\').summernote(';
        $field .= '{height:200,dialogsInBody: true, prettifyHtml: true, toolbar: [
                        [\'style\', [\'bold\', \'italic\', \'underline\', \'strikethrough\', \'clear\']],
                        [\'fontsize\', [\'fontsize\']],
                        [\'insert\', [ \'gxcode\']],
                        [\'color\', [\'color\']],
                        [\'tabela\', [\'table\']],
                        [\'para\', [\'ul\', \'ol\', \'paragraph\']],
                        [\'media\', [\'link\', \'picture\',\'video\']],
                        [\'misc\', [\'fullscreen\',\'codeview\']]
                    ], lang: \'pt-BR\'}';
        $field .= ');';
        $field .= '});';
        /*if($value){
            $replace = ['\'',"\n","\r"];
            $value = str_replace($replace, '', $value);
            #$field .= '$(\'#' . $name . '\').html(\''.str_replace('\'', '', $value).'\');';
            $field .= '$(\'#' . $name . '\').html(escape($(\'#' . $name . '\').summernote(\'code\', \'' .$value . '\')));';
        }*/
        $field .= '</script>';
        $field .= '</div>';
        $field .= '</div>';
        return $field;
    }

    /**
     * Retorna um form-group padrão Bootstrao contendo um campo de formulário input
     *  
     * @param String $label
     * @param String $name
     * @param String $value
     * @param String $type [opcional]
     * @param String $placeholder [opcional]
     * @param boolean $required [opcional]
     * @return string
     */
    public function getFormHidden($name, $value) {
        return '<input type="hidden" name="' . $name . '" id="' . $name . '" value="' . $value . '">';
    }

    /**
     * Retorna um form-group padrão Bootstrao contendo um campo de formulário input checkbox
     *  
     * @param String $label
     * @param String $name
     * @param String $values Array de value=>visible_value
     * @param String $checkedValues [opcional] Array de values dos campos selecionados
     * @param boolean $required [opcional]
     * @return string
     */
    public function getFormInputCheckbox($label, $name, $value, $checked = false) {
        $checkedStr = $checked ? 'checked="checked"' : '';
        $field = '<div class="checkbox col-sm-offset-3">';
        $field .= '<div class="form-group">';
        $field .= '<label>';
        $field .= '<input type="checkbox" name="' . $name . '" ' . $checkedStr . ' value="' . $value . '">';
        $field .= $label;
        $field .= '</label>';
        $field .= '</div>';
        $field .= '</div>';

        return $field;
    }

    /**
     * Retorna um form-group padrão Bootstrao contendo um campo de formulário input radio
     *  
     * @param String $label
     * @param String $name
     * @param String $values Array de value=>visible_value
     * @param String $checked [opcional] value do campo selecionado
     * @param boolean $required [opcional]
     * @return string
     */
    public function getFormInputRadio($label, $name, $values, $checkedValue = NULL, $required = true) {
        $field = '<div class="form-group">';
        $r = '';
        if ($label) {
            $field .= '<label class="control-label col-sm-3" for="' . $name . '">' . $label;
            if ($required) {
                $field .= ' <small><i class="fa fa-asterisk"></i></small>';
                $r = 'required';
            }
            $field .= '</label>';
        }
        $field .= '<div class=" col-sm-9">';
        foreach ($values as $k => $v) {
            $field .= '<div class="radio">';
            $checked = ($k == $checkedValue && is_numeric($checkedValue)) ? 'checked' : '';
            $field .= '<label>';
            $field .= '<input ' . $r . ' type="radio" name="' . $name . '" ' . $checked . ' value="' . $k . '">';
            $field .= $v;
            $field .= '</label>';
            $field .= '</div>'; #.radio
        }
        $field .= '</div>'; #.col-sm-9
        echo '<div class="clearfix"></div>';
        $field .= '</div>'; #.form-group
        return $field;
    }

    /**
     * Retorna um form-group padrão Bootstrao contendo um campo de formulário select option
     *  
     * @param String $label
     * @param String $name
     * @param String $values Array de value=>visible_value
     * @param String $checked [opcional] value do campo selecionado
     * @param boolean $nullOption insere um campo option nulo [opcional]
     * @return string
     */
    public function getFormSelect($label, $name, $values, $checkedValue = NULL, $nullOption = false) {
        $field = '<div class="form-group">';
        $field .= '<label class="col-sm-3 control-label" for="' . $name . '">' . $label . '</label>';
        $field .= '<div class="col-sm-9">';
        $field .= '<select name="' . $name . '" class="form-control" id="' . $name . '">';
        if ($nullOption) {
            $field .= '<option value=""> </option>';
        }
        foreach ($values as $k => $v) {
            $checked = ($k == $checkedValue) ? 'selected' : '';
            $field .= '<option ' . $checked . ' value="' . $k . '">' . $v . '</option>';
        }
        $field .= '</select>';
        $field .= '</div>';
        $field .= '</div>';
        return $field;
    }

}
