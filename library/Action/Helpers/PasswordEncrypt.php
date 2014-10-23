<?php
/**
 * Action Helper for finding days in a month
 * My_Action_Helpers is the alias from the application.ini file
 * My_Action_Helpers_PasswordEncrypt, where PasswordEncrypt is the name of this file 
 */
class Gomow_Action_Helpers_PasswordEncrypt extends Zend_Controller_Action_Helper_Abstract
{
    /**
     * @var Zend_Loader_PluginLoader
     */
    public $pluginLoader;
    
    const ENC_KEY = "ThiI#is@gomow%EncryTion&key";
    const VECTOR =  "gomow@ve";
    /**
     * Constructor: initialize plugin loader
     *
     * @return void
     */
    public function __construct()
    {
        $this->pluginLoader = new Zend_Loader_PluginLoader();
    }
    /**
     * Returns the number of days in a given month + year
     *
     * @param int $month
     * @param int $year
     * @return int
     * @throws Exception
     */
    public function passwordEncrypt($str,$lngth=16)
    {   
        
        $filter = new Zend_Filter_Encrypt(array('adapter' => 'mcrypt', 'key' => self::ENC_KEY));
        $filter->setVector(self::VECTOR);
        $encrypted = $filter->filter($str);
        return bin2hex($encrypted);
        
        /*$str = substr($str,0,$lngth);
	$str = str_pad($str,$lngth," ");
	$retstr = "";
	for($i=0; $i<$lngth; $i++)
	{
		$sch = substr($str,$i,1);
		$iasc=ord($sch) + 2*$i + 30;
		if ($iasc>255) {
                    $iasc=$iasc-255;
                }
		$sch = chr($iasc);
		$retstr = $retstr.$sch;
	}
	$retstr = implode("*",unpack('C*',$retstr));
	return addslashes($retstr);*/
    }
    /**
     * Strategy pattern: call helper as broker method
     * @param  int $month
     * @param  int $year
     * @return int
     */
    public function direct($password)
    {
        return $this->passwordEncrypt($password);
    }
}