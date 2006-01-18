--TEST--
XMLRPCext Backend XML-RPC server Validator1 test (manyTypesTest)
--FILE--
<?php
class TestServer {
    /**
     * test function
     *
     * see http://www.xmlrpc.com/validator1Docs
     *
     * @param int $int an integer
     * @param boolean $bool a boolean
     * @param string $string a string
     * @param float $double a float/double
     * @param mixed $datetime a datetime
     * @param mixed $base64 a base64 encoded string
     * @return array result
     */
    public static function manyTypesTest($int, $bool, $string, $double, $datetime, $base64) {
        return array($int, $bool, $string, $double, $datetime, $base64);
    }
}

set_include_path(realpath(dirname(__FILE__) . '/../../../../') . PATH_SEPARATOR . get_include_path());
require_once 'XML/RPC2/Server.php';
$options = array(
	'prefix' => 'validator1.',
	'backend' => 'Php'
);

$server = XML_RPC2_Server::create('TestServer', $options);
$GLOBALS['HTTP_RAW_POST_DATA'] = <<<EOS
<?xml version="1.0" encoding="iso-8859-1"?>
<methodCall>
<methodName>validator1.manyTypesTest</methodName>
<params>
 <param>
  <value>
   <int>1</int>
  </value>
 </param>
 <param>
  <value>
   <boolean>1</boolean>
  </value>
 </param>
 <param>
  <value>
   <string>foo</string>
  </value>
 </param>
 <param>
  <value>
   <double>3.141590</double>
  </value>
 </param>
 <param>
  <value>
   <dateTime.iso8601>20060116T19:14:03</dateTime.iso8601>
  </value>
 </param>
 <param>
  <value>
   <base64>Zm9vYmFy&#10;</base64>
  </value>
 </param>
</params>
</methodCall>
EOS
;
$response = $server->getResponse();
$result = (XML_RPC2_Backend_Php_Response::decode(simplexml_load_string($response)));
var_dump($result);

?>
--EXPECT--
array(6) {
  [0]=>
  int(1)
  [1]=>
  bool(true)
  [2]=>
  string(3) "foo"
  [3]=>
  float(3.14159)
  [4]=>
  float(1137438843)
  [5]=>
  string(6) "foobar"
}
