role :app, %w{deploy@dev.hexis.hr}
server 'dev.hexis.hr', user: 'deploy', roles: %w{app}