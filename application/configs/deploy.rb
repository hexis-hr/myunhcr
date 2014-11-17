# config valid only for Capistrano 3.1
lock '3.2.1'

set :application, 'Indigo'
set :repo_url, 'git@github.com:Internet-Inovacije/myunhcr.git'
set :stage, :production
set :permission_method,     :acl
set :webserver_user,        "www-data"

set :use_set_permissions,   true

set :linked_dirs, %w{application/logs}

set :writable_dirs, ["application/logs"]

set :format, :pretty
set :log_level, :info

set :scm, :git