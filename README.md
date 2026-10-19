<div align="center">

# ECLIPSE

### Música, identidade e experiência digital.

Website de uma banda com apresentação de álbuns, agenda de concertos e uma base de funcionalidades de comércio e gestão de utilizadores.

![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)
![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)

**[Sobre](#sobre-o-projeto) · [Funcionalidades](#funcionalidades) · [Instalação](#executar-localmente) · [Estado](#estado-do-projeto)**

</div>

---

## Sobre o projeto

O **Eclipse** reúne a presença digital de uma banda num website com páginas dedicadas à sua história, lançamentos e concertos. O projeto combina uma interface de tema escuro com componentes Bootstrap e funcionalidades em JavaScript e PHP.

Além das páginas de apresentação, o código inclui registo e autenticação, perfil de utilizador, carrinho de bilhetes, páginas de checkout e um painel administrativo. A base de dados está definida num script SQL com entidades para utilizadores, artistas, álbuns, concertos e encomendas.

> **Estado:** em desenvolvimento. Existem funcionalidades implementadas e fluxos que ainda precisam de integração e validação, sobretudo no checkout e na administração.

## Funcionalidades

| Área | O que está presente no código |
| --- | --- |
| Página inicial | Destaques de lançamentos, próximo concerto e vídeo |
| Álbuns | Página dedicada aos lançamentos da banda |
| Tour | Agenda de eventos e seleção de bilhetes |
| Sobre | Apresentação da banda |
| Contactos | Formulário de contacto e secção de newsletter |
| Utilizadores | Registo, login, logout e página de perfil |
| Carrinho | Gestão de bilhetes com persistência em `localStorage` |
| Checkout | Páginas e scripts para o fluxo de compra |
| Administração | Painel e ficheiros de apoio à gestão |

A presença de uma página ou formulário não significa que o respetivo serviço esteja integrado. O envio de mensagens, a newsletter, o processamento de encomendas e os pagamentos precisam de validação própria.

## Tecnologias

- **HTML e CSS** para estrutura, identidade visual e personalização das páginas.
- **Bootstrap 5.3** para grelha, componentes e adaptação do layout.
- **JavaScript** para interações, seleção de bilhetes e carrinho.
- **PHP com PDO** para acesso à base de dados e gestão de sessões.
- **MySQL** para armazenamento dos dados.
- **Font Awesome, Google Fonts e Animate.css** como recursos visuais complementares.

As páginas utilizam recursos externos por CDN. É necessária ligação à internet para carregar esses recursos na configuração atual.

## Executar localmente

### 1. Preparar o ambiente

Utiliza um servidor local com **PHP**, extensão **PDO MySQL** e **MySQL**, como o XAMPP. O projeto não inclui uma etapa de compilação nem requer `npm install`.

### 2. Obter o projeto

```bash
git clone https://github.com/lucasomena920-lab/bandsa.git
cd bandsa
```

Em alternativa, descarrega o ZIP do repositório e extrai os ficheiros.

### 3. Colocar os ficheiros no servidor

No XAMPP para Windows, coloca a pasta do projeto em:

```text
C:\xampp\htdocs\bandsa
```

Inicia os serviços **Apache** e **MySQL** no painel do XAMPP.

### 4. Importar a base de dados

Abre o phpMyAdmin e importa o ficheiro `db_schema.sql`. O script contém a criação e seleção da base de dados `eclipse_db`.

Também podes importar pelo terminal, a partir da pasta do projeto:

```bash
mysql -u root -p < db_schema.sql
```

O utilizador MySQL usado na importação precisa de permissão para criar a base de dados e as tabelas.

### 5. Configurar a ligação

Edita as variáveis de ligação em `includes/config.php` de acordo com o teu ambiente:

```php
$host = 'localhost';
$dbname = 'eclipse_db';
$username = 'root';
$password = '';
```

Estes são os valores locais presentes no projeto. Usa credenciais próprias em qualquer ambiente publicado e evita guardar palavras-passe reais no repositório.

### 6. Abrir o website

Acede pelo navegador:

```text
http://localhost/bandsa/index.html
```

Para testar apenas o frontend, podes abrir as páginas HTML. Os fluxos em PHP exigem um servidor com PHP e, quando aplicável, a base de dados configurada.

## Organização dos ficheiros

| Caminho | Conteúdo |
| --- | --- |
| `index.html` | Página inicial |
| `albuns.html` | Página de álbuns |
| `tour.html` | Concertos e seleção de bilhetes |
| `sobre.html` | Apresentação da banda |
| `contactos.html` | Contactos e formulários |
| `criar-conta.html` | Formulário de registo |
| `carrinho.php` | Página de carrinho |
| `checkout.HTML` | Página de checkout |
| `css/` | Estilos principais |
| `js/` | Scripts de interação e carrinho |
| `imagens/` | Imagens e identidade visual |
| `includes/` | Configuração e rotinas de apoio |
| `php/` | Autenticação, perfil e administração |
| `assents/` e `assent style/` | Scripts e estilos adicionais de checkout |
| `db_schema.sql` | Estrutura da base de dados |

Mantém a escrita dos nomes dos ficheiros, incluindo as extensões em maiúsculas. Servidores Linux distinguem maiúsculas de minúsculas.

## Estado do projeto

O projeto disponibiliza a estrutura visual e o código de vários fluxos, mas ainda precisa de consolidar a integração entre páginas, scripts PHP e base de dados.

Pontos identificados na revisão dos ficheiros:

- O painel administrativo consulta a tabela `produtos`, que não está definida no script SQL fornecido.
- O processamento de checkout utiliza `encomendas` e `encomenda_itens`, enquanto o esquema SQL define `orders` e `order_items`.
- O ficheiro `php/admin_login.php` referencia `db.php` e `admin_tokens`, ausentes no conjunto fornecido.
- Existem scripts de carrinho e checkout em várias pastas, que precisam de consolidação.
- A configuração atual liga-se diretamente a `eclipse_db`; por isso, a importação inicial deve ser feita pelo script SQL antes de depender de `setup_db.php`.

Estas observações resultam da leitura do código. Os fluxos não foram executados nem validados de ponta a ponta.

### Próximas melhorias

- [ ] Alinhar as consultas PHP com o esquema da base de dados.
- [ ] Consolidar os scripts de carrinho e checkout.
- [ ] Validar registo, login, perfil e permissões administrativas.
- [ ] Integrar e testar contacto e newsletter.
- [ ] Definir e validar o processamento de pagamentos.
- [ ] Adicionar testes dos principais percursos de utilização.
- [ ] Preparar configuração e publicação num alojamento com PHP e MySQL.

O **GitHub Pages** pode apresentar a parte estática, mas não executa PHP nem disponibiliza MySQL. Para utilizar o backend é necessário alojamento compatível.

## Autor

Desenvolvido por **Lucas Omena**.

[![GitHub](https://img.shields.io/badge/GitHub-lucasomena920--lab-181717?style=flat-square&logo=github)](https://github.com/lucasomena920-lab)

## Licença

O conjunto de ficheiros fornecido não inclui um ficheiro `LICENSE`. As condições de utilização e redistribuição ainda precisam de ser definidas pelo autor.
