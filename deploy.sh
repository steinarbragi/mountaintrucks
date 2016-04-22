# ! /bin/bash
# This is the Vivaldi.com deployment script
# run this to backup the current site to the Vivaldi home folder
# and deploy from staging (rik.viv.ext) server to production.

# Files and folders to be excluded from backup should be added to the file 'exlude_list'

TIME=$(date +"%d-%m-%Y_%H-%M")

echo 'Backing up current site to home folder under the name vivaldisearch_'$TIME
mkdir -p /home/vivaldi/vivaldisearch_$TIME/
rsync -avzhe ssh vivaldi@posch.viv.ext:/var/www/vivaldisearch/ /home/vivaldi/vivaldisearch_$TIME/

echo 'Deployment starting, copying to production'
rsync -avzhe ssh /home/vivaldi/vivaldisearch/_site/ vivaldi@posch.viv.ext:/var/www/vivaldisearch/

echo 'All is done, please verify the site works. If not replace the site with the backup found in /home/vivaldi/vivaldisearch_'$TIME
