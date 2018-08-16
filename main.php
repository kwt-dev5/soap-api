<?php
    ini_set('display_errors',1);
    ini_set('default_socket_timeout', 100000);
    use SoapClient;
    use SoapVar;
    use SoapHeader;





class WsseAuthHeader extends SoapHeader {
    private $wss_ns = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd';
    function __construct($user, $pass, $ns = null) {
        if ($ns) {
            $this->wss_ns = $ns;
        }

        $auth = new stdClass();
        $auth->Username = new SoapVar($user, XSD_STRING, NULL, $this->wss_ns, NULL, $this->wss_ns);
        $auth->Password = new SoapVar($pass, XSD_STRING, NULL, $this->wss_ns, NULL, $this->wss_ns);

        $username_token = new stdClass();
        $username_token->UsernameToken = new SoapVar($auth, SOAP_ENC_OBJECT, NULL, $this->wss_ns, 'UsernameToken', $this->wss_ns);

        $security_sv = new SoapVar(
                new SoapVar($username_token, SOAP_ENC_OBJECT, NULL, $this->wss_ns, 'UsernameToken', $this->wss_ns), SOAP_ENC_OBJECT, NULL, $this->wss_ns, 'Security', $this->wss_ns);

        parent::__construct($this->wss_ns, 'Security', $security_sv, true,'http://abce.com');
    }

}

    $wsdl = 'https://external-eme-uat-03.emersion.com.au/Accounts.wsdl';
        $username = 'your user name'; 
        $password = 'your password';  
        
    $wsse_header = new WsseAuthHeader($username, $password);
    $client = new SoapClient($wsdl, array(
        "trace" => 1,
        "exceptions" => 1,
        'verifypeer'=>true,
        )
    );
    $client->__setSoapHeaders(array($wsse_header));
    
    
     
    $request = array('AccountID' => "2242254");
    $results = $client->__soapCall('GetAccount',$request);

    dd($results);

?>