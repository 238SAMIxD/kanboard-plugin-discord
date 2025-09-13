<?php

namespace Kanboard\Plugin\Discord\Notification;

use Kanboard\Core\Base;
use Kanboard\Core\Notification\NotificationInterface;
use Kanboard\Model\TaskModel;

/**
 * Discord Notification
 */
class Discord extends Base implements NotificationInterface
{
    /**
     * Send notification to a user
     *
     * @param array $user
     * @param string $eventName
     * @param array $eventData
     */
    public function notifyUser(array $user, $eventName, array $eventData)
    {
        $webhook = $this->userMetadataModel->get($user['id'], 'discord_webhook_url', $this->configModel->get('discord_webhook_url'));
        $channel = $this->userMetadataModel->get($user['id'], 'discord_webhook_channel');

        if (! empty($webhook)) {
            if ($eventName === TaskModel::EVENT_OVERDUE) {
                foreach ($eventData['tasks'] as $task) {
                    $project = $this->projectModel->getById($task['project_id']);
                    $eventData['task'] = $task;
                    $this->sendMessage($webhook, $channel, $project, $eventName, $eventData);
                }
            } else {
                $project = $this->projectModel->getById($eventData['task']['project_id']);
                $this->sendMessage($webhook, $channel, $project, $eventName, $eventData);
            }
        }
    }

    /**
     * Send notification to a project
     *
     * @param array $project
     * @param string $eventName
     * @param array $eventData
     */
    public function notifyProject(array $project, $eventName, array $eventData)
    {
        $webhook = $this->projectMetadataModel->get($project['id'], 'discord_webhook_url', $this->configModel->get('discord_webhook_url'));
        $channel = $this->projectMetadataModel->get($project['id'], 'discord_webhook_channel');

        if (! empty($webhook)) {
            $this->sendMessage($webhook, $channel, $project, $eventName, $eventData);
        }
    }

    /**
     * Build Discord message payload
     *
     * @param array $project
     * @param string $eventName
     * @param array $eventData
     * @return array
     */
    public function getMessage(array $project, $eventName, array $eventData)
    {
        if ($this->userSession->isLogged()) {
            $author = $this->helper->user->getFullname();
            $title = $this->notificationModel->getTitleWithAuthor($author, $eventName, $eventData);
        } else {
            $title = $this->notificationModel->getTitleWithoutAuthor($eventName, $eventData);
        }

        $taskTitle = $eventData['task']['title'];
        $content = '['.$project['name'].'] '.$title.' ('.$taskTitle.')';

        $embeds = [];
        if ($this->configModel->get('application_url') !== '') {
            $url = $this->helper->url->to('TaskViewController', 'show', ['task_id' => $eventData['task']['id'], 'project_id' => $project['id']], '', true);
            $embeds[] = [
                'title' => 'View Task',
                'url' => $url,
            ];
        }

        $payload = ['content' => $content];
        if (! empty($embeds)) {
            $payload['embeds'] = $embeds;
        }

        return $payload;
    }

    /**
     * Send message to Discord webhook
     *
     * @param string $webhook
     * @param string $channel Ignored by Discord webhooks; kept for parity
     * @param array $project
     * @param string $eventName
     * @param array $eventData
     */
    protected function sendMessage($webhook, $channel, array $project, $eventName, array $eventData)
    {
        $payload = $this->getMessage($project, $eventName, $eventData);
        // Discord webhooks do not support overriding channel via payload; channel meta is ignored
        $this->httpClient->postJsonAsync($webhook, $payload);
    }
}
