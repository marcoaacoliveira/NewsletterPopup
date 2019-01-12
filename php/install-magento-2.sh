#!/usr/bin/env bash
cd /htdocs/
if [ -d "magento2" ]; then
  echo "Folder magento2 already exists. Do you to overwrite it? [y/N]"
  read RewriteIt
  if [ "$RewriteIt" = "y" ]; then
    rm -rf magento2/*
    composer create-project --repository=https://repo.magento.com/ magento/project-community-edition magento2
  fi
fi

#Reading variables
echo "Type url [dev.redstage.com]: "
read Url

echo "Type host [red_mariadb]: "
read Host

echo "Type DB name [magento]: "
read DB

echo "Type DB User [root]: "
read DBUser

echo "Type DB Password [root]: "
read DBPass

echo "Admin Url [admin]: "
read AdminUrl

echo "Admin First Name [admin]: "
read AdminFirstName

echo "Admin Last Name [admin]: "
read AdminLastName

echo "Admin Email [admin@admin.com]: "
read AdminEmail

echo "Admin User [admin]: "
read AdminUser

echo "Admin Pass [admin123]: "
read -s AdminPass

echo "Language [en_US]: "
read Language

echo "Currency [USD]: "
read Currency

echo "Timezone [America/Chicago]: "
read Timezone
#end reading variables

cd magento2/

#Running setup command
bin/magento setup:install \
--base-url=http://${Url:-"dev.redstage.com"}/ \
--db-host=${Host:-"red_mariadb"} \
--db-name=${DB:-"magento"} \
--db-user=${DBUser:-"root"} \
--db-password=${DBPass:-"root"} \
--backend-frontname=${AdminUrl:-"admin"} \
--admin-firstname=${AdminFirstName:-"Admin"} \
--admin-lastname=${AdminLastName:-"Admin"} \
--admin-email=${AdminEmail:-"admin@admin.com"} \
--admin-user=${AdminUser:-"admin"} \
--admin-password=${AdminPass:-"admin123"} \
--language=${Language:-"en_US"} \
--currency=${Currency:-"USD"} \
--timezone=${Timezone:-"America/Chicago"} \
--use-rewrites=1
#End setup command


#Permissions
find var generated vendor pub/static pub/media app/etc -type f -exec chmod g+w {} +
find var generated vendor pub/static pub/media app/etc -type d -exec chmod g+ws {} +
chown -R :www-data .
chmod u+x bin/magento
# End Permissions

echo "Magento installed successfully. Enjoy!"