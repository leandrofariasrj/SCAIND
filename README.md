# SCAIND

SCAIND ou Sistema de Cadastro Independente, é uma ferramenta para cadastro interno de funcionários que utiliza tecnologia web com html, css, php e Banco de dados. Inicialmente desenvolvida para atender o núcleo de TI, com função de organizar dados de acessos a perfil de usuários de domínio, email e/ou dados de natureza sensíveis. A versão atual ganhou atenção em itens de segurança, como: registro de atividades opcionais dos registros e controle de sessão ativa. 
O projeto SCAIND nasceu da necessidade converter um projeto criado em MS ACCESS, que tinha como função guardar informações de funcionários, para o uso de sistemas em empresa, abrigando dados sensíveis vinculados aos dados de logins de sistemas e emails. A conversão desse sistema tinha como objetivo principal, realizar o reaproveitamento dos dados já existentes, reorganizar a apresentação dos dados e inserir segurança no compartilhamento das informações pela rede local. A documentação de implantação do projeto SCAIND que virá a seguir, tem como intuito orientar quanto aos requisitos básicos e os primeiros passos para utilização do sistema em um ambiente interno e controlado. Plataforma utilizada na implantação:

● Servidor

  ○ Sistema operacional Debian 9x
  ○ Banco de dados MariaDB versão 10.1.44
  ○ PHP versão 7.0.33
  ○ Apache versão 2.4.25
  
● Cliente

  ○ Sistema operacional com suporte aos navegadores
    ■ Google Chrome (V. 86.0.4240.75)
    ■ Internet Explorer (V. 11.0.9600.19596)
    ■ Firefox Browser (V. 82.0.2)
  ○ Resolução
    ■ Mínima de 1280 x 720
    ■ Ideal 1366 x 768 ou superior
    
  Etapas a serem seguidas na implantação da ferramenta de cadastro SCAIND:

1. Criação e atribuição de permissões na pasta de armazenamento de sessões, padronizada em "/sessao";

2. Copiar pasta do projeto SCAIND no caminho de publicação no servidor apache, atribuindo as permissões necessárias;

3. Criar o banco de dados "SCAIND", realizando a importação (via comando dump ou carregamento via PhpMyAdmin) das tabelas estabelecidas no arquivo "SCAIND_Implantar.sql";

4. Alterar o usuário e senha de acesso ao banco de dados, no arquivo "acesso.php";

5. Reinicie o servidor Apache e teste o acesso no endereço:"caminho_do_servidor/scaind/logon.php".

  Primeiros passos para criar o primeiro usuário gerenciador de cadastros:

  Na tela de logon acessar o usuário "root scaind" com a senha "#root@scaind" (Usuário ou senha raíz de administração podem ser alteradas no script "logon.php" na
função de testeLogon), após o login é necessário criar um setor para a liberar a criação de cadastro, navegue nas opções ‘administração de cadastros’ ==> ‘setor’ para adicionar um novo setor. Com o setor criado adicione um usuário com as devidas informações, inserindo no mínimo o nome completo, nome de usuário e senha. Após o cadastro criado, busque o mesmo na barra de pesquisa e altere as permissões na opção acesso.
  O acesso do novo usuário é feito com os dados de usuário e senha cadastrada, após o acesso o usuário pode alterar a senha, observando que a alteração de senha para o acesso ao sistema “SCAIND”, não modifica a senha cadastrada anteriormente.

  Considerações:

  Respeitando a versão do PHP, configurações e permissões das pastas citadas, a utilização de servidores Windows para implantar o projeto é possível. No uso de servidores Windows, considere a alteração do caminho da pasta de sessões salvas (‘session.save_path’) conforme o padrão da nomenclatura aplicada no Windows, tal
caminho está especificado nos arquivos ‘consulta.php’, ‘credencial.php’, ‘crud_scaind.php’, ‘fnc_cadastros.php’, ‘index.php, logon.php’, ‘novo_cadastro.php’ e ‘novo_setor.php’. A alteração deve ser feita no comando “ini_set(‘session.save_path’ , ‘caminho_correto’)”
localizado no início dos arquivos citados.
  Quanto ao banco de dados, testes foram realizados no MySQL obtendo êxito no funcionamento do sistema, a escola do MariaDB se deu pela gratuidade do mesmo. O
banco de dados foi inserido no mesmo servidor que publicava a aplicação, o destacamento do serviços de banco de dados, implica na configuração do caminho para o novo servidor no arquivo "acesso.php", além de configurações de acessos externos ao banco de dados. 
  Considerando que o projeto foi estabelecido de uma forma independente, sem fins lucrativos e a fim de exercitar o meu aprendizado acadêmico, podemos encontrar discordâncias no quesito estrutural da codificação, mas creio ter chego o mais próximos da organização adequada para uma boa fluidez do código, assim como o seu funcionamento da aplicação. A possibilidade de haver falhas (Bugs) na codificação não é descartada, em
caso de falhas favor reportar no email “ projectserverone@gmail.com ”, idéias para a
melhoria da aplicação serão bem vindas no email citado, assim como conselhos na
formação do código.
