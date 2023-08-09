# dumbo-loja-virtual
Uma loja virtual feita em PHP, por enquanto está incompleta.

A virtual store named Dumbo, written in PHP, that is not yet complete.


# Usage 📚

```
The dumbo started with an simple PHP project, that turned into the "dumbo virtual store"

You can edit itens avaible in store in the "products" folder.

Create accounts, manage user´s order´s and many other features.
```
<img src="https://media.discordapp.net/attachments/1138577676291031173/1138833085115400192/image.png?width=1000&height=636" width="600">

# Run step´s ⚙

### 1. Install <a href='https://www.php.net/downloads.php'>PHP</a>

### 2. Open the project´s folder and in terminal, execute:
```
cd LojaV
```
### 3. Install <a href='https://sourceforge.net/projects/xampp/files/XAMPP%20Windows/8.2.4/xampp-windows-x64-8.2.4-0-VS16-installer.exe/download'>XAMPP</a>

### 4. Run MySQL and Apache

### 5. Create tables "pedidos, conta, carrinho":

```
CREATE TABLE conta (
    id INT  PRIMARY KEY,
    nome TEXT NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE carrinho (
    email VARCHAR(255) NOT NULL,
    itens INT NOT NULL,
    quantidade INT NOT NULL,
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    name TEXT NOT NULL,
);

CREATE TABLE pedidos (
    id INT  PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    status INT NOT NULL, 
    valor DOUBLE(10,2) NOT NULL,
    cidade TEXT NOT NULL,
    estado TEXT NOT NULL,
    endereco TEXT NOT NULL
);
```

### 6. Now, run the project with PHP Server or XAMPP:

PHP Server:

php -S localhost:(port) default.php

XAMPP:

http://localhost/LojaV

### 7. Install <a href='https://getcomposer.org/download/'>Composer</a>

```
composer require mercado-pago/dx-php
```

# Tnx for still here! Can u follow me? ❤

