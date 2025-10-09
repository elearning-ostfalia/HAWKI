# Installation

* ggf. chmod +x install.sh (falls Permissions nicht passen)

* cp .env.docker .env

* set AI keys in .env

* config folder:

  - create model_providers.php from sample file and add keys

# Docker-Keycloak

* Admin-User: admin/admin
* Users: user1/user1, user2/user2, user3/user3
* 


# Info

* create self signed certificate: 

    openssl req -x509 -nodes -days 3650 -newkey rsa:2048 -keyout olaf.key -out olaf.crt -subj "/C=DE/ST=Germany/L=WF/O=Ostfalia/CN=olaf"

* Exportieren des Realm olaf-realm: 

Wechseln in den Container und dann 

        /opt/keycloak/bin/kc.sh export --realm olaf-realm --file /tmp/olaf-realm.json

Danach die Datei herunterladen.


# Aufruf

Damit dass läuft, muss olaf und keycloak auch noch in /etc/hosts eingetragen werden.

Damit es mit OIDC funktioniert, muss olaf mit im Browser wie folgt gestartet werden:
    
    https://olaf:4443

Keycloak: 

    http://localhost:8080


