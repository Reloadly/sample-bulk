## About Project

Reloadly's Bulk Sample project is introduced to enable users to deploy a ready-made solution that is maintained by Reloadly. This solution can be easily deployed on any machine that fulfills the required prerequisites. We have made the deployment as simple as possible and are open to suggestions.

## Requirements

The project is built on PHP Laravel framework and Vue.JS We have made it possible to deploy the project with zero development knowledge. However, in order to install this on your machine/server you will need to install a few key things. The project is based on docker to create a fresh and clean environment. The project also required Make (script installer) to process the installation. The actual installation process after the prerequisites is quite simple.

- [Docker](https://www.docker.com/)
- [Make](https://www.gnu.org/software/make/)

You should be able to install both the above requirements on almost all operating systems.


## Installation

Once the requirements are met. You can execute the following command to clone the repository and start the installation process.

``git clone https://github.com/Reloadly/sample-bulk.git && cd sample-bulk && make install``


Just executing the above command should clone the whole repository into `sample-bulk` folder and start the installation process.

Once installation is done the project will run on your localhost (port 80) or on which ever IP you server holds.

After completion you can open the project url (localhost or server ip/domain) and simply login using the default credentials 

## Default Credentials

#### System Admin User

```
Email: admin@system.com
Password: admin 
```

#### Database Users

```
Root Username: root
Root Password: root@rbpdb@123@A
```

```
Non-Root Username: rbp_db_user
Non-Root Password: rbp_db_user@rbpdb@123@A 
```

## Useful Commands

(execute within the sample-bulk folder)

You can stop the project at any time using the below command 

``make stop``

You can start the project using the bellow command

``make start``

You can restart the project using the bellow command

``make restart``

You can install the project using the bellow command

``make install``

## Advanced Users

The system creates and installed two docker containers. One for MySQL (Using Maria-db. container) No port for this container is opened due to security issues. However, this container does provide an internal port (3306) which you may or may not want to open to allow access from outside network. This can be achieved by tinkering into the `docker-compose.yml` file.

In regard to how the web server is build. The system creates an apache-based container on (php 7.3) the build is made specific to the requirements of the project and complete details of this build can be found within the `DockerFiles\Dockerfile` file. By default, the configuration opens the 80 port on the physical machine for direct access. However, if you want to open the system on a specific port you can change this in the docker-compose file.

Please note that if you are using this system on an open environment accessible to the world, It is highly advised to change these credentials. You can change these in the `docker-compose.yml` and `.env` file. Once these are changed you will be required to remove the `volumes` directory if it was created and then run 'make install' again to reinstall the system based on the new credentials.
