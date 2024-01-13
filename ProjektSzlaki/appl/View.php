<?php
 
namespace appl  ;
 
/**
 * klasa pelniaca role View w modelu MVC ktora fomuuje odpowiedni 
 * frontend w zaleznosci od danych otrzymanych w modelu 
 */
class View
{
    protected $_file;
    protected $_data = array();
    /**
     * konstruktor, ktorego argument to nazwa pliku w ktorym jest szablon strony
     */
    public function __construct($template)
    {
        $file = 'template/'.strtolower($template).'.tpl' ;
        if ( file_exists($file) )  
         { $this->_file =  $file ; }
        else
         { throw new Exception("Template " . $file . " doesn't exist.") ; }
    }
      
    public function __set($key, $value)
    {
        $this->_data[$key] = $value;
    }
      
    public function __get($key) 
    {
        return $this->_data[$key];
    }
      
    public function __toString()
    {         
        extract($this->_data);
        //otwieranie bufora
        ob_start();
        include($this->_file);
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }
}
 
?>