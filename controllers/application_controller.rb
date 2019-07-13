# frozen_string_literal: true

# Classe base dos controllers
class ApplicationController < Sinatra::Base
  configure do
    set :views, 'app/views'
    set :public_dir, 'public'
    set :admin_dir, 'admin'
  end
end
