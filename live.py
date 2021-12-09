#pip install Pillow
#pip install bs4
#pip install requests

from bs4 import BeautifulSoup
from PIL import Image

import requests
import os, sys, stat
import PIL 

from io import BytesIO

def obtemNoticias(dir, arquivo):

    url = ''

    #VERIFICA SE O ARQUIVO EXISTE
    if os.path.isfile("urlaovivo.txt") == False :
        print('teste')

        arquivoUrl = open('urlaovivo.txt', 'w')
        
        arquivoUrl.write('')
        arquivoUrl.close()

        os.chmod("urlaovivo.txt", stat.S_IRWXO)
        
    

    arquivoUrl = open('urlaovivo.txt', 'r')

    url = arquivoUrl.readline()
    arquivoUrl.close()


    if url.strip() == '':
        return


    page = requests.get(url)
    soup = BeautifulSoup(page.content, 'html.parser')

    conteudo = soup.find_all('script')

    script = str(conteudo[15]).replace('<script>','').replace('</script>','').replace('\n', '').replace('window.trv2 = ', '').strip()
    if len(script) < 30 :
        script = str(conteudo[14]).replace('<script>','').replace('</script>','').replace('\n', '').replace('window.trv2 = ', '').strip()

        if len(script) < 30 :
            script = str(conteudo[16]).replace('<script>','').replace('</script>','').replace('\n', '').replace('window.trv2 = ', '').strip()

    
    #SALVA INFOS NO ARQUIVO   
    if os.path.isdir(dir) == False :
        os.makedirs(dir)

    arquivo = open('%s/%s.txt' % (dir, arquivo), 'w')

    arquivo.write(str(script))

    arquivo.close()


obtemNoticias("attResultado", "aovivo")
