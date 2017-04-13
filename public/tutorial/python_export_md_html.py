# pip install markdown2
from markdown2 import Markdown
import markdown2 
import glob, os

def file_get_contents(filename):
    	with open(filename) as f:
       		return f.read()


for file in glob.glob("*.md"):
		template = file_get_contents("template.html");
		markdowner = Markdown()
		html = markdown2.markdown_path(file).encode('UTF-8')
		f = open(file[:-3]+'.html', encoding='utf-16', mode='w')
		template = template.replace("[content_html]",html.decode('utf-8'))
		f.write(template)  # python will convert \n to os.linesep
		f.close()  # you can omit in most cases as the destructor will call it