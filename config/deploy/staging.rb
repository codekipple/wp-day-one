set :folder, "staging.wpdayone.com"
set :deploy_to, "/var/www/#{fetch(:folder)}"
set :domain, "#{fetch(:folder)}"

# deploy current branch
set :branch, $1 if `git branch` =~ /\* (\S+)\s/m

set :db_server, "localhost"
set :db_name, "wpdayone_demo"
set :db_user, "wpdayone_demo"
set :db_password, "change me"