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

## Acessando o client para testar a aplicação
Caso queria podemos [editar o arquivo hosts](https://www.tecmundo.com.br/sistema-operacional/5214-como-editar-os-arquivos-hosts-do-computador-.htm),  caso tenha alterado o ip no arquivo de configuração Homestead.yaml reflita essa alteração abaixo:
```
192.168.10.10 rpg-game
```

Caso tenha utilizado Homestead como ambiente e tenha seguido as instruções, basta acessar o link:
```
#ip da maquina virtual
http://192.168.10.10/sample-client/

#ou caso tenha alterado o hosts
http://rpg-game/sample-client/
```

Lembrando que esse client é apenas um exemplo de como acessar as funções do web service via ajax.

## Web Service Actions
### Initiative
Description: Run the initiative roll for characters passed on the json request. Is mandatory to pass two characters on the request, all fields on the character object are mandatory as well.

Url: 192.168.10.10/api/v1/initiative

Method: Post

Sample json request:
```
{
  "characters": [
    {
      "name": "Human",
      "attack": "+2",
      "defense": "+1",
      "strength": "+1",
      "agility": "+2",
      "damage": "1d6",
      "hp": "12"
    },
    {
      "name": "Orc",
      "attack": "+1",
      "defense": "0",
      "strength": "+2",
      "agility": "0",
      "damage": "1d8",
      "hp": "20"
    }
  ]
}
```

Sample response:
```
{
    "data": {
        "Human": {
            "roll": 19,
            "bonus": 2,
            "initiative": 21
        },
        "Orc": {
            "roll": 20,
            "bonus": 0,
            "initiative": 20
        },
        "Winner": "Human",
        "VerboseResult": "Initiative round started!\nRolling initiative for Human\nDice roll = 19 + 2 (Agility bonus)\nInitiative = 21\n\nRolling initiative for Orc\nDice roll = 20 + 0 (Agility bonus)\nInitiative = 20\n\nHuman won the initiative round, and will start attacking!\n\n"
    }
}
```

### Attack
Description: Run the attack roll for character passed as "attacker" on the json request. Is mandatory to pass two characters on the request, all fields on the character object and attacker are mandatory as well.

Url: 192.168.10.10/api/v1/attack

Method: Post

Json request:
```
{
  "characters": [
    {
      "name": "Human",
      "attack": "+2",
      "defense": "+1",
      "strength": "+1",
      "agility": "+2",
      "damage": "1d6",
      "hp": "12"
    },
    {
      "name": "Orc",
      "attack": "+1",
      "defense": "0",
      "strength": "+2",
      "agility": "0",
      "damage": "1d8",
      "hp": "20"
    }
  ],
  "attacker": "Human"
}
```

Sample response:
```
{
    "data": {
        "Attacker": {
            "name": "Human",
            "roll": 20,
            "bonus": 4,
            "attack": 24
        },
        "Defender": {
            "name": "Orc",
            "roll": 15,
            "bonus": 0,
            "defense": 15
        },
        "AttackResult": {
            "result": "hit",
            "damageRoll": 6,
            "bonus": 1,
            "damage": 7
        },
        "characters": [
            {
                "name": "Human",
                "attack": "+2",
                "defense": "+1",
                "strength": "+1",
                "agility": "+2",
                "damage": "1d6",
                "hp": "12"
            },
            {
                "name": "Orc",
                "attack": "+1",
                "defense": "0",
                "strength": "+2",
                "agility": "0",
                "damage": "1d8",
                "hp": 13
            }
        ],
        "VerboseResult": "Start attack round! Human is attacking Orc!\n\nRolling dice for Human attack...\nDice roll = 20 + 4 (Agility + Attack bonus)\nAttack = 24\n\nRolling dice for Orc defense...\nDice roll = 15 + 4 (Agility + Defense bonus)\nDefense = 15\n\nHuman attack hit the Orc for 7 of damage!\n"
    }
}
```
