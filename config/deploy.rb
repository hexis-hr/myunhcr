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

before "deploy:finished", "deploy:symlink_shared"
before "deploy:finished", "deploy:install"

namespace :deploy do

    desc "run composer update"
    task :install do
        on "deploy@dev.hexis.hr" do
            execute "cd #{current_path} && composer update"
            execute "cd #{current_path} && php zf.php orm:schema-tool:update --force"
            execute :sudo, :chown, "-R www-data:www-data #{current_path}/data/DoctrineORMModule/Proxy/"
            execute :sudo, :chown, "-R www-data:www-data #{current_path}/module/Application/language/"
        end
    end

    desc "Symlink shared configs and folders on each release."
        task :symlink_shared do
            on "deploy@dev.hexis.hr" do
                execute "ln -nfs #{shared_path}/config/doctrine.local.php /var/www/myunhcr/current/config/autoload/doctrine.local.php"
            end
        end
end
