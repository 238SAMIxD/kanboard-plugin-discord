<?php

namespace Kanboard\Plugin\Discord;

use Kanboard\Core\Translator;
use Kanboard\Core\Plugin\Base;

/**
 * Discord Plugin
 *
 * @package  discord
 */
class Plugin extends Base
{
    public function initialize()
    {
        // Integrations pages
        $this->template->hook->attach('template:config:integrations', 'discord:config/integration');
        $this->template->hook->attach('template:project:integrations', 'discord:project/integration');
        $this->template->hook->attach('template:user:integrations', 'discord:user/integration');

        // Register notification types
        $this->userNotificationTypeModel->setType('discord', t('Discord'), '\\Kanboard\\Plugin\\Discord\\Notification\\Discord');
        $this->projectNotificationTypeModel->setType('discord', t('Discord'), '\\Kanboard\\Plugin\\Discord\\Notification\\Discord');
    }

    public function onStartup()
    {
        Translator::load($this->languageModel->getCurrentLanguage(), __DIR__.'/Locale');
    }

    public function getPluginDescription()
    {
        return 'Receive notifications on Discord via webhooks';
    }

    public function getPluginAuthor()
    {
        return '238SAMIxD';
    }

    public function getPluginVersion()
    {
        return '1.0.0';
    }

    public function getPluginHomepage()
    {
        return 'https://github.com/238SAMIxD/kanboard-plugin-discord';
    }

    public function getPluginName()
    {
        return 'Discord';
    }

    public function getCompatibleVersion()
    {
        return '>=1.0.37';
    }
}
