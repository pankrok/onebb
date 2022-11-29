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
            'acp' => true,
            'ico' => 'fa-bolt',
            'trans' => [
                [
                    'domain' => 'ep',
                    'locale' => 'pl_PL',
                ]
            ],
        ];
    }
    
    public function getEvents(): ?array
    {
        return [
            'myEvent' => 'myFunc',
        ];
    }
    
    public function getAdminEvents(): ?array
    {
        return [
            'myAdminEvent' => 'myAdminFunc',
            'getData' => 'myAdminGetData',
        ];
    }
    
    public function getSnippet(): ?string
    {
        return file_get_contents(__DIR__ . '/snippet.js');
    }
    
    public function getAdminTemplate(): ?string
    {
        return  '<div>
        
                    <input class="form-control" id="epFoo" type="text"></input><br />
                    <button class="btn btn-secondary" id="epBtn">GO!</button><br />
                    <a id="epUrl" href="">link test</a>
                </div>';
    }
    
    public function getAdminScript(): ?string
    {
        return  "   document.getElementById('epUrl').addEventListener('click', (e) => { 
                        e.preventDefault();
                        window.\$obbPlugins.routerPluginPush('ExamplePlugin', 'getAdminTemplateTwo', 'getAdminScriptTwo');
                    }, false);
        
                    let j = null;
                    window.\$obbPlugins.dispatch(
                        'ExamplePlugin',
                        'getData',
                    ).then(response => {
                        document.getElementById('epFoo').value = response.data;
                    }).then(() => {
                        j = new window.\$jodit(document.getElementById('epFoo'), {});
                    });
                                
                    document.getElementById('epBtn').addEventListener('click', () => { 
                    window.\$obbPlugins.dispatch(
                        'ExamplePlugin',
                        'myAdminEvent',
                        { text: document.getElementById('epFoo').value}
                    ).then(response => {console.log(response);});
                }, false);";
    }
    
    public function getAdminTemplateTwo()
    {
        return 'xD';
    }
    
    public function getAdminScriptTwo() {
        return '';
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
    
    public function myFunc($payload)
    {   
       return file_get_contents(__DIR__ .'/example.txt');
    } 

    public function myAdminFunc($payload)
    {
        file_put_contents(__DIR__ .'/example.txt', $payload['data']['text']);
        return $payload;
    }
    
    public function myAdminGetData($payload)
    {
        return file_get_contents(__DIR__ .'/example.txt');
    }
}