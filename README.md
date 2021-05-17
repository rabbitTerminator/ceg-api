**`Ceg Rest Api v0.1`**  

## Struktura projektu
````
  App    
    &nbsp;&nbsp;&nbsp;&nbsp;/  - Uklada databázové modely, objektove reprezentace dat   
    &nbsp;&nbsp;&nbsp;&nbsp;/Http/Controllers -  základní logika aplikace
    &nbsp;&nbsp;&nbsp;&nbsp;/Http/Middleware  -  splňuje kod do logiky kontroleru 
    &nbsp;&nbsp;&nbsp;&nbsp;/Http/Requests    - vlastni reprezentace requestu pro validace dat 
    &nbsp;&nbsp;&nbsp;&nbsp;/Helpers  - pomocne  funkce
   
  config/ konfigurace databází , auntifikace a CORS politiky
  
  routes/web.php  konfigurace web cest aplikace
  
  doker/ konfigurace konfigurace pro docker kontejnery , ve kterých běží všechny služby aplikace
  docker-compose.yml - konfigurační file docker cpmpose , pro spouštění aplikace
  .env -  File obsahující v sobe všechny proměny prostředí pro aplikace , například název databází.
  ````
## Docker

Backendova cast aplikace úplně běží uvnitř docker kontejnerů , Doker umožňuje dost rychle spustit všechny potřebné servisy a služby uvnitř kontejneru , tato architektura umožňuje rozběhnout aplikace kdykoli dost rychle. Termín kontejner lze chápat jako virtuální počítač.

## Docker Compose


Docker Compose je nástroj pro spouštění aplikací s více kontejnery na Dockeru
definováno pomocí  (https://compose-spec.io).
Soubor typu Compose se používá k definování toho, jak tvoří jeden nebo více kontejnerů aplikace.
Jakmile máte soubor Compose, můžete vytvořit a spustit aplikaci pomocí
jediný příkaz: `docker-compose up`.

Složené soubory lze použít k místnímu nasazení aplikací nebo do cloudu
[Amazon ECS] (https://aws.amazon.com/ecs) nebo
[Microsoft ACI] (https://azure.microsoft.com/services/container-instances/) pomocí
Docker CLI. Další informace o tom, jak to provést, si můžete přečíst:
- [Compose for Amazon ECS] (https://docs.docker.com/engine/context/ecs-integration/)
- [Compose for Microsoft ACI] (https://docs.docker.com/engine/context/aci-integration/)

Kde získat Docker Compose
----------------------------

### Windows a macOS

Docker Compose je součástí
[Docker Desktop] (https://www.docker.com/products/docker-desktop)
pro Windows a macOS.

### Linux

Binární soubory Docker Compose si můžete stáhnout z
[stránka vydání] (https://github.com/docker/compose/releases) v tomto úložišti.

### Používání pip

Pokud vaše platforma není podporována, můžete si Docker Compose stáhnout pomocí `pip`:

`` konzole
pip install docker-compose
``

> ** Poznámka: ** Docker Compose vyžaduje Python 3.6 nebo novější.

Rychlý start
-----------

Použití Docker Compose je v zásadě třístupňový proces:
1. Definujte prostředí své aplikace pomocí souboru `Dockerfile`
   reprodukováno kdekoli.
2. Definujte služby, které tvoří vaši aplikaci, v `docker-compose.yml`
   mohou být provozovány společně v izolovaném prostředí.
3. Nakonec spusťte program „docker-compose up“ a program Compose se spustí a spustí celý
   aplikace.

Soubor Compose vypadá takto:

```yaml
services:
  web:
    build: .
    ports:
      - "5000:5000"
    volumes:
      - .:/code
  redis:
    image: redis
```


Příklady aplikací Compose najdete v našem
[Awesome Compose repository] (https://github.com/docker/awesome-compose).

Další informace o formátu Compose viz
[Vytvořit odkaz na soubor] (https://docs.docker.com/compose/compose-file/).

 Docker Compose  Spouštění aplikace
===============

Po spouštění  docker-compose příkazem `docker-compose -d`  v adresáři projektu 
lze sledovat logy aplikace pomoci příkazu `docker-compose logs` 
Ale nejlepší variantou bude instalace GUI pro docker 
(https://www.docker.com/products/docker-desktop).

### Existující služby projektu 

Tato aplikace pro svoje práce používá služby takové jako :
 * Webový server nginx  
 * Databází mysql 
 * Nastroj pro rizeni databází PhpMyAdmin
 * Interpretator programovacího jazyku php 
 
 Každá z těchto  služeb používá vlastní kontejner který popsán v souborů `docker-compose.yml`
 
### Struktura souboru docker
```text
└── mysql 
│   └── build  // docker file pro mysql
│   └── data   // Prázdný soubor , po prvnímu spouštění aplikace bude obsahovat  databází 
│   └── scripts // Obsahuje bash skripty pro inicializace databází , a SQL skripty pro načtení testovacích dat 
│   │   └── sql
│   │   │   └── dumps
└── nginx // Konfigurace webového serveru 
│   └── ssl
└── php-fpm // Konfigurace php  a bash skript pro ověření toho ze všechny kontejnery spouštěny a databáze existuje
```



