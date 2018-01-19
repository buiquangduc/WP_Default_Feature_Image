if [ -z "$1" ]
	  then
	    echo "No argument supplied. Use ./production.sh {version}. For example: ./production.sh 1.1.0"
	    exit
fi
# Checkout to master branch
git checkout master
# Pull the latest code from master branch
git pull git@github.com:buiquangduc/WP_Default_Feature_Image.git master
# Create new branch
git checkout -b $1
# Running build script
bash build.sh
# Remove development stuff
rm .gitignore
rm -rf assets build.sh composer.json composer.lock node_modules package-lock.json package.json storage webpack.config.js yarn.lock yarn-error.log
# Commit and push to production branch
git add .
git commit -m ":tada: Production version $1 $(date)"
git push git@github.com:buiquangduc/WP_Default_Feature_Image.git $1
# Checkout to master branch
git checkout master
