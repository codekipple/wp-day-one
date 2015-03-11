# config valid only for Capistrano 3.3.3
lock '3.3.3'

set :application, 'wp-day-one'
set :repo_url, "git@github.com:codekipple/#{fetch(:application)}.git"
set :db_dev, "wpdayone_dev"
set :db_dev_password, "password"

# Default value for :scm is :git
# set :scm, :git
# set :git_enable_submodules, 1

# Default value for :format is :pretty
# set :format, :pretty

# Default value for :log_level is :debug
# set :log_level, :debug

# Default value for :pty is false
# set :pty, true

# Default value for :linked_files is []
# set :linked_files, %w{config/database.yml}

# Default value for linked_dirs is []
# set :linked_dirs, %w{bin log tmp/pids tmp/cache tmp/sockets vendor/bundle public/system}

# Default value for default_env is {}
# set :default_env, { path: "/opt/ruby/bin:$PATH" }

# Default value for keep_releases is 5
set :keep_releases, 3

# set :ssh_options, { forward_agent: true }

# Composer
# --------
# set :composer_bin, "/usr/local/bin/composer"
# set :composer_options, "--optimize-autoloader --prefer-dist --verbose"

namespace :deploy do

    desc 'Restart application'
    task :restart do
        on roles(:app), in: :sequence, wait: 5 do
            # Your restart mechanism here, for example:
            # execute :touch, release_path.join('tmp/restart.txt')
        end
    end

    after :publishing, :restart

    after :restart, :clear_cache do
        on roles(:web), in: :groups, limit: 3, wait: 10 do
            # Here we can do anything such as:
            # within release_path do
            #   execute :rake, 'cache:clear'
            # end
        end
    end

end

after "deploy:updated", "wordpress:plugins:remove"
after "deploy:updated", "composer:copy_vendors"
after "deploy:updated", "composer:install"
after "deploy:updated", "npm:copy_modules"
after "deploy:updated", "npm:install"
after "deploy:updated", "grunt:build"
after "deploy:updated", "wordpress:execute"
after "deploy:published", "wordpress:create_symlink"