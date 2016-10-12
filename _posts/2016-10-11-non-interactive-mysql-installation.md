---
layout: post
title:  "Linux-Non-interactive MySQL Installation"
date:   2016-10-11 00:21:56 +1030
categories: Linux, MySQL
tags: [Linux, MySQL]
---
Install MySQL non-interactively (without prompts) in bash scripting.
<!--summary break-->

#### Download MySQL APT Repository
[Latest Ubuntu/Debian DEB](http://dev.mysql.com/downloads/repo/apt/)

#### Turn off "frontend" (prompts) during installations.
{%highlight bash%}
export DEBIAN_FRONTEND=noninteractive
{%endhighlight%}

#### Choose MySQL version and Install
{%highlight bash%}
echo mysql-apt-config mysql-apt-config/enable-repo select mysql-5.7 | sudo debconf-set-selections
dpkg -i /path/to/mysql-apt-config_0.8.0-1_all.deb
apt-get update
apt-get -y install mysql-server
{%endhighlight%}

#### Configure MySQL and add user with password
{%highlight bash%}
cp -r /path/to/my.cnf /etc/mysql/my.cnf
rm -f /path/to/my.cnf
mysql -e "USE mysql; CREATE USER IF NOT EXISTS '$username'@'localhost' IDENTIFIED BY '$mysqlpwd'; GRANT ALL PRIVILEGES ON *.* TO '$username'@'localhost' IDENTIFIED BY '$mysqlpwd'; FLUSH PRIVILEGES;"
{%endhighlight%}

#### Restart mysql server
{%highlight bash%}
service mysql restart 
{%endhighlight%}
