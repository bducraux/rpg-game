# Simulador de batalhas por turnos

Desenvolvido utilizando [Laravel Lumen](http://lumen.laravel.com/), pois me parece ser o framework mais rápido para pequenas aplicaçoes restful.
[Homestead](https://laravel.com/docs/5.4/homestead) já esta integrado ao projeto, facilitando o teste do código.



## Guia para instalação
É necessário ter Comporser instalado para realizar a instalaçao das dependências do projeto, caso não tenho instalado siga as instruções do site. [Composer Install](https://getcomposer.org/download/) 

Para clonar e rodar essa aplicação, você precisará do [Git](https://git-scm.com/) e [Composer](https://getcomposer.org/download/) instalados no seu computador. Utilizando o terminal de linha de comando:

```
#Clone o projeto
git clone https://github.com/bducraux/rpg-game.git

#Vá para o diretório do projeto
cd rpg-game

#Instale as dependências do projeto
composer install
```

## Utlizando Homestead como ambiente:

O Homestead é um ambiente de desenvolvimento pronto para que possamos facilmente desenvolver e testar aplicações em Laravel sem precisarmos nos preocupar com configuração de servidores e instalação de recursos.
O Homestead nada mais é do que uma máquina virtual com software já instalado.

Para a instalação do homestead é necessário a instalação do Vagrant e do Virtualbox.

[https://www.vagrantup.com/](https://www.vagrantup.com/)

[https://www.virtualbox.org/wiki/Downloads](https://www.virtualbox.org/wiki/Downloads)

Não há segredo, basta baixar a opção certa para seu sistema (32 ou 64 Bits) e instalá-los.

Com Vagrant e Virtualbox instalados, dentro do diretório do projeto,rode o comando:
```
#Cria os arquivos de configuração para a maquina virtual
php vendor\laravel\homestead\bin\homestead make
```

Com esse comando o arquivo Homestead.yaml é criado, nesse arquivo é onde configuramos a maquina virtual que será criada, ip default é 192.168.10.10, altere caso seja necessário. e rode o comando abaixo.
```
#Inicia a maquina virtual
vagrant up
```
Caso enfrente erro para iniciar a maquina virtual verifique se Intel Virtualization Technology está habilitada na Bios no seu computador. [Instruções de como habilitar](http://www.sysprobs.com/disable-enable-virtualization-technology-bios).

## Acessando a aplicação
Caso queria podemos [editar o arquivo hosts](https://www.tecmundo.com.br/sistema-operacional/5214-como-editar-os-arquivos-hosts-do-computador-.htm),  caso tenha alterado o ip no arquivo de configuração Homestead.yaml reflita essa alteração abaixo:
```
192.168.10.10 rpg-game
```

Caso tenha utilizado Homestead como ambiente e tenha seguido as instruções, basta acessar o link:
```
#ip da maquina virtual
http://192.168.10.10

#ou caso tenha alterado o hosts
http://rpg-game
```

