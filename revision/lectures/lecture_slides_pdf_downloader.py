import requests
import pdfkit



for tut in range(8,10):
    tut = str(tut)
    filename = "lect"+tut+".html"
    
    url = "https://www.cse.unsw.edu.au/~cs3311/19s1/lectures/0"+tut+"/notes.html"
    pdfkit.from_url(url, filename+".pdf")

   
    url = "https://www.cse.unsw.edu.au/~cs3311/19s1/lectures/0"+tut+"cont/notes.html"
    filename = "lect"+tut+"cont.html"
    
    pdfkit.from_url(url, filename+".pdf")
