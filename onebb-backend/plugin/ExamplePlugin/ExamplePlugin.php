<?php

namespace Plugin\ExamplePlugin;

use App\Controller\Plugin\PluginInterface;
use App\Controller\Plugin\PluginController;

class ExamplePlugin extends PluginController implements PluginInterface
{
    
    public function info(): array
    {
        return [
            'name' => 'ExamplePlugin',
            'version' => '0.1.5',
            'meta' => 'This is an example plugin showing box and alert.',
            'trans' => [
                [
                    'domain' => 'ep',
                    'locale' => 'pl_PL',
                ]
            ],
        ];
    }
    
    public function getEvents(): array
    {
        return [
            'myEvent' => 'myFunc'
        ];
    }
    
    public function getSnippet(): string
    {
        return 
        "<script>
            document.addEventListener('readystatechange', function() {
                if (document.readyState === 'complete') {
                    window.\$obbPlugins.subscribe('Home', function(){
                    window.\$obbPlugins
                        .dispatch('ExamplePlugin', 'myEvent')
                        .then(response => {
                            document.querySelector('#examplePluginContent').innerHTML = (response.data)
                        });
                    })   
                }
            }); 
        </script>";
    }
    
    public function install(): bool
    {
        
        
        $box = $this->createBox('EPBox', 
            '<div class="box"><div class="box-content" id="examplePluginContent"></div></div>'
        );
         
        $skinbox = $this->setBoxOnSkin($box, 'Home', 'top');
        
        $data = [
            'box_id' => $box->getId(),
            'skinbox_id' => $skinbox->getId(),
        ];
        
        file_put_contents(__DIR__ . '/plugin_storage.json', json_encode($data));
        return true;   
    }
    
    public function active(): bool
    {
        $data = json_decode(file_get_contents(__DIR__ . '/plugin_storage.json'), true);
        $this->activeSkinBox($data['skinbox_id']);
        
        return true; 
    }
    
    public function uninstall(): bool
    {
        $data = json_decode(file_get_contents(__DIR__ . '/plugin_storage.json'), true);
        $this->deleteBox($data['box_id']);
        return true; 
    }
    
    public function deactive(): bool
    {
        $data = json_decode(file_get_contents(__DIR__ . '/plugin_storage.json'), true);
        $this->deactiveSkinBox($data['skinbox_id']);
        return true; 
    }
    
    public function myFunc()
    {   
        $txt = '<div class="alert danger text-center"><strong>%s</strong></div>';
       
       return sprintf($txt, $this->translator->trans('hello'));
    }    
}