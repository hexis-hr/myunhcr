module Sass::Script::Functions 
  def file_exists(image_file) 
    path = Dir.getwd + "/" + image_file.value 
    Sass::Script::Bool.new(File.exists?(path)) 
  end 
end
