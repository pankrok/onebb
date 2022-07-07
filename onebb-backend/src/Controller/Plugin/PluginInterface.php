<?php

namespace App\Controller\Plugin;

interface PluginInterface
{
    public function info() : array;
    public function getEvents(): ?array;
    public function getAdminEvents(): ?array;
    public function getSnippet(): ?string;
    public function active() : bool;
    public function deactive() : bool;
    public function install() : bool;
    public function uninstall() : bool;
}