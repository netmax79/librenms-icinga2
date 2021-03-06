# librenms-icinga2
Icinga2 plugin for LibreNMS

## Why this plugin ?
I was searching for an integration of my Icinga2 monitoring status to librenms host view. As i was unable to find such an integration
i created it myself.

Icinga2 has a great API for getting all the required informations and also librenms has an API which is used in this project.

## Requirements
All devicenames within librenms must match the hostnames from icinga2!
I used FQDNs in my setup.

## Installation
1. Clone or download this repository and put the Icinga2 folder to your **librenms/html/plugins** directory-

```bash
git clone https://github.com/netmax79/librenms-icinga2.git
cd librenms-icinga2
cp -a Icinga2 /opt/librenms/html/plugins/
chown -R librenms:librenms /opt/librenms/html/plugins/Icinga2
```
2. create an API user within Icinga2 configuration

   See https://icinga.com/docs/icinga2/latest/doc/12-icinga2-api/#authentication 

3. create an librenms API key (librenms setting menu -> API -> API settings)

4. Edit **/opt/librenms/config.php** and add the lines from **config.php.example**
   adapt the variables to the previously created API login credentials

5. You now need to activate the plugin, from your librenms Plugins menu, select Plugin Admin.

6. On the plugin admin page, click on Enable next to the Icinga2 plugin.

## Usage

On each device overview page there will be a box with current monitoring status below the main system informations.

On Overview -> Plugins -> Icinga2 there is a complete overview for all hosts services .

## How does it look?

### complete overview (all hosts)
![](screenshots/all-hosts.png)

### host detail
![](screenshots/host-overview.png)
