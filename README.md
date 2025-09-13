Discord plugin for Kanboard
===========================

Receive Kanboard notifications on Discord via Webhooks.

Author
------

- Based on the Kanboard Slack plugin by Frédéric Guillot
- Adapted for Discord by 238SAMIxD
- License MIT

Requirements
------------

- Kanboard >= 1.0.37

Installation
------------

You have the choice between 3 methods:

1. Install the plugin from the Kanboard plugin manager in one click
2. Download the zip file and decompress everything under the directory `plugins/Discord`
3. Clone this repository into the folder `plugins/Discord`

Note: Plugin folder is case-sensitive.

Configuration
-------------

Firstly, create a Webhook in your Discord server:

1. Server Settings > Integrations > Webhooks > New Webhook
2. Choose the target channel and copy the Webhook URL

You can define a global webhook URL in **Settings > Integrations > Discord** and optionally override it for each project and user.

Notes:

- Discord webhooks post to a fixed channel; the "Channel/Group/User" field is present for parity but is not used by Discord.
- The message includes a direct link to the task when the Kanboard application URL is configured.

Receive individual user notifications
------------------------------------

- Go to your user profile then choose **Integrations > Discord**
- Paste the Discord webhook URL or leave it blank to use the global webhook URL
- Enable Discord in your user notifications **Notifications > Discord**

Receive project notifications
-----------------------------

- Go to the project settings then choose **Integrations > Discord**
- Paste the Discord webhook URL or leave it blank to use the global webhook URL
- Enable Discord in your project notifications **Notifications > Discord**

Troubleshooting
---------------

- Enable the debug mode
- All connection errors with the Discord webhook endpoint are recorded in the log files `data/debug.log` or syslog
