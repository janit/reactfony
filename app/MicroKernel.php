<?php

// app/MicroKernel.php
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\RouteCollectionBuilder;

class MicroKernel extends Kernel
{
    use MicroKernelTrait;

    public function registerBundles()
    {
        $bundles =  array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new AppBundle\AppBundle(),
            );
        
        return $bundles;
    }

    protected function configureRoutes(RouteCollectionBuilder $routes)
    {
        $routes->add('/', 'kernel:indexAction', 'index');
    }

    protected function configureContainer(ContainerBuilder $c, LoaderInterface $loader)
    {
        $c->loadFromExtension('framework', ['secret' => '12345']);
    }

    public function indexAction()
    {

        $react_source = file_get_contents(__DIR__.'/../web/js/react.js');
        $app_source = file_get_contents(__DIR__.'/../web/js/components.js');
        
        $rjs = new ReactJS($react_source, $app_source);
        
        $rjs->setComponent('Timer', array(
            'startTime' => time()
        ));

        $output = '
            <html>
            <head>
                <title>Epoch at server</title>
                <script src="/js/react.js"></script>
                <script src="/js/components.js"></script>
            </head>
            <body>
            <h1>Epoch server time</h1>
            <h2>Client side only</h2>
            <div id="client"></div>
            <h2>Server side with a two second client detach</h2>
            <div id="server">' . $rjs->getMarkup() . '</div>
            <script>

            ' . $rjs->getJS("#client") . '

            setTimeout(function(){
                ' . $rjs->getJS("#server") . '
            }, 2000);
            </script>
            </body>
            </html>
        ';
        
        $response = new Response($output);
        
        return $response;
    }
}

