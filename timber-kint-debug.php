<?php
/*
 * Plugin Name: Twig Kint debug
 * Description: Debug Twig templates with Kint
 * Version: 1.0.0
 * Author: Kaido Toomingas
 * Author URI: http://web3.ee
 * Depends: Timber, Kint Debugger
 * */
defined( 'ABSPATH' ) or die( 'Nope, not accessing this' );
class Timber_Kint_Debug {
  /**
   * set max depth for kint traversal
   *
   * @var integer
   */
  private $maxLevels = 4;

  function __construct() {
    add_filter('get_twig', array($this,'add_to_twig'));
  }

  /**
   * add the function to twig
   *
   * @param $twig
   * @return void
   */
  function add_to_twig($twig) {
    $twig->addExtension(new Twig_Extension_StringLoader());
    $twig->addFunction(new Twig_SimpleFunction('kint', array($this, 'call_kint'),  array('is_safe' => array('html'), 'needs_context' => true, 'needs_environment' => true) ));
    return $twig;
  }

  /**
   * run kint
   *
   * @param $twig
   * @param $vars
   * @param boolean $context
   * @return void
   */
  function call_kint($twig, $vars, $context = FALSE) {
    if ($twig->isDebug()) {
      if (!class_exists('Kint')) {
        require_once(__DIR__ . '/vendor/autoload.php');
      }
      Kint::$maxLevels = $this->maxLevels;
      if ($context) {
        Kint::dump($context);
      } else {
        Kint::dump($vars);
      }
    }
  }
}
new Timber_Kint_Debug();
