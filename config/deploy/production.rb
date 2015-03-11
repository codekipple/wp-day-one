set :folder, "wpdayone.com"
set :deploy_to, "/var/www/#{fetch(:folder)}"
set :domain, "#{fetch(:folder)}"

# Default branch is :master
set :branch, 'master'

set :db_server, "localhost"
set :db_name, "wpdayone_live"
set :db_user, "wpdayone_live"
set :db_password, "change me"