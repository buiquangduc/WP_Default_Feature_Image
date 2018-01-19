if [ -z "$1" ]
  then
    echo "No argument supplied. Use ./production.sh {version}. For example: ./production.sh 1.1.0"
    exit
fi

# Checkout to master branch
git checkout master
# Pull the latest code from master branch
git pull https://github.com/buiquangduc/WP_Default_Feature_Image.git master
# Checkout to production branch
git checkout -b production
# Running build script
bash build.sh
# Remove development stuff
git rm -rf --cached assets build.sh composer.json composer.lock node_modules package-lock.json package.json storage webpack.config.js yarn.lock
# Commit and push to production branch
git add .
git commit -m ":tada: Production version $1 $(date)"
git push https://github.com/buiquangduc/WP_Default_Feature_Image.git product
# Checkout to master branch
git checkout master

