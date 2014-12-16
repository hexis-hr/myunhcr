# config valid only for Capistrano 3.1
lock '3.2.1'

set :application, 'MyUNHCR'
set :repo_url, 'git@github.com:Internet-Inovacije/myunhcr.git'
set :stage, :production
set :permission_method,     :acl
set :webserver_user,        "www-data"

set :use_set_permissions,   true

set :linked_dirs, %w{application/logs vendor}

set :writable_dirs, ["application/logs"]

set(:symlinks, [
  {
    source: "/public/uploads",
    link: "/data/uploads"
  }
])

set :format, :pretty
set :log_level, :info

set :deploy_to, '/var/www/myunhcr/'

# Default value for :scm is :git
set :scm, :git

before "deploy:restart", "deploy:install"

namespace :deploy do
    desc "run composer update"
    task :install do
        run "cd #{current_path} && composer update"
        run "php zf.php orm:schema-tool:update --force"
    end
end
