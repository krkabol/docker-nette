<?php
namespace App\Listeners;

use Fluent\Logger\FluentLogger;
use Kdyby\Events\Subscriber;
use Nette\Application\Application;
use Nette\Application\BadRequestException;
use Nette\Application\Request;
use Nette\SmartObject;

class FluentListener implements Subscriber
{
    use SmartObject;

    protected $fluentLogger;
    public function __construct()
    {
        $this->fluentLogger=new FluentLogger("fluentd","24224");
    }

    public function getSubscribedEvents()
    {
        return array(
            'Nette\\Application\\Application::onStartup',
            'Nette\\Application\\Application::onRequest',
            'Nette\\Application\\Application::onError'
        );
    }

    public function logFluent($key='access',$message)
    {
        $msg['message']=$message;
        $this->fluentLogger->post("nette.".$key , $msg);
    }

    public function onStartup(Application $app)
    {

    }
    public function onRequest(Application $app, Request $request)
    {

        if (PHP_SAPI === 'cli') {
            return;
        }

        $params = $request->getParameters();
        $message=$request->getPresenterName() . (isset($params['action']) ? ':' . $params['action'] : '');
        $this->logFluent('access',$message);

    }
    public function onError(Application $app, \Throwable $e)
    {

        if ($e instanceof BadRequestException) {
            return; // skip
        }
        // log only 500
        $this->logFluent('error',$e->getMessage());

    }
}
