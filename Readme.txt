
Question�rios 2014 e 2016

1 - o conteudo deve ser copiado para uma pasta windows e executado a partir dessa pasta


2 - a pasta "apps" tem os executaveis/configuraveis para correr a ferramenta


3 - correr o "Extractor.cmd" na pasta get_pages
> este processo vai descarregar o conteudo dos question�rios e respectivas respostas (demora algum tempo uma vez que as p�ginas t�m que fazer o rendering do js)
> vai tamb�m criar um ficheiro "list.txt" com as empresas por ano


4 - correr o "Extractor.cmd" na pasta get_dim e verificar o resultado na pasta get_dim/out
> este processo vai utilizar o ficheiro "list.txt" na pasta get_pages para gerar os csv com os textos e as dimens�es por ano

as duas primeiras colunas de cada csv s�o um numero (1-7) atribuido a cada dimens�o e o nr da resposta para a quest�o dessa linha.
podem ser apagadas abrindo os csv no excel

NOTA : o separador do csv � ";"

Criado por :
Joaquim.A.Marques@sapo.pt
27-03-2017