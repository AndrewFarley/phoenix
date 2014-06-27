# config valid only for Capistrano 3.1
lock '3.1.0'

set :application, 'beta.dosomething.org'
set :repo_url, 'git@github.com:blisteringherb/dosomething.git'

set :branch, "dev"
if ENV['branch']
    set :branch, ENV['branch'] || 'dev'
end

set :ssh_options, { :forward_agent => true }

namespace :deploy do

  desc "Run ds build tasks"
  task :build do
    on roles(:app) do |host|
      execute "cd '#{release_path}'; #{release_path}/bin/ds build"
      execute "cd '#{release_path}/lib/themes/dosomething/paraneue_dosomething'; grunt prod"
      execute "cd '#{release_path}/html'; drush vset --yes ds_version " + ENV['branch']
      execute "cd '#{release_path}/html'; echo " + ENV['branch'] + " > VERSION"
    end
  end

  task :shared_links do
    on roles(:app) do |host|
      execute "cd '#{release_path}/html/sites/default'; rm -rf files 2> /dev/null; ln -s #{shared_path}/files files"
      execute "ln -s #{shared_path}/settings.#{:stage}.php #{release_path}/html/sites/default/settings.#{:stage}.php"
      if :stage == 'staging'
        execute "printf 'User-agent: *\nDisallow: /' > #{release_path}/html/robots.txt"
      end
    end
  end

  after :updated, 'deploy:build'
  after :build, 'deploy:shared_links'

end
