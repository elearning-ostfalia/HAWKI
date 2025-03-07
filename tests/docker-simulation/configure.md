* copy tests/docker-simulation/.env.example to .env and set passwords

* config folder:

  - create model_providers.php from sample file and add keys

# Docker-Keycloak

* Admin-User: admin/admin
* Users: user1/user1, user2/user2, user3/user3
* 

* Exportieren des Realm olaf-realm: 

Wechseln in den Container und dann 

        /opt/keycloak/bin/kc.sh export --realm olaf-realm --file /tmp/olaf-realm.json

Danach die Datei herunterladen.

# Aufruf

Damit dass läuft, muss olaf und keycloak auch noch in /etc/hosts eingetragen werden.

Damit es mit OIDC funktioniert, muss olaf mit olaf:8083 im Browser gestartet werden:
http://olaf:8083

Keycloak: http://localhost:8080


