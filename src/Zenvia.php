<?php
/**
 * Created by PhpStorm.
 * User: alessandro
 * Date: 21/06/17
 * Time: 18:06
 */

namespace FeitozaAle\Zenvia;


use FeitozaAle\Zenvia\Model\Sms;
use FeitozaAle\Zenvia\Model\SmsFacade;

class Zenvia
{
    const DEFAULT_API_URL = 'https://api-rest.zenvia360.com.br';

    private $urlApi;

    private $account;

    private $password;

    public function __construct($account, $password, $urlApi = null)
    {
        $this->urlApi = self::DEFAULT_API_URL;

        if($urlApi) {
            $this->urlApi = $urlApi;
        }

        $this->account = $account;
        $this->password = $password;
    }

    public function send($to, $message)
    {
        $smsFacade = new SmsFacade($this->account, $this->password, $this->urlApi);

        $sms = new Sms();
        $sms->setTo($to);
        $sms->setMsg($message);
        $sms->setId(uniqid());
        $sms->setCallbackOption(Sms::CALLBACK_NONE);

        try{
            //Envia a mensagem para o webservice e retorna um objeto do tipo SmsResponse com o status da mensagem enviada
            $response = $smsFacade->send($sms);

            echo "Status: ".$response->getStatusCode() . " - " . $response->getStatusDescription();
            echo "\nDetalhe: ".$response->getDetailCode() . " - " . $response->getDetailDescription();

            if($response->getStatusCode()!="00"){
                echo "\nMensagem não pôde ser enviada.";
            }

        }
        catch(\Exception $ex){
            echo "Falha ao fazer o envio da mensagem. Exceção: ".$ex->getMessage()."\n".$ex->getTraceAsString();
        }

    }


}