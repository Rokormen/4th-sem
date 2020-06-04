This is a repository for the 4th-sem projec

[![buddy pipeline](https://app.buddy.works/rokormen/4th-sem/pipelines/pipeline/259721/badge.svg?token=16833720c9a89c117da47ccc87f58bec97ff7420a8895bfb53812504d2dba071 "buddy pipeline")](https://app.buddy.works/rokormen/4th-sem/pipelines/pipeline/259721)

## Введение
Данный сайт предназначен для того, чтобы люди могли общаться посредством чат-комнат. Разработан как проект для университета.  
## Пособие по установке

### Шаг 1: Что вам понадобится.
Это пособие предполагает, что в Вашем распоряжении имеются сервер с опреационной системой Ubuntu 18.04 и базовые знания git.

### Шаг 2: Установка окружения
Для установки окружения (а именно Apache, PHP, MySQL) вам необходимо ввести следующие команды в терминал.
* Apache:  
`sudo apt-get install apache2`
* PHP:  
`sudo apt-get install php libapache2-mod-php php-mysql`
* MySQL:  
`sudo apt-get install mysql-server`  
`sudo mysql_secure_installation` - для того, чтобы задать политику безопасности на установленной базе данные  
### Шаг 3: Клонирование репозитория.
В терминале перейдите в папку //var/www/  
Инициализируйте гит, а затем склонируйте данные репозитория командой  
`git clone https://github.com/Rokormen/4th-sem.git html`  
### Шаг 4: Перенос базы данных
Для того, чтобы создать базу данных для работы с сайтом зайдите в оболочку MySQL `$ mysql -u root`  
`CREATE DATABASE name;`  
Данной командой создайте пустую базу данных с именем name.  
Затем командой в терминале  
`mysql -u root name < lab6.sql`  
Импортируйте базу данных на базу созданную вами базу.  
