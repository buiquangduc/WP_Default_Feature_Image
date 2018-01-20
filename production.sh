if [ -z "$1" ]
	  then
	    echo "No argument supplied. Use ./production.sh {version}. For example: ./production.sh 1.1.0"
	    exit
fi
BRANCH="v$1"
# Checkout to master branch
git checkout master
# Pull the latest code from master branch
git pull origin master
# Create new branch
git checkout -b $BRANCH
# Running build script
bash build.sh
# Remove development stuff
rm .gitignore
rm -rf assets build.sh composer.json composer.lock node_modules package-lock.json package.json storage webpack.config.js yarn.lock yarn-error.log .travis.ci .scrutinizer.ci
# Commit and push to production branch
git add .
git commit -m ":tada: Production version $1 $(date) [ci skip]"
git push origin $BRANCH
# Checkout to master branch
git checkout master
bash build.sh
